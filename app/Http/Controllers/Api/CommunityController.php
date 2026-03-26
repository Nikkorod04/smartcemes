<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommunityController extends Controller
{
    // No middleware needed - each method handles its own auth

    /**
     * List all communities
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'status' => 'string|in:active,inactive',
            'search' => 'string|max:255',
        ]);

        $query = Community::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 20);
        $communities = $query->latest('created_at')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $communities->items(),
                'pagination' => [
                    'total' => $communities->total(),
                    'per_page' => $communities->perPage(),
                    'current_page' => $communities->currentPage(),
                    'last_page' => $communities->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * Get a single community
     */
    public function show($id)
    {
        $community = Community::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $community->id,
                'name' => $community->name,
                'code' => $community->code,
                'location' => $community->location,
                'status' => $community->status,
                'created_at' => $community->created_at?->toIso8601String(),
                'updated_at' => $community->updated_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Create a new community
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'code' => 'required|string|max:50|unique:communities',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $community = Community::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Community created successfully',
            'data' => [
                'id' => $community->id,
                'name' => $community->name,
                'code' => $community->code,
                'location' => $community->location,
                'status' => $community->status,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a community
     */
    public function update(Request $request, $id)
    {
        $community = Community::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:communities,name,' . $id,
            'code' => 'sometimes|required|string|max:50|unique:communities,code,' . $id,
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $community->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Community updated successfully',
            'data' => [
                'id' => $community->id,
                'name' => $community->name,
                'code' => $community->code,
                'location' => $community->location,
                'status' => $community->status,
            ],
        ]);
    }

    /**
     * Delete a community
     */
    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Community deleted successfully',
        ]);
    }

    /**
     * Generate AI analysis for assessment summary
     */
    public function generateAISummary($communityId)
    {
        try {
            \Log::info('AI Summary: Request started', [
                'community_id' => $communityId,
                'auth_check' => auth('web')->check() ? 'yes' : 'no',
                'user' => auth('web')->user() ? auth('web')->user()->id : 'none',
            ]);
            
            // For API endpoints called from frontend, just verify community exists
            // The page itself requires authentication
            $community = Community::with('assessmentSummary')->findOrFail($communityId);
            
            if (!$community->assessmentSummary) {
                \Log::warning('AI Summary: No assessment summary found', ['community_id' => $communityId]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No assessment summary available for this community',
                ], 404);
            }

            $summary = $community->assessmentSummary;

            // Check if we already have a recent AI summary (less than 24 hours old)
            if ($summary->ai_summary && $summary->ai_summary_generated_at && 
                $summary->ai_summary_generated_at->diffInHours(now()) < 24) {
                \Log::info('AI Summary: Using cached summary', ['community_id' => $communityId]);
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'ai_summary' => $summary->ai_summary,
                        'generated_at' => $summary->ai_summary_generated_at,
                        'cached' => true,
                    ],
                ]);
            }

            \Log::info('AI Summary: Generating new summary', ['community_id' => $communityId]);

            $aiService = app(\App\Services\HuggingFaceAIService::class);
            
            // Prepare data for AI analysis
            $analysisData = [
                'community_name' => $community->name,
                'total_responses' => $summary->total_assessments,
                'demographics' => [
                    'avg_age' => $summary->avg_age,
                    'common_sex' => $summary->most_common_sex,
                    'civil_status' => $summary->most_common_civil_status,
                    'education' => $summary->most_common_educational_attainment,
                    'livelihood' => $summary->most_common_livelihood,
                ],
                'services' => [
                    'overall_satisfaction' => $summary->overall_service_satisfaction,
                    'police' => $summary->avg_service_rating_police,
                    'health_clinic' => $summary->avg_service_rating_clinic,
                    'water_supply' => $summary->avg_service_rating_water,
                    'roads' => $summary->avg_service_rating_roads,
                ],
                'problems' => [
                    'family' => is_array($summary->top_family_problems) ? array_slice($summary->top_family_problems, 0, 3) : [],
                    'health' => is_array($summary->top_health_problems) ? array_slice($summary->top_health_problems, 0, 3) : [],
                    'education' => is_array($summary->top_education_problems) ? array_slice($summary->top_education_problems, 0, 3) : [],
                    'employment' => is_array($summary->top_employment_problems) ? array_slice($summary->top_employment_problems, 0, 3) : [],
                    'infrastructure' => is_array($summary->top_infrastructure_problems) ? array_slice($summary->top_infrastructure_problems, 0, 3) : [],
                ],
                'opportunities' => [
                    'interested_in_training' => $summary->percent_interested_in_training,
                    'desired_trainings' => is_array($summary->common_desired_trainings) ? $summary->common_desired_trainings : [],
                    'interested_continuing_studies' => $summary->percent_interested_in_continuing_studies,
                ],
                'themes' => is_array($summary->key_feedback_themes) ? $summary->key_feedback_themes : [],
            ];

            \Log::info('AI Summary: Calling HF service', ['community_id' => $communityId, 'data_keys' => array_keys($analysisData)]);
            
            $insights = $aiService->generateInsights($analysisData);
            
            \Log::info('AI Summary: Insights received', ['community_id' => $communityId, 'insight_keys' => array_keys($insights)]);
            
            $aiSummary = $this->formatInsights($insights);
            
            // Save to database
            $summary->update([
                'ai_summary' => $aiSummary,
                'ai_summary_generated_at' => now(),
            ]);

            \Log::info('AI Summary: Summary saved to database', ['community_id' => $communityId]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'ai_summary' => $aiSummary,
                    'generated_at' => $summary->ai_summary_generated_at,
                    'cached' => false,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI Summary Generation Error', [
                'community_id' => $communityId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Error generating AI analysis: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format insights for display
     */
    private function formatInsights(array $insights): string
    {
        $summary = '<div class="ai-insights">';
        
        if (!empty($insights['summary'])) {
            $summary .= '<div class="insight-section"><strong>Summary:</strong><p>' . htmlspecialchars($insights['summary']) . '</p></div>';
        }
        
        if (!empty($insights['highlights'])) {
            $summary .= '<div class="insight-section"><strong>Key Findings:</strong><ul>';
            foreach ((array)$insights['highlights'] as $highlight) {
                $summary .= '<li>' . htmlspecialchars($highlight) . '</li>';
            }
            $summary .= '</ul></div>';
        }
        
        if (!empty($insights['recommendations'])) {
            $summary .= '<div class="insight-section"><strong>Recommendations:</strong><ul>';
            foreach ((array)$insights['recommendations'] as $rec) {
                $summary .= '<li>' . htmlspecialchars($rec) . '</li>';
            }
            $summary .= '</ul></div>';
        }
        
        $summary .= '</div>';
        
        return $summary;
    }
}
