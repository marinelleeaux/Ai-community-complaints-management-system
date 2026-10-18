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
        $this->timeout = (int) config('ai.timeout', 4);
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
                        $reply = "Maayong adlaw, {$resident->name}! Bahin sa imong reklamo nga {$c->reference_number} ({$c->title}):\n\n"
                            . "• Status: {$badge['label']}\n"
                            . "• Purok: " . ($c->purok?->name ?? 'North Poblacion') . "\n"
                            . "• Barangay Remarks: " . ($c->resident_visible_remarks ?: 'Giproseso pa sa kadumalahan sa barangay.') . "\n\n"
                            . "Pahibaloon ka namo pinaagi sa SMS o System notification kung duna nay dugang kalambuan.";
                        
                        $reply = str_replace('*', '', $reply);

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
You are the "North Poblacion AI Barangay Assistant" — a warm, friendly, empathetic companion and local guide for residents of Barangay North Poblacion, Municipality of Maramag, Province of Bukidnon, Philippines.

CRITICAL PERSONA RULES:
1. TALK LIKE A TRUE FRIEND ("HIGALA" / "KAIBIGAN"):
   - Speak conversationally, warmly, and empathetically, like a caring neighbor living in North Poblacion who is always happy to chat and help out.
   - Do NOT just spit out rigid, robotic bureaucratic lists or dry reminders. Reply directly to what they are asking, validate how they feel, comfort them if they are stressed or frustrated about an issue in their purok, and converse naturally.
   - Address the resident warmly as "higala kong {$resident->name}" (Bisaya/Cebuano), "kaibigang {$resident->name}" (Tagalog), or "my friend {$resident->name}" (English).
   - Match the resident's language: if they speak Bisaya/Cebuano, reply in natural warm conversational Bisaya; if Tagalog, reply in friendly Tagalog; if English, reply in friendly English.

2. CRITICAL FORMATTING RULE (NO ASTERISKS):
   - ABSOLUTELY DO NOT use asterisks (*) or markdown symbols like `**`, `*`, or bullet asterisks anywhere in your response. The user does not want asterisks or messy symbols.
   - Write cleanly in natural plain sentences and conversational paragraphs.
   - For bullet lists, use a simple bullet point (•) or number (1, 2, 3) followed by plain text without asterisks.

3. OFFICIAL BARANGAY INFORMATION:
   - Location: Barangay Hall, Sayre Highway, North Poblacion, Maramag, Bukidnon
   - Office Hours: Monday to Friday, 8:00 AM to 5:00 PM
   - Key Services: Barangay Clearance, Certificate of Indigency, Certificate of Residency, First-Time Jobseekers Certificate (Free), Lupong Tagapamayapa dispute mediation.
   - Waste Collection: Scheduled weekly per Purok; segregated solid waste collection (biodegradable vs non-biodegradable).
   - Emergency Services: PNP Maramag, BFP Maramag, MDRRMO Emergency Rescue, Barangay Tanod 24/7 Outpost.

4. THIS RESIDENT'S RECENT COMPLAINTS (PRIVATE TO THIS RESIDENT ONLY):
{$complaintContext}

5. LATEST BARANGAY ANNOUNCEMENTS:
{$announcementContext}

