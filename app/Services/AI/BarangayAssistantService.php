<?php

namespace App\Services\AI;

use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Complaint;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BarangayAssistantService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->provider = (string) config('ai.provider', 'gemini');
        $this->apiKey = (string) config('ai.api_key', '');
        $this->model = (string) config('ai.model', 'gemini-1.5-flash');
        $this->timeout = (int) config('ai.timeout', 30);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && ! str_contains($this->apiKey, 'your-');
    }

    /**
     * Respond to a chat message from a resident.
     */
    public function respond(User $resident, ChatSession $session, string $userMessage): string
    {
        // 1. Save user's message
        ChatMessage::create([
            'session_id' => $session->id,
            'sender_type' => 'user',
            'message' => $userMessage,
        ]);

        // 2. Fetch authenticated resident's recent complaints for tracking context
        $residentComplaints = Complaint::where('resident_id', $resident->id)
            ->latest()
            ->take(5)
            ->get();

        $complaintContext = $residentComplaints->map(function ($c) {
            $badge = $c->statusBadge();
            return "- Ref: {$c->reference_number} | Title: {$c->title} | Status: {$badge['label']} ({$c->status}) | Remarks: " . ($c->resident_visible_remarks ?: 'Under evaluation');
        })->implode("\n");

        // 3. Fetch latest active announcements for barangay updates context
        $announcements = Announcement::published()->latest()->take(3)->get();
        $announcementContext = $announcements->map(function ($a) {
            return "- Title: {$a->title} (Category: {$a->category}, Date: " . ($a->event_start ? $a->event_start->format('M d, Y') : 'N/A') . ")";
        })->implode("\n");

        // 4. Check for direct resident complaint inquiry
        $lowerMsg = mb_strtolower($userMessage);
        if (str_contains($lowerMsg, 'reklamo') || str_contains($lowerMsg, 'complaint') || str_contains($lowerMsg, 'status') || str_contains($lowerMsg, 'track') || str_contains($lowerMsg, 'npc-')) {
            if ($residentComplaints->isNotEmpty()) {
                // If message matches a specific reference number
                foreach ($residentComplaints as $c) {
                    if (str_contains(strtoupper($userMessage), $c->reference_number)) {
                        $badge = $c->statusBadge();
                        $reply = "Maayong adlaw, {$resident->name}! Bahin sa imong reklamo nga **{$c->reference_number}** (*{$c->title}*):\n\n"
                            . "• **Status:** {$badge['label']}\n"
                            . "• **Purok:** " . ($c->purok?->name ?? 'North Poblacion') . "\n"
                            . "• **Barangay Remarks:** " . ($c->resident_visible_remarks ?: 'Giproseso pa sa kadumalahan sa barangay.') . "\n\n"
                            . "Pahibaloon ka namo pinaagi sa SMS/System notification kung duna nay dugang kalambuan.";
                        
                        ChatMessage::create([
                            'session_id' => $session->id,
                            'sender_type' => 'assistant',
                            'message' => $reply,
                        ]);

                        return $reply;
                    }
                }
            }
        }

        // 5. Build system prompt for AI or fallback
        $systemPrompt = <<<EOT
You are the "North Poblacion AI Barangay Assistant", the official digital assistant of Barangay North Poblacion, Municipality of Maramag, Province of Bukidnon, Philippines.
You assist residents with warmth, respect ("po/opo" in Tagalog, polite Bisaya/Cebuano, or professional English).
You fluently answer in the language the resident speaks (Cebuano/Bisaya, Tagalog, or English).

OFFICIAL BARANGAY INFORMATION:
- Location: Barangay Hall, Sayre Highway, North Poblacion, Maramag, Bukidnon
- Office Hours: Monday to Friday, 8:00 AM to 5:00 PM
- Punong Barangay / Council: Barangay Council of North Poblacion
- Key Services: Barangay Clearance, Certificate of Indigency, Certificate of Residency, First-Time Jobseekers Certificate (Free), Lupong Tagapamayapa dispute mediation.
- Waste Collection: Scheduled weekly per Purok; segregated solid waste collection (biodegradable vs non-biodegradable).
- Emergency Services: PNP Maramag, BFP Maramag, MDRRMO Emergency Rescue, Barangay Tanod 24/7 Outpost.

THIS RESIDENT'S AUTHENTIC RECENT COMPLAINTS (PRIVATE TO THIS RESIDENT ONLY):
{$complaintContext}

LATEST BARANGAY ANNOUNCEMENTS:
{$announcementContext}

GUIDELINES:
1. Always address the resident courteously as {$resident->name}.
2. If the resident asks about their complaints, refer only to their authentic records listed above.
3. If they ask how to file a complaint, guide them to click "New Complaint" on the left sidebar.
4. Keep answers clear, helpful, and culturally respectful.
EOT;

        $assistantReply = null;

        if ($this->isConfigured()) {
            try {
                if ($this->provider === 'gemini') {
                    $response = Http::timeout($this->timeout)->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                        [
                            'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                            'contents' => [
                                ['role' => 'user', 'parts' => [['text' => $userMessage]]],
                            ],
                            'generationConfig' => [
                                'temperature' => 0.4,
                                'maxOutputTokens' => 800,
                            ],
                        ]
                    );

                    if ($response->successful()) {
                        $assistantReply = $response->json('candidates.0.content.parts.0.text');
                    }
                }
            } catch (Exception $e) {
                Log::warning('AI Barangay Assistant API error: ' . $e->getMessage());
            }
        }

        // If AI reply is empty, use smart local conversational generator
        if (! $assistantReply) {
            $assistantReply = $this->generateLocalResponse($userMessage, $resident, $residentComplaints, $announcements);
        }

        // 6. Save assistant message
        ChatMessage::create([
            'session_id' => $session->id,
            'sender_type' => 'assistant',
            'message' => $assistantReply,
        ]);

        return $assistantReply;
    }

    /**
     * Local knowledge-based conversational responses in Cebuano, Tagalog, and English.
     */
    protected function generateLocalResponse(string $msg, User $resident, $complaints, $announcements): string
    {
        $text = mb_strtolower($msg);

        if (str_contains($text, 'clearance') || str_contains($text, 'indigency') || str_contains($text, 'residency') || str_contains($text, 'cedula')) {
            return "Maayong adlaw, {$resident->name}! Aron makakuha og **Barangay Clearance, Certificate of Residency, o Indigency** sa Barangay North Poblacion, Maramag:\n\n"
                . "1. Bisitaha ang atong Barangay Hall (Sayre Highway, North Poblacion).\n"
                . "2. Oras sa Opisina: Lunes hangtod Biyernes, 8:00 AM – 5:00 PM.\n"
                . "3. Dad-a ang: Valid Government ID, Purok Clearance/Certification, ug Cedula.\n"
                . "4. Libre ang First-Time Jobseekers Certificate subay sa RA 11261.\n\n"
                . "Kung duna kay dugang pangutana, andam ko motabang kanimo!";
        }

        if (str_contains($text, 'basura') || str_contains($text, 'garbage') || str_contains($text, 'waste') || str_contains($text, 'hakot')) {
            return "Maayong adlaw, {$resident->name}!\n\n"
                . "Ang pagkolekta sa basura sa Barangay North Poblacion ginasubay matag Purok kausa sa usa ka semana subay sa iskedyul sa MENRO/Barangay Sanitation.\n\n"
                . "• **Pahinumdom:** I-segregate ang malata (biodegradable) ug dili malata (non-biodegradable).\n"
                . "• Kung duna kay uncollected garbage o illegal dumping sa inyong lugar, pwede ka magsumite og reklamo gamit ang **\"New Complaint\"** button dinhi sa sistema!";
        }

        if (str_contains($text, 'reklamo') || str_contains($text, 'complaint') || str_contains($text, 'unsaon') || str_contains($text, 'submit')) {
            if ($complaints->isNotEmpty()) {
                $list = $complaints->map(function ($c) {
                    $b = $c->statusBadge();
                    return "• **{$c->reference_number}**: {$c->title} — *{$b['label']}*";
                })->implode("\n");
                return "Maayong adlaw, {$resident->name}! Ania ang lista sa imong mga kasamtangang reklamo:\n\n"
                    . $list . "\n\n"
                    . "Aron magsumite og bag-ong reklamo, i-click lamang ang **\"New Complaint\"** sa menu. Awtomatiko kining madawat ug susihon sa atong AI ug sa mga opisyal sa Barangay North Poblacion.";
            }

            return "Maayong adlaw, {$resident->name}! Sayon ra ang pagsumite og reklamo:\n\n"
                . "1. I-click ang **\"New Complaint\"** sa wala nga bahin sa screen.\n"
                . "2. Isulat ang titulo, detalye sa hitabo, lokasyon/Purok, ug petsa.\n"
                . "3. Mahimo kang mag-attach og litrato o dokumento isip ebidensya.\n"
                . "4. Human ma-submit, makadawat ka og Reference Number (sama sa `NPC-2026-XXXX`) aron masubay ang lakang sa barangay.";
        }

        if (str_contains($text, 'emergency') || str_contains($text, 'hotline') || str_contains($text, 'pulis') || str_contains($text, 'fire') || str_contains($text, 'tabang')) {
            return "🚨 **EMERGENCY HOTLINES — MARAMAG, BUKIDNON** 🚨\n\n"
                . "• **Barangay North Poblacion Tanod Outpost:** 0917-000-4444\n"
                . "• **MDRRMO Rescue / Ambulance:** 0917-000-3333 / (088) 828-1111\n"
                . "• **Maramag Municipal Police Station:** 0917-000-1111\n"
                . "• **Maramag Bureau of Fire Protection (BFP):** 0917-000-2222\n\n"
                . "Palihog tawag dayon sa mga numero sa ibabaw kung dunay dinalian nga katalagman o peligro.";
        }

        return "Maayong adlaw, {$resident->name}! Ako ang North Poblacion AI Barangay Assistant.\n\n"
            . "Andam ko motabang kanimo bahin sa:\n"
            . "• Pagsusi sa status sa imong mga reklamo\n"
            . "• Pamaagi sa pag-file og bag-ong reklamo\n"
            . "• Mga serbisyo ug rekisitos sa Barangay Hall (Clearance, Indigency, etc.)\n"
            . "• Mga anunsyo ug kalihokan sa Barangay North Poblacion, Maramag\n"
            . "• Emergency hotlines ug waste management schedule\n\n"
            . "Unsa may akong ikaabag kanimo karong adlawa?";
    }
}
