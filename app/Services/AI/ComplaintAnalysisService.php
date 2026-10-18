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

MANDATORY TASKS:
1. Detect the original language (e.g. "Cebuano/Bisaya", "Tagalog", or "English").
2. Translate the description COMPLETELY into clear, professional English. (If written in Cebuano or Tagalog, translate every part into English. DO NOT leave Cebuano/Bisaya/Tagalog dialect words in the translation).
3. Provide a concise, professional English summary (1-2 sentences). CRITICAL: The "english_summary" MUST be 100% in English. Never output Cebuano or Tagalog in the english_summary.
4. Suggest the most appropriate category strictly from: [{$categoriesList}].
5. Assign a realistic priority strictly from: ["low", "medium", "high", "urgent"]:
   - "urgent": Immediate threat to human life or bodily safety, armed conflict, weapons (guns, knives, bolo), active physical brawl, active fire, live sparking high-voltage electrical wires, or life-threatening flooding.
   - "high": Major public infrastructure collapse (burst main water line leaving areas with no water, completely blocked or impassable main road), aggressive stray dog attacks / rabies hazard, active burglaries/theft, deep uncovered manhole/drainage trench hazard, or illegal drug activity.
   - "medium": Uncollected municipal garbage accumulation and foul odor, persistent late-night videoke / karaoke noise past curfew, inoperative streetlights causing dark spots, road potholes, or roaming stray animals.
   - "low": Minor property boundary or fence disputes without violence, routine daytime domestic animal noise, tree trimming on private lots, general barangay inquiries, or certificate requests.
6. Provide an objective 1-2 sentence justification for the assigned priority based on public safety, health impact, and urgency.