6. CONVERSATIONAL GUIDANCE:
   - When asked about their complaints, give a warm, reassuring update using the authentic records above. Tell them you're keeping an eye on it with the barangay staff for them.
   - If they want to report a problem or file a complaint, encourage them warmly and guide them to click "New Complaint" on the menu.
   - Keep answers clear, compassionate, helpful, and culturally respectful.
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
                                'temperature' => 0.5,
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

        // Ensure zero asterisks (*) or symbol clutter in output
        $assistantReply = str_replace('*', '', $assistantReply);
        $assistantReply = preg_replace('/ {2,}/', ' ', $assistantReply);

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

        // Friendly greetings
        if (str_contains($text, 'kumusta') || str_contains($text, 'hello') || str_contains($text, 'hi') || str_contains($text, 'maayong')) {
            return "Kumusta ka, higala kong {$resident->name}! Nalipay kaayo ko nga nakig-istorya ka nako karon. Isipa ko nga imong suod nga higala ug silingan dinhi sa Barangay North Poblacion, Maramag nga andam pirmi maminaw ug motabang kanimo.\n\n"
                . "Naa ba kay gustong ipangutana bahin sa imong reklamo, mga serbisyo sa barangay, o unsa pa diha? Sultihi lang ko, higala!";
        }

        if (str_contains($text, 'clearance') || str_contains($text, 'indigency') || str_contains($text, 'residency') || str_contains($text, 'cedula')) {
            return "Walay problema, higala kong {$resident->name}! Sayon ra kaayo na. Para sa imong Barangay Clearance, Certificate of Residency, o Indigency, pwede ra kaayo ka mohapit sa atong Barangay Hall diha sa Sayre Highway (abli mi Lunes hangtod Biyernes, 8:00 AM hangtod 5:00 PM).\n\n"
                . "1. Dad-a lang imong Valid Government ID ug Cedula.\n"
                . "2. Kung para sa trabaho ug first-time jobseeker ka, libre gyud na subay sa Republic Act 11261!\n"
                . "3. Dali ra kini maproseso sa atong mga buotang kawani sa barangay.\n\n"
                . "May lain pa ba koy ikatabang nimo, higala?";
        }

        if (str_contains($text, 'basura') || str_contains($text, 'garbage') || str_contains($text, 'waste') || str_contains($text, 'hakot')) {
            return "Ay higala kong {$resident->name}, nasabtan gyud nako kung unsa ka-importante ang limpyo ug presko nga palibot para sa atong pamilya ug mga silingan dinhi sa North Poblacion!\n\n"
                . "• Ang pagkolekta sa basura ginasubay matag semana sa tagsa-tagsa ka purok subay sa iskedyul sa MENRO ug barangay sanitation.\n"
                . "• Tabangi lang pod nato atong mga kolektor pinaagi sa pag-segregate sa malata (biodegradable) ug dili malata (non-biodegradable).\n"
                . "• Kung duna man gani nagtambak og basura o wala nahipos sa inyong dapit, sultihi ko o i-file nato sa \"New Complaint\" aron matabangan tika nga maaksyonan dayon kini sa barangay. Ania ra ko naga-abag nimo!";
        }

        if (str_contains($text, 'reklamo') || str_contains($text, 'complaint') || str_contains($text, 'unsaon') || str_contains($text, 'submit')) {
            if ($complaints->isNotEmpty()) {
                $list = $complaints->map(function ($c) {
                    $b = $c->statusBadge();
                    return "• {$c->reference_number}: {$c->title} — {$b['label']}";
                })->implode("\n");

                return "Higala kong {$resident->name}, gitan-aw nako ang imong mga gisumite nga reklamo dinhi sa sistema:\n\n"
                    . $list . "\n\n"
                    . "Ayaw kabalaka higala, ginasubay gyud kini pag-ayo sa barangay aron mahatagan og solusyon ang imong gikabalak-an. Kung gusto kang mag-file og dugang reklamo, i-click lang ang \"New Complaint\" sa menu ug ako kang tabangan sa pag-asikaso niini!";
            }

            return "Walay problema, higala kong {$resident->name}! Sayon ra kaayo ang pagsumite og reklamo dinhi sa atong sistema:\n\n"
                . "1. I-click lang ang \"New Complaint\" sa menu sa wala.\n"
                . "2. Isulat ang titulo, detalye sa hitabo, lokasyon/Purok, ug petsa.\n"
                . "3. Mahimo kang mag-attach og litrato aron mas klaro ang ebidensya.\n"
                . "4. Human ma-submit, makadawat dayon ka og Tracking Reference Number (pananglitan NPC-2026-XXXX).\n\n"
                . "Ayaw pagpanuko pagsulti nako kung naglisud ka, kay ubanan tika sa matag lakang!";
        }

        if (str_contains($text, 'emergency') || str_contains($text, 'hotline') || str_contains($text, 'pulis') || str_contains($text, 'fire') || str_contains($text, 'tabang')) {
            return "🚨 HIGALA, ANIA ANG MGA EMERGENCY HOTLINES SA MARAMAG: 🚨\n\n"
                . "• Barangay North Poblacion Tanod Outpost: 0917-000-4444\n"
                . "• MDRRMO Rescue / Ambulance: 0917-000-3333 / (088) 828-1111\n"
                . "• Maramag Municipal Police Station: 0917-000-1111\n"
                . "• Maramag Bureau of Fire Protection (BFP): 0917-000-2222\n\n"
                . "Amping kanunay higala, ug panalipdan ang imong kaugalingon ug pamilya! Kung luwas na ka, sultihi ko aron matabangan pod tika sa barangay.";
        }

        return "Kumusta ka, higala kong {$resident->name}! Nalipay kaayo ko nga nakig-chat ka nako karon. Isipa ko nga imong matinud-anong higala ug silingan dinhi sa Barangay North Poblacion, Maramag.\n\n"
            . "Andam ko motabang ug makig-istorya kanimo bahin sa:\n"
            . "• Pagsusi sa kahimtang sa imong mga reklamo\n"
            . "• Pamaagi sa pag-file og opisyal nga reklamo\n"
            . "• Mga rekisitos sa Barangay Clearance, Indigency, ug uban pa\n"
            . "• Mga kalihokan ug balita sa Barangay North Poblacion\n"
            . "• Emergency hotlines ug schedule sa basura\n\n"
            . "Unsa may akong ikaabag o ma-estorya nimo karong adlawa, higala?";
    }
}
