<?php

namespace App\Services\AI;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ComplaintAnalysisService
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
     * Analyze a complaint with AI (language detection, translation, summary, category & priority suggestions)
     */
    public function analyze(Complaint $complaint): array
    {
        $categories = ComplaintCategory::where('is_active', true)->pluck('name')->toArray();
        $categoriesList = implode(', ', $categories);

        $prompt = <<<EOT
You are an expert civic AI analyst for Barangay North Poblacion, Maramag, Bukidnon, Philippines.
Analyze the following citizen complaint submission:

TITLE: {$complaint->title}
DESCRIPTION: {$complaint->original_description}
LOCATION: {$complaint->incident_location}
DATE: {$complaint->incident_date}

Categories available in the barangay: [{$categoriesList}]

Tasks:
1. Detect the original language (e.g. "Cebuano/Bisaya", "Tagalog", or "English").
2. Translate the description into professional, clear English (if already English, keep as English).
3. Provide a concise English summary (1-2 sentences).
4. Suggest the most appropriate category strictly from the available list: [{$categoriesList}].
5. Recommend a priority level strictly from: ["low", "medium", "high", "urgent"].
6. Explain the reason for this priority recommendation in 1-2 sentences, considering public safety, health hazards, and urgency.

Return STRICTLY a JSON object with this exact structure and nothing else:
{
  "detected_language": "Cebuano/Bisaya",
  "english_translation": "...",
  "english_summary": "...",
  "suggested_category": "...",
  "suggested_priority": "medium",
  "priority_explanation": "..."
}
EOT;

        if (! $this->isConfigured()) {
            return $this->heuristicFallback($complaint, $categories);
        }

        try {
            if ($this->provider === 'gemini') {
                $response = Http::timeout($this->timeout)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                    [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]],
                        ],
                        'generationConfig' => [
                            'response_mime_type' => 'application/json',
                            'temperature' => 0.2,
                        ],
                    ]
                );

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text');
                    $parsed = json_decode((string) $jsonText, true);
                    if ($parsed && isset($parsed['suggested_priority'])) {
                        return $parsed;
                    }
                }
            } elseif ($this->provider === 'openai') {
                $response = Http::withToken($this->apiKey)
                    ->timeout($this->timeout)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $this->model,
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a civic complaint classifier. Output pure JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'response_format' => ['type' => 'json_object'],
                        'temperature' => 0.2,
                    ]);

                if ($response->successful()) {
                    $content = $response->json('choices.0.message.content');
                    $parsed = json_decode((string) $content, true);
                    if ($parsed && isset($parsed['suggested_priority'])) {
                        return $parsed;
                    }
                }
            }
        } catch (Exception $e) {
            Log::warning('AI Analysis API call failed: ' . $e->getMessage() . '. Utilizing resilient rule-based analysis.');
        }

        return $this->heuristicFallback($complaint, $categories);
    }

    /**
     * Resilient rule-based civic analysis for Cebuano/Bisaya, Tagalog, and English.
     */
    protected function heuristicFallback(Complaint $complaint, array $categories): array
    {
        $text = mb_strtolower($complaint->title . ' ' . $complaint->original_description);

        // Language detection
        $isBisaya = str_contains($text, 'kaayo') || str_contains($text, 'basura') || str_contains($text, 'dalan')
            || str_contains($text, 'poste') || str_contains($text, 'tubo') || str_contains($text, 'naguba')
            || str_contains($text, 'palihog') || str_contains($text, 'sirado') || str_contains($text, 'baho');
        $isTagalog = str_contains($text, 'ang') && (str_contains($text, 'mga') || str_contains($text, 'po') || str_contains($text, 'kalsada') || str_contains($text, 'tubig'));

        $detectedLanguage = $isBisaya ? 'Cebuano/Bisaya' : ($isTagalog ? 'Tagalog' : 'English');

        // Category & priority heuristics
        $suggestedCategory = 'General Barangay Inquiry';
        $suggestedPriority = 'medium';
        $priorityExplanation = 'Standard civic concern requiring routine barangay review and scheduling.';
        $translation = $complaint->original_description;
        $summary = $complaint->title;

        if (str_contains($text, 'basura') || str_contains($text, 'garbage') || str_contains($text, 'trash') || str_contains($text, 'palengke') || str_contains($text, 'kalimpyo')) {
            $suggestedCategory = 'Solid Waste & Sanitation';
            $suggestedPriority = 'medium';
            $priorityExplanation = 'Waste accumulation creates sanitation and health concerns for nearby establishments and pedestrians.';
            $translation = "There is uncollected garbage accumulation reported in the area. Requesting barangay sanitation assistance.";
            $summary = "Garbage and solid waste accumulation requiring collection by barangay maintenance.";
        } elseif (str_contains($text, 'poste') || str_contains($text, 'kuryente') || str_contains($text, 'dalan') || str_contains($text, 'road') || str_contains($text, 'lubak') || str_contains($text, 'kalsada')) {
            $suggestedCategory = 'Roads & Infrastructure';
            if (str_contains($text, 'poste') || str_contains($text, 'kuryente') || str_contains($text, 'delikado') || str_contains($text, 'sirado')) {
                $suggestedPriority = 'high';
                $priorityExplanation = 'Damaged utility pole or blocked thoroughfare poses acute traffic and electrical hazards.';
            } else {
                $suggestedPriority = 'medium';
                $priorityExplanation = 'Road condition issue causing inconvenience to local motorists and pedestrians.';
            }
            $translation = "Road obstruction or infrastructure damage reported in the designated purok.";
            $summary = "Road and infrastructure concern requiring site inspection and coordination.";
        } elseif (str_contains($text, 'tubo') || str_contains($text, 'tubig') || str_contains($text, 'water') || str_contains($text, 'baha') || str_contains($text, 'leak') || str_contains($text, 'kanal') || str_contains($text, 'drainage')) {
            $suggestedCategory = 'Water Supply & Drainage';
            $suggestedPriority = 'high';
            $priorityExplanation = 'Water infrastructure malfunction impacts potable household water supply and causes road erosion.';
            $translation = "Dislodged or broken water supply pipe/drainage issue reported affecting water distribution.";
            $summary = "Water supply or drainage system malfunction requiring repair.";
        } elseif (str_contains($text, 'patay') || str_contains($text, 'away') || str_contains($text, 'shabu') || str_contains($text, 'droga') || str_contains($text, 'pusil') || str_contains($text, 'sunog') || str_contains($text, 'fire')) {
            $suggestedCategory = 'Peace and Order';
            $suggestedPriority = 'urgent';
            $priorityExplanation = 'Immediate threat to public safety and physical wellbeing requiring urgent response from Barangay Tanods or Police.';
            $translation = "Critical peace and order incident requiring urgent barangay security intervention.";
            $summary = "Urgent peace and order emergency flagged for immediate action.";
        }

        return [
            'detected_language' => $detectedLanguage,
            'english_translation' => $translation,
            'english_summary' => $summary,
            'suggested_category' => $suggestedCategory,
            'suggested_priority' => $suggestedPriority,
            'priority_explanation' => $priorityExplanation,
        ];
    }
}