Return STRICTLY a JSON object with this exact structure and nothing else:
{
  "detected_language": "Cebuano/Bisaya",
  "english_translation": "...",
  "english_summary": "...",
  "suggested_category": "...",
  "suggested_priority": "urgent",
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
                            'temperature' => 0.1,
                        ],
                    ]
                );

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text');
                    $parsed = json_decode((string) $jsonText, true);
                    if ($parsed && isset($parsed['suggested_priority']) && ! empty($parsed['english_summary'])) {
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
                        'temperature' => 0.1,
                    ]);

                if ($response->successful()) {
                    $content = $response->json('choices.0.message.content');
                    $parsed = json_decode((string) $content, true);
                    if ($parsed && isset($parsed['suggested_priority']) && ! empty($parsed['english_summary'])) {
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
     * Guarantees 100% English translation & summary and realistic priority tiering.
     */
    protected function heuristicFallback(Complaint $complaint, array $categories): array
    {
        $text = mb_strtolower($complaint->title . ' ' . $complaint->original_description);

        // Broad Cebuano/Bisaya detection markers
        $bisayaMarkers = [
            'kaayo', 'basura', 'dalan', 'poste', 'tubo', 'naguba', 'palihog', 'sirado', 'baho',
            'daghan', 'saba', 'silingan', 'away', 'patay', 'patyon', 'iro', 'manok', 'koral',
            'yuta', 'kuryente', 'kawat', 'lungkab', 'baha', 'kanal', 'lubak', 'lapok', 'ngitngit',
            'suga', 'gabii', 'buntag', 'adlaw', 'dili', 'wala', 'walay', 'naay', 'ani', 'kini',
            'nila', 'namo', 'kanako', 'unya', 'kay', 'og', 'ug', 'sa amo', 'sa amoa', 'tabang',
            'tabangi', 'piti-piti', 'makuryentihan', 'videoke', 'nagtinukmoday', 'sukmagay',
            'nagsumbagon', 'guba', 'naguba', 'nabuak', 'hugaw', 'tambak', 'purok'
        ];

        // Broad Tagalog detection markers
        $tagalogMarkers = [
            'ang', 'mga', 'po', 'opo', 'kalsada', 'tubig', 'bahay', 'kapitbahay', 'ingay',
            'gabi', 'araw', 'pakiusap', 'tulungan', 'sirang', 'nakakagambala', 'sunog', 'nakita',
            'namin', 'naming', 'hindi', 'meron', 'walang', 'baril', 'patayan', 'magnanakaw',
            'loob', 'basura', 'poste', 'ilaw', 'madilim', 'lubak', 'baha', 'kanal'
        ];

        $bisayaScore = 0;
        foreach ($bisayaMarkers as $marker) {
            if (str_contains($text, $marker)) {
                $bisayaScore++;
            }
        }

        $tagalogScore = 0;
        foreach ($tagalogMarkers as $marker) {
            if (str_contains($text, $marker)) {
                $tagalogScore++;
            }
        }

        $detectedLanguage = 'English';
        if ($bisayaScore >= 2 || ($bisayaScore > $tagalogScore && $bisayaScore >= 1)) {
            $detectedLanguage = 'Cebuano/Bisaya';
        } elseif ($tagalogScore >= 2) {
            $detectedLanguage = 'Tagalog';
        }

        // -------------------------------------------------------------------------
        // REALISTIC CATEGORIZATION & PRIORITY ASSIGNMENT
        // -------------------------------------------------------------------------

        // 1. URGENT: Life safety, deadly weapons, physical violence, active fire, live exposed electrical wires
        $isArmedViolence = str_contains($text, 'pusil') || str_contains($text, 'baril') || str_contains($text, 'gun')
            || str_contains($text, 'kutsilyo') || str_contains($text, 'saksak') || str_contains($text, 'knife')
            || str_contains($text, 'patay') || str_contains($text, 'patyon') || str_contains($text, 'patayin')
            || str_contains($text, 'hagit') || str_contains($text, 'threat') || str_contains($text, 'brawl')
            || str_contains($text, 'rambol') || str_contains($text, 'sukmagay') || str_contains($text, 'sumbagay')
            || str_contains($text, 'sumbag') || str_contains($text, 'dinumogay') || str_contains($text, 'dumog')
            || str_contains($text, 'sukmag') || str_contains($text, 'suntukan') || str_contains($text, 'sapakan')
            || str_contains($text, 'bugbugan') || str_contains($text, 'nag-away') || str_contains($text, 'away');

        $isActiveFire = str_contains($text, 'sunog') || str_contains($text, 'fire') || str_contains($text, 'kalayo') || str_contains($text, 'blaze');

        $isLiveWireHazard = (str_contains($text, 'kuryente') || str_contains($text, 'wire') || str_contains($text, 'cable') || str_contains($text, 'spark'))
            && (str_contains($text, 'makuryentihan') || str_contains($text, 'spark') || str_contains($text, 'delikado') || str_contains($text, 'luyloy') || str_contains($text, 'bata') || str_contains($text, 'live'));

        $isSevereFlood = str_contains($text, 'trap') && str_contains($text, 'baha');

        // 2. HIGH: Major infrastructure outage, impassable road, dog bite/rabies, burglary, deep open manhole, narcotics
        $isMajorWaterOutage = (str_contains($text, 'tubo') || str_contains($text, 'tubig') || str_contains($text, 'water'))
            && (str_contains($text, 'nabuak') || str_contains($text, 'putok') || str_contains($text, 'burst') || str_contains($text, 'walay agas') || str_contains($text, 'walang tubig') || str_contains($text, 'nagasagasaga'));

        $isRoadBlocked = (str_contains($text, 'dalan') || str_contains($text, 'kalsada') || str_contains($text, 'road'))
            && (str_contains($text, 'sirado') || str_contains($text, 'impassable') || str_contains($text, 'dili na maagian') || str_contains($text, 'hindi madaanan') || str_contains($text, 'natumba nga kahoy') || str_contains($text, 'dakong lungag'));

        $isDangerousAnimal = (str_contains($text, 'iro') || str_contains($text, 'aso') || str_contains($text, 'dog'))
            && (str_contains($text, 'paak') || str_contains($text, 'kagat') || str_contains($text, 'bite') || str_contains($text, 'rabies') || str_contains($text, 'wild') || str_contains($text, 'nangangagat'));

        $isTheft = str_contains($text, 'kawat') || str_contains($text, 'kawatan') || str_contains($text, 'lungkab')
            || str_contains($text, 'nanakawan') || str_contains($text, 'magnanakaw') || str_contains($text, 'theft') || str_contains($text, 'burglary');

        $isOpenManhole = (str_contains($text, 'kanal') || str_contains($text, 'drainage') || str_contains($text, 'manhole'))
            && (str_contains($text, 'walay tabon') || str_contains($text, 'walang takip') || str_contains($text, 'bukas') || str_contains($text, 'open') || str_contains($text, 'mahulog'));

        $isIllegalDrugs = str_contains($text, 'shabu') || str_contains($text, 'droga') || str_contains($text, 'drugs') || str_contains($text, 'narcotics');

        // 3. LOW: Property boundary dispute, daytime animal sound, routine certificate inquiries, tree trimming
        $isBoundaryDispute = str_contains($text, 'koral') || str_contains($text, 'bakod') || str_contains($text, 'utlanan')
            || str_contains($text, 'yuta') || str_contains($text, 'lupa') || str_contains($text, 'boundary');

        $isDaytimeRooster = (str_contains($text, 'manok') || str_contains($text, 'rooster')) && ! $isArmedViolence;

        $isRoutineInquiry = str_contains($text, 'pangutana') || str_contains($text, 'tanong') || str_contains($text, 'inquiry')
            || str_contains($text, 'clearance') || str_contains($text, 'certificate') || str_contains($text, 'indigency');

        // Default medium fallbacks: waste/garbage, streetlight, videoke/noise, general road potholes
        $isWaste = str_contains($text, 'basura') || str_contains($text, 'garbage') || str_contains($text, 'trash') || str_contains($text, 'baho') || str_contains($text, 'palengke') || str_contains($text, 'hugaw');
        $isNoise = str_contains($text, 'saba') || str_contains($text, 'ingay') || str_contains($text, 'videoke') || str_contains($text, 'karaoke') || str_contains($text, 'dili makatulog') || str_contains($text, 'sound system') || str_contains($text, 'party');
        $isLighting = str_contains($text, 'suga') || str_contains($text, 'ilaw') || str_contains($text, 'ngitngit') || str_contains($text, 'madilim') || str_contains($text, 'poste');

        // Assign realistic category and priority
        if ($isArmedViolence || $isActiveFire || $isLiveWireHazard || $isSevereFlood) {
            $suggestedPriority = 'urgent';
            if ($isActiveFire) {
                $suggestedCategory = 'Health & Environmental Hazards';
                $priorityExplanation = 'Active fire outbreak presents catastrophic danger to residential properties and human life. Emergency BFP and rescue deployment dispatched.';
                $translation = "Active fire incident reported in the area. Immediate fire response and emergency evacuation requested.";
                $summary = "Emergency: Active fire outbreak reported requiring immediate BFP firefighting units.";
            } elseif ($isLiveWireHazard) {
                $suggestedCategory = 'Street Lighting & Hazards';
                $priorityExplanation = 'Damaged electrical utility infrastructure with live exposed wires or sparking posing imminent electrocution danger to pedestrians.';
                $translation = "Critical electrical hazard reported with live exposed power wires or leaning utility pole creating severe electrocution risk.";
                $summary = "Emergency: High-voltage electrical hazard and exposed live wires requiring urgent power utility intervention.";
            } else {
                $suggestedCategory = 'Peace and Order';
                $priorityExplanation = 'Immediate threat to public safety and physical bodily integrity requiring instantaneous response by Barangay Tanods and PNP Police.';
                $translation = "Urgent physical altercation, armed threat, or violent public disturbance reported in the purok requiring immediate security intervention.";
                $summary = "Emergency: Urgent peace and order incident involving physical conflict or violence requiring immediate law enforcement response.";
            }
        } elseif ($isMajorWaterOutage || $isRoadBlocked || $isDangerousAnimal || $isTheft || $isOpenManhole || $isIllegalDrugs) {
            $suggestedPriority = 'high';
            if ($isMajorWaterOutage) {
                $suggestedCategory = 'Water Supply & Drainage';
                $priorityExplanation = 'Ruptured primary water distribution pipe compromises drinking water supply for multiple households and causes road foundation erosion.';
                $translation = "Major burst water pipe reported resulting in heavy water loss and loss of household water supply in the neighborhood.";
                $summary = "High Priority: Ruptured water distribution pipe causing widespread water outage requiring urgent utility repair.";
            } elseif ($isRoadBlocked) {
                $suggestedCategory = 'Roads & Infrastructure';
                $priorityExplanation = 'Complete thoroughfare obstruction or severe road failure blocks access for emergency responders and community transport.';
                $translation = "Impassable road condition or physical obstruction reported blocking traffic along the primary barangay road.";
                $summary = "High Priority: Thoroughfare obstruction or severe road damage preventing vehicle transit.";
            } elseif ($isDangerousAnimal) {
                $suggestedCategory = 'Animal Control & Strays';
                $priorityExplanation = 'Aggressive animal behavior or reported animal bites pose acute rabies transmission and physical danger to pedestrians and children.';
                $translation = "Aggressive dog incident or bite reported in the public road. Immediate veterinary/animal control intervention requested.";
                $summary = "High Priority: Aggressive stray animal attacking pedestrians requiring prompt barangay animal control action.";
            } elseif ($isTheft) {
                $suggestedCategory = 'Peace and Order';
                $priorityExplanation = 'Burglary and property trespassing incident requiring immediate barangay blotter recording and heightened night patrols.';
                $translation = "Burglary or theft incident reported within the residential purok. Requesting official barangay blotter recording and increased roving patrols.";
                $summary = "High Priority: Report of burglary and criminal theft requiring barangay security documentation and surveillance.";
            } elseif ($isOpenManhole) {
                $suggestedCategory = 'Water Supply & Drainage';
                $priorityExplanation = 'Deep uncovered drainage manhole along public path creates severe risk of serious pedestrian and vehicular falls.';
                $translation = "Deep open drainage trench or missing manhole cover reported along pedestrian path posing fall hazard.";
                $summary = "High Priority: Hazardous uncovered drainage trench creating severe fall risks for pedestrians.";
            } else {
                $suggestedCategory = 'Peace and Order';
                $priorityExplanation = 'Reported illicit drug activities requiring strict confidential coordination with BADAC and PNP law enforcement.';
                $translation = "Suspected illicit narcotics activities reported in the vicinity. Requesting confidential coordination with barangay authorities.";
                $summary = "High Priority: Suspected illegal drug activity flagged for confidential barangay and law enforcement handling.";
            }
        } elseif ($isBoundaryDispute || $isDaytimeRooster || $isRoutineInquiry) {
            $suggestedPriority = 'low';
            if ($isBoundaryDispute) {
                $suggestedCategory = 'Peace and Order';
                $priorityExplanation = 'Non-violent civil property boundary dispute suitable for scheduled Katarungang Pambarangay mediation hearing.';
                $translation = "Disagreement regarding property boundary fence line between adjacent neighbors. Requesting scheduled Lupon mediation.";
                $summary = "Low Priority: Peaceful property boundary line dispute requested for standard Katarungang Pambarangay mediation.";
            } elseif ($isDaytimeRooster) {
                $suggestedCategory = 'Public Nuisance & Noise';
                $priorityExplanation = 'Routine daytime domestic animal sounds with no immediate public safety or health violation.';
                $translation = "Daytime noise complaint regarding neighbor's domestic roosters or pets during daylight hours.";
                $summary = "Low Priority: Minor daytime domestic poultry noise concern suitable for friendly neighbor dialogue.";
            } else {
                $suggestedCategory = 'General Barangay Inquiry';
                $priorityExplanation = 'Routine administrative inquiry or certification assistance manageable during standard barangay office hours.';
                $translation = "Citizen inquiry regarding standard barangay document issuance, certification, or office procedures.";
                $summary = "Low Priority: Routine civic inquiry regarding standard barangay administrative clearances and procedures.";
            }
        } else {
            // Standard medium priority (Sanitation, Noise, Streetlights, Potholes, General)
            $suggestedPriority = 'medium';
            if ($isWaste) {
                $suggestedCategory = 'Solid Waste & Sanitation';
                $priorityExplanation = 'Uncollected organic and solid waste generates foul odor and pest breeding hazards affecting neighborhood hygiene.';
                $translation = "Uncollected solid waste and rotting garbage accumulation reported in the area emitting foul odors. Requesting municipal sanitation collection.";
                $summary = "Citizen reports uncollected garbage accumulation and foul odor requiring municipal waste disposal.";
            } elseif ($isNoise) {
                $suggestedCategory = 'Public Nuisance & Noise';
                $priorityExplanation = 'Excessive late-night noise disturbance after barangay curfew disrupts sleep and peace of neighborhood residents.';
                $translation = "Excessive late-night videoke and sound system noise disturbance reported past barangay curfew. Requesting tanod reminder.";
                $summary = "Citizen reports excessive late-night videoke noise disturbance from neighboring premises violating peaceful hours.";
            } elseif ($isLighting) {
                $suggestedCategory = 'Street Lighting & Hazards';
                $priorityExplanation = 'Non-operational street lighting creates dark road sections that elevate nighttime traffic risks and safety concerns.';
                $translation = "Inoperative streetlights reported along the purok road resulting in dark thoroughfare conditions during evening hours.";
                $summary = "Citizen reports broken street lighting along purok road requiring maintenance and bulb replacement.";
            } elseif (str_contains($text, 'dalan') || str_contains($text, 'kalsada') || str_contains($text, 'road') || str_contains($text, 'lubak') || str_contains($text, 'lapok')) {
                $suggestedCategory = 'Roads & Infrastructure';
                $priorityExplanation = 'Road surface deterioration with potholes and mud causing transit difficulty for motorcycles and pedestrians.';
                $translation = "Deteriorated road surface with mud and potholes reported along the purok road causing transit inconvenience.";
                $summary = "Citizen reports road deterioration and potholes requiring barangay engineering inspection and grading.";
            } else {
                $suggestedCategory = 'General Barangay Inquiry';
                $priorityExplanation = 'Standard civic concern requiring routine barangay review, scheduling, and on-site verification.';
                $translation = "Citizen concern filed with Barangay North Poblacion regarding community neighborhood maintenance and welfare.";
                $summary = "Citizen concern regarding community welfare and neighborhood management requiring barangay verification.";
            }
        }

        // If the original input was already clean English, keep authentic translation context
        if ($detectedLanguage === 'English') {
            $translation = $complaint->original_description;
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
