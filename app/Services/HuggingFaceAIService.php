<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceAIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://router.huggingface.co/models';

    public function __construct()
    {
        $this->apiKey = config('services.hugging_face.api_key');
        $this->model = config('services.hugging_face.model', 'mistralai/Mistral-7B-Instruct-v0.1');
    }

    /**
     * Generate insights based on M&E data
     */
    public function generateInsights(array $stats): array
    {
        try {
            $prompt = $this->buildAnalysisPrompt($stats);
            $response = $this->callAPI($prompt);
            
            // Log the response for debugging
            \Log::info('HF API Response received', ['response_length' => strlen($response)]);
            
            // If response is empty or only contains the prompt, use defaults
            if (empty(trim($response)) || !preg_match('/SUMMARY:|HIGHLIGHTS:|RECOMMENDATIONS:|TRENDS:/', $response)) {
                \Log::warning('AI response does not contain expected sections, using defaults');
                return $this->getDefaultInsights($stats);
            }
            
            $parsed = $this->parseInsights($response);
            
            // If parsing resulted in empty insights, use defaults
            if (empty($parsed['summary']) && empty($parsed['highlights'])) {
                \Log::warning('Parsed insights are empty, using defaults');
                return $this->getDefaultInsights($stats);
            }
            
            return $parsed;
        } catch (\Exception $e) {
            \Log::error('Hugging Face API Error: ' . $e->getMessage());
            return $this->getDefaultInsights($stats);
        }
    }

    /**
     * Call Hugging Face Inference API
     */
    private function callAPI(string $prompt): string
    {
        \Log::info('HF API: Starting API call', [
            'model' => $this->model,
            'prompt_length' => strlen($prompt),
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("{$this->baseUrl}/{$this->model}", [
                'inputs' => $prompt,
                'parameters' => [
                    'max_new_tokens' => 500,
                    'temperature' => 0.7,
                ],
            ]);

            \Log::info('HF API: Response received', [
                'status' => $response->status(),
                'response_length' => strlen($response->body()),
            ]);

            if ($response->failed()) {
                \Log::error('HF API: Failed response', [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500),
                ]);
                throw new \Exception('Hugging Face API failed: ' . substr($response->body(), 0, 200));
            }

            $data = $response->json();
            \Log::info('HF API: JSON parsed successfully', [
                'data_keys' => array_keys($data[0] ?? []),
                'has_generated_text' => isset($data[0]['generated_text']),
            ]);

            return $data[0]['generated_text'] ?? '';
        } catch (\Exception $e) {
            \Log::error('HF API: Exception occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Build analysis prompt from stats
     */
    private function buildAnalysisPrompt(array $stats): string
    {
        $data = json_encode($stats, JSON_PRETTY_PRINT);
        $totalResponses = $stats['total_responses'] ?? 0;
        $communitySatisfaction = $stats['services']['overall_satisfaction'] ?? 0;
        $trainingInterest = $stats['opportunities']['interested_in_training'] ?? 0;
        $continuingStudiesInterest = $stats['opportunities']['interested_continuing_studies'] ?? 0;
        $communityName = $stats['community_name'] ?? 'Community';
        $avgAge = $stats['demographics']['avg_age'] ?? 'N/A';
        $livelihood = $stats['demographics']['livelihood'] ?? 'N/A';
        
        return <<<PROMPT
You are an expert M&E (Monitoring & Evaluation) analyst specializing in community assessments. Analyze the following community assessment data and provide detailed, data-driven insights about the community's needs, challenges, and opportunities.

COMMUNITY: {$communityName}
KEY METRICS:
- Total Assessment Responses: {$totalResponses}
- Overall Service Satisfaction: {$communitySatisfaction}%
- Interest in Training Programs: {$trainingInterest}%
- Interest in Continuing Studies: {$continuingStudiesInterest}%
- Demographics: Average age {$avgAge}, Primary livelihood: {$livelihood}

Full ASSESSMENT DATA:
$data

Provide a detailed analysis with SPECIFIC NUMBERS and ACTUAL DATA from the community. Structure your response EXACTLY as follows:

SUMMARY: Write 3-4 sentences that analyze the community's current state, needs, and readiness for development. Include actual percentages and numbers from the assessment data.
HIGHLIGHTS:
- [Include specific demographic or assessment findings with numbers]
- [Reference actual top problems or challenges identified]
- [Mention concrete opportunities based on the data]
RECOMMENDATIONS:
- [Action-oriented recommendations based on identified problems]
- [Programs or initiatives tailored to the training interests shown]
- [Focus on leveraging identified opportunities]
TRENDS:
- [Mention patterns in service satisfaction and problems]
- [Discuss community readiness for development initiatives]

Must include: actual numbers, percentages, specific problems identified, and data references in every section. Use the actual top problems and opportunities from the data.
PROMPT;
    }

    /**
     * Parse API response into structured insights
     */
    private function parseInsights(string $response): array
    {
        // Remove the prompt from the response if it's included (API sometimes echoes it back)
        $response = str_replace('You are an expert M&E', '', $response);
        $response = preg_replace('/^.*?SUMMARY:/s', 'SUMMARY:', $response);
        
        $insights = [
            'summary' => '',
            'highlights' => [],
            'recommendations' => [],
            'trends' => [],
        ];

        // Extract sections from response
        if (preg_match('/SUMMARY:\s*(.+?)(?=HIGHLIGHTS:|$)/s', $response, $matches)) {
            $insights['summary'] = trim($matches[1]);
        }

        if (preg_match('/HIGHLIGHTS:\s*((?:- .+\n?)+)/s', $response, $matches)) {
            $highlights = $matches[1];
            $insights['highlights'] = array_filter(array_map(function($line) {
                return trim(str_replace('- ', '', $line));
            }, explode("\n", $highlights)));
        }

        if (preg_match('/RECOMMENDATIONS:\s*((?:- .+\n?)+)/s', $response, $matches)) {
            $recommendations = $matches[1];
            $insights['recommendations'] = array_filter(array_map(function($line) {
                return trim(str_replace('- ', '', $line));
            }, explode("\n", $recommendations)));
        }

        if (preg_match('/TRENDS:\s*((?:- .+\n?)+)/s', $response, $matches)) {
            $trends = $matches[1];
            $insights['trends'] = array_filter(array_map(function($line) {
                return trim(str_replace('- ', '', $line));
            }, explode("\n", $trends)));
        }

        return $insights;
    }

    /**
     * Generate insights from community assessment data
     */
    private function getAssessmentInsights(array $stats): array
    {
        $communityName = $stats['community_name'] ?? 'Community';
        $totalResponses = $stats['total_responses'] ?? 0;
        $satisfaction = $stats['services']['overall_satisfaction'] ?? 0;
        $trainingInterest = $stats['opportunities']['interested_in_training'] ?? 0;
        $continuingStudies = $stats['opportunities']['interested_continuing_studies'] ?? 0;
        $avgAge = $stats['demographics']['avg_age'] ?? 0;
        $mostCommonLivelihood = $stats['demographics']['livelihood'] ?? 'Not specified';
        
        // Get top problems
        $topFamilyProblems = is_array($stats['problems']['family'] ?? []) ? array_slice($stats['problems']['family'], 0, 2) : [];
        $topHealthProblems = is_array($stats['problems']['health'] ?? []) ? array_slice($stats['problems']['health'], 0, 2) : [];
        
        $summary = "{$communityName} community assessment is based on {$totalResponses} responses. " .
            "Residents show " . ($trainingInterest > 50 ? "strong" : "moderate") . " interest in training programs ({$trainingInterest}%), " .
            "with overall service satisfaction at {$satisfaction}%. " .
            "The community is primarily engaged in {$mostCommonLivelihood} with an average age of " . round($avgAge) . " years.";
        
        $highlights = [
            "{$trainingInterest}% of community members are interested in training programs, indicating high potential for skill development initiatives",
            "Service satisfaction level at {$satisfaction}% suggests " . ($satisfaction > 60 ? "adequate" : "areas for improvement in") . " public service delivery",
            "Primary livelihood activity: {$mostCommonLivelihood}, relevant for designing economic development programs",
        ];
        
        if (!empty($topFamilyProblems)) {
            $highlights[] = "Top family-related challenges: " . implode(", ", $topFamilyProblems);
        }
        
        if (!empty($topHealthProblems)) {
            $highlights[] = "Health concerns identified: " . implode(", ", $topHealthProblems);
        }
        
        $recommendations = [];
        if ($trainingInterest > 60) {
            $recommendations[] = "Develop and deliver targeted training programs in skills relevant to {$mostCommonLivelihood} sector to leverage high community interest";
        }
        
        if ($satisfaction < 70) {
            $recommendations[] = "Conduct stakeholder consultations to identify service delivery gaps and implement targeted improvements";
        }
        
        if ($continuingStudies > 50) {
            $recommendations[] = "Establish partnerships with educational institutions to support {$continuingStudies}% of residents interested in furthering their studies";
        }
        
        $recommendations[] = "Create community-based initiatives addressing identified problems while leveraging livelihood activities";
        $recommendations[] = "Monitor and evaluate program effectiveness through regular assessment cycles";
        
        $trends = [
            "Community readiness: High engagement with " . $totalResponses . " assessment responses indicates willingness to participate in development initiatives",
            "Economic focus: Strong emphasis on {$mostCommonLivelihood} sector suggests concentration of development efforts needed",
            "Learning demand: {$trainingInterest}% training interest and {$continuingStudies}% educational interest show strong human capital development potential",
        ];
        
        return [
            'summary' => $summary,
            'highlights' => $highlights,
            'recommendations' => $recommendations,
            'trends' => $trends,
        ];
    }

    /**
     * Get default insights if API fails
     */
    private function getDefaultInsights(array $stats): array
    {
        // Check if this is assessment data (not program data)
        if (isset($stats['community_name']) && isset($stats['demographics'])) {
            return $this->getAssessmentInsights($stats);
        }
        
        $totalPrograms = $stats['total_programs'] ?? 0;
        $activePrograms = $stats['total_active_programs'] ?? 0;
        $planningPrograms = $stats['planning_programs'] ?? 0;
        $completedPrograms = $stats['completed_programs'] ?? 0;
        $programsThisMonth = $stats['programs_this_month'] ?? 0;
        $totalCommunities = $stats['total_communities'] ?? 0;
        $growth = $stats['growth_percent'] ?? 0;
        
        $activePercentage = $totalPrograms > 0 ? round(($activePrograms / $totalPrograms) * 100, 1) : 0;
        $completedPercentage = $totalPrograms > 0 ? round(($completedPrograms / $totalPrograms) * 100, 1) : 0;
        $planningPercentage = $totalPrograms > 0 ? round(($planningPrograms / $totalPrograms) * 100, 1) : 0;
        
        $topCommunity = !empty($stats['top_communities']) ? $stats['top_communities'][0]['name'] : 'key communities';
        
        // Build summary
        $summaryParts = [];
        if ($programsThisMonth > 0) {
            $summaryParts[] = "This month shows {$programsThisMonth} new program" . ($programsThisMonth > 1 ? 's' : '') . " created with a {$growth}% growth rate";
        } else {
            $summaryParts[] = "No new programs were created this month";
        }
        $summaryParts[] = "The organization manages {$totalPrograms} total program" . ($totalPrograms > 1 ? 's' : '') . " ({$activePercentage}% active, {$completedPercentage}% completed) across {$totalCommunities} communit" . ($totalCommunities > 1 ? 'ies' : 'y') . ", with {$topCommunity} leading in engagement";
        
        $summary = implode('. ', $summaryParts) . '.';
        
        // Build highlights
        $highlights = [];
        if ($activePrograms > 0) {
            $highlights[] = "{$activePrograms} program" . ($activePrograms > 1 ? 's' : '') . " ({$activePercentage}%) are currently active, ensuring consistent community engagement";
        }
        if ($completedPrograms > 0) {
            $highlights[] = "{$completedPrograms} program" . ($completedPrograms > 1 ? 's' : '') . " ({$completedPercentage}%) successfully completed, demonstrating strong implementation capacity";
        }
        if ($programsThisMonth > 0) {
            $highlights[] = "{$programsThisMonth} new program" . ($programsThisMonth > 1 ? 's' : '') . " launched this month with {$growth}% growth rate compared to last month";
        } else {
            $highlights[] = "Current portfolio consists of {$totalPrograms} program" . ($totalPrograms > 1 ? 's' : '') . " at various stages of implementation";
        }
        
        // Build recommendations (only include meaningful ones)
        $recommendations = [];
        if ($planningPrograms > 0) {
            $recommendations[] = "Accelerate {$planningPrograms} program" . ($planningPrograms > 1 ? 's' : '') . " in planning phase ({$planningPercentage}%) to maintain momentum and reach targets";
        } else {
            $recommendations[] = "Continue monitoring active programs to ensure successful implementation and timely completion";
        }
        
        if ($completedPrograms > 0) {
            $recommendations[] = "Document and replicate success factors from {$completedPrograms} completed program" . ($completedPrograms > 1 ? 's' : '') . " to improve future outcomes";
        } else {
            $recommendations[] = "Focus on completing current programs to build a track record of successful initiatives";
        }
        
        $recommendations[] = "Scale engagement with remaining {$totalCommunities} communit" . ($totalCommunities > 1 ? 'ies' : 'y') . " to maximize program reach and impact";
        
        // Build trends (only include meaningful ones)
        $trends = [];
        if ($programsThisMonth > 0 || $growth != 0) {
            $trendText = $growth > 0 ? "positive growth trajectory" : ($growth < 0 ? "decline" : "sustained programming");
            $growthText = $growth > 0 ? "{$growth}% increase" : ($growth < 0 ? abs($growth) . "% decrease" : "no change");
            $trends[] = "Organization showing {$trendText}: {$growthText} in program creation compared to previous month";
        }
        
        if ($totalPrograms > 0) {
            $trends[] = "Portfolio balance: {$activePercentage}% active" . ($planningPercentage > 0 ? ", {$planningPercentage}% planning" : "") . ", {$completedPercentage}% completed programs";
        }
        
        $trends[] = "Community engagement across {$totalCommunities} location" . ($totalCommunities > 1 ? 's' : '') . " with {$topCommunity} showing strong participation";
        
        return [
            'summary' => $summary,
            'highlights' => $highlights,
            'recommendations' => $recommendations,
            'trends' => $trends,
        ];
    }
}
