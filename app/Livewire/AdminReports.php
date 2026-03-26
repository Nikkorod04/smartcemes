<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Services\HuggingFaceAIService;

class AdminReports extends Component
{
    public string $activeTab = 'ai-insights';
    public string $timeRange = '30days';
    public bool $loadingInsights = false;
    public array $aiInsights = [];

    public function extensionProgramsStats()
    {
        return [
            'total' => ExtensionProgram::count(),
            'active' => ExtensionProgram::where('status', 'active')->count(),
            'completed' => ExtensionProgram::where('status', 'completed')->count(),
            'planned' => ExtensionProgram::where('status', 'planned')->count(),
        ];
    }

    public function getProgramsThisMonth()
    {
        return ExtensionProgram::where('created_at', '>=', now()->subMonth())->count();
    }

    public function getLastMonthPrograms()
    {
        return ExtensionProgram::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
    }

    public function calculateGrowthRate()
    {
        $thisMonth = $this->getProgramsThisMonth();
        $lastMonth = $this->getLastMonthPrograms();
        
        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    public function getTopCommunities()
    {
        $programs = ExtensionProgram::all();
        $communityCount = [];
        
        foreach ($programs as $program) {
            if (is_array($program->related_communities)) {
                foreach ($program->related_communities as $communityId) {
                    $communityCount[$communityId] = ($communityCount[$communityId] ?? 0) + 1;
                }
            }
        }
        
        arsort($communityCount);
        $topCommunityIds = array_slice(array_keys($communityCount), 0, 3);
        
        return Community::whereIn('id', $topCommunityIds)->get(['id', 'name'])->toArray();
    }

    #[Computed]
    public function communitiesStats()
    {
        $communities = Community::query();
        
        return [
            'total' => $communities->count(),
            'active' => $communities->where('status', 'active')->count(),
            'inactive' => $communities->where('status', 'inactive')->count(),
        ];
    }

    #[Computed]
    public function programsPerformance()
    {
        return ExtensionProgram::select('status')
            ->groupBy('status')
            ->selectRaw('count(*) as count')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    public function getAllPrograms()
    {
        return ExtensionProgram::all();
    }

    public function getProgramsByStatus()
    {
        return ExtensionProgram::all()
            ->groupBy('status')
            ->map(function($programs) {
                return $programs->values();
            });
    }

    public function generateAIInsights()
    {
        $this->loadingInsights = true;

        try {
            // Gather data for AI analysis
            $programStats = $this->extensionProgramsStats();
            $communityStats = $this->communitiesStats();
            
            $stats = [
                'total_programs' => $programStats['total'],
                'total_active_programs' => $programStats['active'],
                'planning_programs' => $programStats['planned'],
                'completed_programs' => $programStats['completed'],
                'programs_this_month' => $this->getProgramsThisMonth(),
                'growth_percent' => $this->calculateGrowthRate(),
                'total_communities' => $communityStats['total'],
                'top_communities' => $this->getTopCommunities(),
            ];

            // Call AI service to generate insights
            $insights = $this->callAIService($stats);
            
            $this->aiInsights = $insights;
        } catch (\Exception $e) {
            $this->aiInsights = ['error' => 'Failed to generate insights: ' . $e->getMessage()];
        }

        $this->loadingInsights = false;
    }

    private function callAIService(array $data): array
    {
        // Initialize Hugging Face AI service and generate insights
        $aiService = new HuggingFaceAIService();
        return $aiService->generateInsights($data);
    }

    public function render()
    {
        return view('livewire.admin-reports', [
            'programsStats' => $this->extensionProgramsStats(),
            'communitiesStats' => $this->communitiesStats(),
            'allPrograms' => $this->getAllPrograms(),
            'programsByStatus' => $this->getProgramsByStatus(),
        ]);
    }
}
