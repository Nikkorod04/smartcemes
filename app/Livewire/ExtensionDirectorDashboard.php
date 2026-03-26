<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Services\HuggingFaceAIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;

class ExtensionDirectorDashboard extends Component
{
    public $totalPrograms = 0;
    public $activePrograms = 0;
    public $completedPrograms = 0;
    public $planningPrograms = 0;
    public $totalCommunities = 0;
    public $programsByStatus = [];
    public $programsThisMonth = 0;
    public $programsGrowthPercent = 0;
    public $recentPrograms = [];
    public $communityParticipation = [];
    public $programStatusData = [];
    public $monthlyTrendData = [];
    public $thismMonthSummary = '';
    public $aiInsights = [];

    public function mount()
    {
        // Allow access for admin and secretary roles
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'secretary'])) {
            throw new AuthorizationException('Unauthorized access to dashboard.');
        }

        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total Programs
        $this->totalPrograms = ExtensionProgram::count();
        $this->activePrograms = ExtensionProgram::where('status', 'active')->count();
        $this->completedPrograms = ExtensionProgram::where('status', 'completed')->count();
        $this->planningPrograms = ExtensionProgram::where('status', 'planning')->count();

        // Total Communities
        $this->totalCommunities = Community::where('status', 'active')->count();

        // Programs This Month
        $this->programsThisMonth = ExtensionProgram::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Growth Percent
        $lastMonthPrograms = ExtensionProgram::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $this->programsGrowthPercent = $lastMonthPrograms > 0 
            ? round((($this->programsThisMonth - $lastMonthPrograms) / $lastMonthPrograms) * 100, 1)
            : ($this->programsThisMonth > 0 ? 100 : 0);

        // Recent Programs
        $this->recentPrograms = ExtensionProgram::latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'status' => $p->status,
                'created_at' => $p->created_at->format('M d, Y'),
                'communities_count' => $this->getCommunitiesCount($p->related_communities),
            ])
            ->toArray();

        // Programs by Status for Chart
        $this->programsByStatus = [
            'active' => $this->activePrograms,
            'planning' => $this->planningPrograms,
            'completed' => $this->completedPrograms,
            'inactive' => ExtensionProgram::where('status', 'inactive')->count(),
        ];

        // Community Participation
        $this->communityParticipation = $this->getCommunityParticipation();

        // Program Status Data for Chart.js
        $this->programStatusData = [
            'labels' => ['Active', 'Planning', 'Completed', 'Inactive'],
            'datasets' => [
                [
                    'label' => 'Programs by Status',
                    'data' => [
                        $this->activePrograms,
                        $this->planningPrograms,
                        $this->completedPrograms,
                        ExtensionProgram::where('status', 'inactive')->count(),
                    ],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(107, 114, 128)',
                    ],
                    'borderWidth' => 2,
                ]
            ]
        ];

        // Monthly Trend Data
        $this->monthlyTrendData = $this->getMonthlyTrendData();

        // Generate AI Summary
        $this->thismMonthSummary = $this->generateMonthSummary();
    }

    private function getCommunityParticipation()
    {
        $programs = ExtensionProgram::all();
        $communityParticipation = [];

        foreach ($programs as $program) {
            // Handle related_communities - ensure it's an array
            $communities = $program->related_communities;
            if (is_string($communities)) {
                $communities = json_decode($communities, true) ?? [];
            }
            $communities = is_array($communities) ? $communities : [];

            if (!empty($communities)) {
                foreach ($communities as $communityId) {
                    if (!isset($communityParticipation[$communityId])) {
                        $communityParticipation[$communityId] = 0;
                    }
                    $communityParticipation[$communityId]++;
                }
            }
        }

        // Sort by count descending and take top 5
        arsort($communityParticipation);
        $topCommunities = array_slice($communityParticipation, 0, 5, true);

        $result = [];
        foreach ($topCommunities as $communityId => $count) {
            $community = Community::find($communityId);
            if ($community) {
                $result[] = [
                    'name' => $community->name,
                    'programs' => $count,
                ];
            }
        }

        return $result;
    }

    private function getCommunitiesCount($communities): int
    {
        // Handle related_communities - ensure it's an array
        if (is_string($communities)) {
            $communities = json_decode($communities, true) ?? [];
        }
        $communities = is_array($communities) ? $communities : [];
        return count($communities);
    }

    private function getMonthlyTrendData()
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $count = ExtensionProgram::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $data[] = $count;
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Programs Created',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                ]
            ]
        ];
    }

    public function refresh()
    {
        $this->loadDashboardData();
    }

    private function generateMonthSummary(): string
    {
        try {
            $stats = [
                'programs_this_month' => $this->programsThisMonth,
                'total_active_programs' => $this->activePrograms,
                'total_programs' => $this->totalPrograms,
                'completed_programs' => $this->completedPrograms,
                'planning_programs' => $this->planningPrograms,
                'total_communities' => $this->totalCommunities,
                'growth_percent' => $this->programsGrowthPercent,
                'top_communities' => array_slice($this->communityParticipation, 0, 3),
            ];

            $aiService = new HuggingFaceAIService();
            $insights = $aiService->generateInsights($stats);

            // Store full insights for use in view
            $this->aiInsights = $insights;

            // Return formatted summary as paragraph
            return $insights['summary'] ?? $this->getDefaultMonthSummary($stats);
        } catch (\Exception $e) {
            // Fallback to default summary if AI fails
            $defaultSummary = $this->getDefaultMonthSummary([
                'programs_this_month' => $this->programsThisMonth,
                'total_active_programs' => $this->activePrograms,
                'total_programs' => $this->totalPrograms,
                'completed_programs' => $this->completedPrograms,
                'planning_programs' => $this->planningPrograms,
                'total_communities' => $this->totalCommunities,
                'growth_percent' => $this->programsGrowthPercent,
                'top_communities' => array_slice($this->communityParticipation, 0, 3),
            ]);
            
            // Calculate percentages first
            $activePercentage = $this->totalPrograms > 0 ? round(($this->activePrograms / $this->totalPrograms) * 100, 1) : 0;
            $completedPercentage = $this->totalPrograms > 0 ? round(($this->completedPrograms / $this->totalPrograms) * 100, 1) : 0;
            $planningPercentage = $this->totalPrograms > 0 ? round(($this->planningPrograms / $this->totalPrograms) * 100, 1) : 0;
            
            // Set default insights with actual numbers
            $this->aiInsights = [
                'summary' => $defaultSummary,
                'highlights' => [
                    "{$this->activePrograms} programs ({$activePercentage}%) are currently active across all communities",
                    "{$this->completedPrograms} programs ({$completedPercentage}%) successfully completed with strong results",
                    "{$this->programsThisMonth} new programs launched this month showing {$this->programsGrowthPercent}% growth",
                ],
                'recommendations' => [
                    "Accelerate {$this->planningPrograms} programs in planning phase to maintain implementation momentum",
                    "Scale successful programs to reach all {$this->totalCommunities} active communities effectively",
                    "Document and share best practices from {$this->completedPrograms} completed programs"
                ],
                'trends' => [
                    'Growth trajectory: ' . $this->programsGrowthPercent . '% increase compared to last month',
                    'Portfolio balance: ' . $activePercentage . '% active, ' . $planningPercentage . '% planning, ' . $completedPercentage . '% completed'
                ]
            ];
            
            return $defaultSummary;
        }
    }

    private function getDefaultMonthSummary(array $stats): string
    {
        $growth = $stats['growth_percent'] ?? 0;
        $growthText = $growth >= 0 ? "increased by {$growth}%" : "decreased by " . abs($growth) . "%";
        $topCommunity = !empty($stats['top_communities']) ? $stats['top_communities'][0]['name'] : 'various communities';

        return "This month shows {$stats['programs_this_month']} new programs created, representing a {$growthText} compared to last month. " .
               "The organization now manages {$stats['total_programs']} total programs with {$stats['total_active_programs']} currently active " .
               "and {$stats['completed_programs']} successfully completed. " .
               "These programs are being implemented across {$stats['total_communities']} active communities, with {$topCommunity} being the most active hub. " .
               "The momentum continues with strong engagement in the planning and implementation phases.";
    }

    public function render()
    {
        return view('livewire.extension-director-dashboard');
    }
}
