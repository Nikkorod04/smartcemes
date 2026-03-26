# Community Needs Assessment → Automated Baseline Generation
## Feature Planning & Implementation Guide

**Objective:** Secretary enters community needs assessment data (F-CES-001, minimum 20 responses), system calculates averages across sections, and automatically generates baseline record.

---

## PHASE 1: UNDERSTANDING THE WORKFLOW

### Current State
- ✅ F-CES-001 form exists with 9 sections (Identifying Info, Family, Economic, Education, Health, Housing, Recreation, Problems, Service Ratings)
- ✅ Program Baseline table exists with manual entry
- ❌ No automated calculation from needs assessment data
- ❌ No needs assessment response storage

### Proposed Workflow

```
Secretary starts new program
    ↓
Creates logic model with baseline targets
    ↓
Creates baseline record (manual entry - CURRENT STATE)
    ↓
[NEW FLOW STARTS HERE]
    ↓
Secretary enters Community Needs Assessment form (F-CES-001)
Secretary enters it 20+ times per community (from actual community data)
    ↓
System accumulates responses in CommunityNeedsAssessment table
    ↓
[AUTO-TRIGGER at 20 responses]
    ↓
System calculates AVERAGES across all responses:
  - Average literacy level
  - Average income
  - Average age
  - Average family size
  - Common skills needed
  - Common health issues
  - Common problems
  - Average service satisfaction
    ↓
System AUTO-GENERATES baseline record with calculated values
    ↓
Secretary reviews auto-generated baseline
Secretary can override if needed
Secretary approves/finalizes baseline
```

---

## PHASE 2: DATABASE DESIGN

### 1. NEW TABLE: `community_needs_assessments`
Stores individual respondent data from F-CES-001 form

```sql
CREATE TABLE community_needs_assessments (
    id BIGINT PRIMARY KEY,
    program_id BIGINT NOT NULL (FK),
    community_id BIGINT NOT NULL (FK),
    
    -- Response Status
    response_number INT,           -- 1st, 2nd, 20th response
    status ENUM('draft', 'submitted', 'verified') DEFAULT 'draft',
    
    -- SECTION I: Identifying Information
    respondent_name VARCHAR(255),
    age INT,                       (*) - Will be averaged
    civil_status VARCHAR(50),
    sex ENUM('Male', 'Female', 'Other'),
    religion VARCHAR(100),
    educational_attainment VARCHAR(100),
    
    -- SECTION II: Family Composition (stored as JSON)
    family_composition JSON,       (*) - Calculate avg family size
    
    -- SECTION III: Economic Aspect
    livelihood_options JSON,       (*) - Most common options
    interested_in_training BOOLEAN,
    desired_training JSON,
    
    -- SECTION IV: Educational Aspect
    barangay_facilities JSON,
    household_member_studying BOOLEAN,
    interested_in_continuing_studies BOOLEAN,
    areas_of_interest JSON,
    preferred_time VARCHAR(50),
    preferred_days JSON,
    
    -- SECTION V: Health, Sanitation, Environmental
    common_illnesses JSON,         (*) - Most common illnesses
    action_when_sick JSON,
    barangay_medical_supplies JSON,
    has_barangay_health_programs BOOLEAN,
    benefits_from_programs BOOLEAN,
    water_source JSON,
    garbage_disposal JSON,
    has_own_toilet BOOLEAN,
    keeps_animals BOOLEAN,
    
    -- SECTION VI: Housing and Basic Amenities
    house_type VARCHAR(100),
    tenure_status VARCHAR(100),
    has_electricity BOOLEAN,
    
    -- SECTION VII: Recreational Facilities
    barangay_recreational_facilities JSON,
    use_of_free_time JSON,
    member_of_organization BOOLEAN,
    
    -- SECTION VIII: Other Needs & Problems
    problems_family JSON,          (*) - Most common problems
    problems_health JSON,
    problems_education JSON,
    problems_employment JSON,
    problems_infrastructure JSON,
    problems_economy JSON,
    problems_security JSON,
    
    -- SECTION IX: Summary
    barangay_service_ratings JSON, (*) - Will be averaged (1-5 scale)
    general_feedback TEXT,
    available_for_training BOOLEAN,
    reason_not_available TEXT,
    
    -- Metadata
    created_by BIGINT (FK users),
    updated_by BIGINT (FK users),
    submitted_at TIMESTAMP,
    verified_at TIMESTAMP,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP (soft delete)
    
    -- Indexes
    INDEX (program_id, community_id),
    INDEX (response_number),
    INDEX (status)
);
```

### 2. NEW TABLE: `community_assessment_summaries`
Stores calculated averages (triggered at 20 responses)

```sql
CREATE TABLE community_assessment_summaries (
    id BIGINT PRIMARY KEY,
    program_id BIGINT NOT NULL (FK),
    community_id BIGINT NOT NULL (FK),
    
    -- Counting Info
    total_responses INT,
    response_threshold_met BOOLEAN,
    calculation_date TIMESTAMP,
    
    -- AVERAGED METRICS
    avg_age DECIMAL(5,2),
    avg_family_size DECIMAL(5,2),
    avg_literacy_level DECIMAL(3,2),        -- 1-5 scale
    avg_income DECIMAL(12,2),
    
    -- MODAL (Most Common)
    most_common_livelihood VARCHAR(255),
    most_common_primary_problem VARCHAR(255),
    most_common_primary_health_issue VARCHAR(255),
    
    -- Percentage Statistics (%)
    percent_interested_in_training INT,
    percent_interested_in_continuing_studies INT,
    percent_with_electricity INT,
    percent_with_own_toilet INT,
    percent_with_benefits_from_health_programs INT,
    
    -- Service Satisfaction (averaged from 1-5 scale)
    avg_law_enforcement_rating DECIMAL(3,2),
    avg_fire_protection_rating DECIMAL(3,2),
    avg_barangay_service_rating DECIMAL(3,2),
    -- ... 9 total service ratings
    
    -- Top Challenges (JSON array of top 5)
    top_family_challenges JSON,
    top_health_challenges JSON,
    top_education_challenges JSON,
    top_employment_challenges JSON,
    top_infrastructure_gaps JSON,
    top_economic_needs JSON,
    top_security_concerns JSON,
    
    -- Skills Needed (Top 5 most requested trainings)
    top_requested_skills JSON,
    
    -- Generated Baseline Record ID (if auto-created)
    generated_baseline_id BIGINT (FK program_baselines),
    baseline_auto_generated BOOLEAN DEFAULT FALSE,
    
    -- Status
    status ENUM('calculated', 'reviewed', 'baseline_created', 'archived'),
    notes TEXT,
    
    created_by BIGINT (FK users),
    reviewed_by BIGINT (FK users),
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP (soft delete)
    
    -- Indexes
    INDEX (program_id, community_id),
    INDEX (status),
    INDEX (response_threshold_met)
);
```

### 3. MODIFIED TABLE: `program_baselines`
Add reference to the assessment summary that generated it

```sql
ALTER TABLE program_baselines ADD COLUMN (
    assessment_summary_id BIGINT (FK community_assessment_summaries),
    auto_generated_from_assessment BOOLEAN DEFAULT FALSE,
    auto_generated_at TIMESTAMP
);
```

---

## PHASE 3: LARAVEL MODELS

### Model 1: `CommunityNeedsAssessment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityNeedsAssessment extends Model
{
    use SoftDeletes;

    protected $table = 'community_needs_assessments';

    protected $fillable = [
        'program_id',
        'community_id',
        'response_number',
        'status',
        'respondent_name',
        'age',
        'civil_status',
        'sex',
        'religion',
        'educational_attainment',
        'family_composition',
        'livelihood_options',
        'interested_in_training',
        'desired_training',
        'barangay_facilities',
        'household_member_studying',
        'interested_in_continuing_studies',
        'areas_of_interest',
        'preferred_time',
        'preferred_days',
        'common_illnesses',
        'action_when_sick',
        'barangay_medical_supplies',
        'has_barangay_health_programs',
        'benefits_from_programs',
        'water_source',
        'garbage_disposal',
        'has_own_toilet',
        'keeps_animals',
        'house_type',
        'tenure_status',
        'has_electricity',
        'barangay_recreational_facilities',
        'use_of_free_time',
        'member_of_organization',
        'problems_family',
        'problems_health',
        'problems_education',
        'problems_employment',
        'problems_infrastructure',
        'problems_economy',
        'problems_security',
        'barangay_service_ratings',
        'general_feedback',
        'available_for_training',
        'reason_not_available',
        'created_by',
        'updated_by',
        'submitted_at',
        'verified_at',
    ];

    protected $casts = [
        'family_composition' => 'array',
        'livelihood_options' => 'array',
        'desired_training' => 'array',
        'barangay_facilities' => 'array',
        'areas_of_interest' => 'array',
        'preferred_days' => 'array',
        'common_illnesses' => 'array',
        'action_when_sick' => 'array',
        'barangay_medical_supplies' => 'array',
        'benefits_from_programs' => 'array',
        'water_source' => 'array',
        'garbage_disposal' => 'array',
        'barangay_recreational_facilities' => 'array',
        'use_of_free_time' => 'array',
        'problems_family' => 'array',
        'problems_health' => 'array',
        'problems_education' => 'array',
        'problems_employment' => 'array',
        'problems_infrastructure' => 'array',
        'problems_economy' => 'array',
        'problems_security' => 'array',
        'barangay_service_ratings' => 'array',
        'interested_in_training' => 'boolean',
        'household_member_studying' => 'boolean',
        'interested_in_continuing_studies' => 'boolean',
        'has_barangay_health_programs' => 'boolean',
        'benefits_from_programs' => 'boolean',
        'has_own_toilet' => 'boolean',
        'keeps_animals' => 'boolean',
        'has_electricity' => 'boolean',
        'member_of_organization' => 'boolean',
        'available_for_training' => 'boolean',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope: Get responses for specific program/community pair
    public function scopeForProgramCommunity($query, $programId, $communityId)
    {
        return $query->where('program_id', $programId)
                     ->where('community_id', $communityId);
    }

    // Scope: Get submitted responses only
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    // Helper: Check if threshold (20 responses) is met
    public static function isThresholdMet($programId, $communityId)
    {
        return self::forProgramCommunity($programId, $communityId)
                   ->submitted()
                   ->count() >= 20;
    }
}
```

### Model 2: `CommunityAssessmentSummary.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityAssessmentSummary extends Model
{
    use SoftDeletes;

    protected $table = 'community_assessment_summaries';

    protected $fillable = [
        'program_id',
        'community_id',
        'total_responses',
        'response_threshold_met',
        'calculation_date',
        'avg_age',
        'avg_family_size',
        'avg_literacy_level',
        'avg_income',
        'most_common_livelihood',
        'most_common_primary_problem',
        'most_common_primary_health_issue',
        'percent_interested_in_training',
        'percent_interested_in_continuing_studies',
        'percent_with_electricity',
        'percent_with_own_toilet',
        'percent_with_benefits_from_health_programs',
        'avg_law_enforcement_rating',
        'avg_fire_protection_rating',
        'avg_barangay_service_rating',
        'top_family_challenges',
        'top_health_challenges',
        'top_education_challenges',
        'top_employment_challenges',
        'top_infrastructure_gaps',
        'top_economic_needs',
        'top_security_concerns',
        'top_requested_skills',
        'generated_baseline_id',
        'baseline_auto_generated',
        'status',
        'notes',
        'created_by',
        'reviewed_by',
    ];

    protected $casts = [
        'response_threshold_met' => 'boolean',
        'baseline_auto_generated' => 'boolean',
        'calculation_date' => 'datetime',
        'top_family_challenges' => 'array',
        'top_health_challenges' => 'array',
        'top_education_challenges' => 'array',
        'top_employment_challenges' => 'array',
        'top_infrastructure_gaps' => 'array',
        'top_economic_needs' => 'array',
        'top_security_concerns' => 'array',
        'top_requested_skills' => 'array',
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function generatedBaseline()
    {
        return $this->belongsTo(ProgramBaseline::class, 'generated_baseline_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper: Get latest summary for program/community
    public static function latestFor($programId, $communityId)
    {
        return self::where('program_id', $programId)
                   ->where('community_id', $communityId)
                   ->orderBy('calculation_date', 'desc')
                   ->first();
    }
}
```

---

## PHASE 4: CALCULATION SERVICE

### Service: `CommunityAssessmentAnalysisService.php`

```php
<?php

namespace App\Services;

use App\Models\CommunityNeedsAssessment;
use App\Models\CommunityAssessmentSummary;
use App\Models\ProgramBaseline;
use Carbon\Carbon;

class CommunityAssessmentAnalysisService
{
    /**
     * Calculate averages and generate summary when 20+ responses
     */
    public function analyzeAssessments($programId, $communityId)
    {
        // Get all submitted responses
        $assessments = CommunityNeedsAssessment::forProgramCommunity($programId, $communityId)
            ->submitted()
            ->get();

        $count = $assessments->count();
        
        if ($count < 20) {
            return [
                'success' => false,
                'message' => "Only {$count} responses. Need 20 minimum.",
                'responses_count' => $count
            ];
        }

        // PHASE 1: NUMERIC AVERAGES
        $avgAge = $assessments->avg('age');
        $avgFamilySize = $assessments->pluck('family_composition')
            ->map(fn($fc) => is_array($fc) ? count($fc) : 0)
            ->average();
        $avgLiteracyLevel = $assessments->pluck('barangay_service_ratings')
            ->map(fn($ratings) => is_array($ratings) ? array_sum($ratings) / count($ratings) : 0)
            ->average();
        $avgIncome = $assessments->avg('avg_income'); // If stored in individual assessments

        // PHASE 2: MODAL (MOST COMMON)
        $mostCommonLivelihood = $this->getMostCommonValue(
            $assessments->pluck('livelihood_options')->flatten()
        );

        // PHASE 3: TOP PROBLEMS (By frequency)
        $topFamilyProblems = $this->getTopNValues(
            $assessments->pluck('problems_family')->flatten(),
            5
        );
        $topHealthProblems = $this->getTopNValues(
            $assessments->pluck('problems_health')->flatten(),
            5
        );
        $topEducationProblems = $this->getTopNValues(
            $assessments->pluck('problems_education')->flatten(),
            5
        );

        // ... similar for other problem areas ...

        // PHASE 4: PERCENTAGES
        $percentInterestedInTraining = ($assessments->where('interested_in_training', true)->count() / $count) * 100;
        $percentWithElectricity = ($assessments->where('has_electricity', true)->count() / $count) * 100;
        $percentWithOwnToilet = ($assessments->where('has_own_toilet', true)->count() / $count) * 100;

        // PHASE 5: SERVICE RATINGS AVERAGE
        $serviceRatings = $assessments->pluck('barangay_service_ratings');
        $avgServiceRatings = $this->averageServiceRatings($serviceRatings);

        // PHASE 6: TOP REQUESTED SKILLS
        $topSkills = $this->getTopNValues(
            $assessments->pluck('desired_training')->flatten(),
            5
        );

        // CREATE SUMMARY RECORD
        $summary = CommunityAssessmentSummary::create([
            'program_id' => $programId,
            'community_id' => $communityId,
            'total_responses' => $count,
            'response_threshold_met' => true,
            'calculation_date' => Carbon::now(),
            'avg_age' => round($avgAge, 2),
            'avg_family_size' => round($avgFamilySize, 2),
            'avg_literacy_level' => round($avgLiteracyLevel, 2),
            'avg_income' => round($avgIncome, 2),
            'most_common_livelihood' => $mostCommonLivelihood,
            'percent_interested_in_training' => round($percentInterestedInTraining),
            'percent_with_electricity' => round($percentWithElectricity),
            'percent_with_own_toilet' => round($percentWithOwnToilet),
            'top_family_challenges' => $topFamilyProblems,
            'top_health_challenges' => $topHealthProblems,
            'top_education_challenges' => $topEducationProblems,
            'top_requested_skills' => $topSkills,
            'status' => 'calculated',
            'created_by' => auth()->id(),
        ]);

        // AUTO-GENERATE BASELINE
        $baseline = $this->generateBaseline($programId, $communityId, $summary);

        return [
            'success' => true,
            'message' => "Assessment calculated from {$count} responses. Baseline auto-generated.",
            'summary_id' => $summary->id,
            'baseline_id' => $baseline->id,
            'summary' => $summary,
            'baseline' => $baseline
        ];
    }

    /**
     * Auto-generate baseline record from calculated summary
     */
    private function generateBaseline($programId, $communityId, CommunityAssessmentSummary $summary)
    {
        // Build key findings from the assessment data
        $keyFindings = $this->generateKeyFindings($summary);

        $baseline = ProgramBaseline::create([
            'program_id' => $programId,
            'community_id' => $communityId,
            'baseline_assessment_date' => $summary->calculation_date,
            'target_beneficiaries_count' => null, // Manual override needed
            'target_literacy_level' => round($summary->avg_literacy_level, 2),
            'target_average_income' => round($summary->avg_income, 2),
            'target_skills' => $summary->top_requested_skills,
            'existing_capacities' => $this->describeCapacities($summary),
            'existing_challenges' => $this->describeChallenges($summary),
            'assessment_methodology' => 'Community Needs Assessment F-CES-001 (20+ respondents)',
            'key_findings' => $keyFindings,
            'status' => 'draft', // Secretary needs to review
            'assessment_summary_id' => $summary->id,
            'auto_generated_from_assessment' => true,
            'auto_generated_at' => Carbon::now(),
            'created_by' => auth()->id(),
        ]);

        // Update summary with baseline reference
        $summary->update([
            'generated_baseline_id' => $baseline->id,
            'baseline_auto_generated' => true,
            'status' => 'baseline_created'
        ]);

        return $baseline;
    }

    /**
     * Helper: Get most common value from array
     */
    private function getMostCommonValue($items)
    {
        $items = $items->filter()->values();
        if ($items->isEmpty()) return null;

        $counts = $items->countBy()->sortDesc();
        return $counts->keys()->first();
    }

    /**
     * Helper: Get top N most frequent values
     */
    private function getTopNValues($items, $n = 5)
    {
        $items = $items->filter()->values();
        if ($items->isEmpty()) return [];

        return $items->countBy()
            ->sortDesc()
            ->take($n)
            ->keys()
            ->toArray();
    }

    /**
     * Helper: Average service ratings across respondents
     */
    private function averageServiceRatings($serviceRatingsCollection)
    {
        $allRatings = [];
        
        foreach ($serviceRatingsCollection as $ratings) {
            if (is_array($ratings)) {
                $allRatings[] = $ratings;
            }
        }

        if (empty($allRatings)) return [];

        $ratingNames = [
            'law_enforcement',
            'fire_protection',
            'barangay_service',
            // ... etc
        ];

        $averages = [];
        foreach ($ratingNames as $name) {
            $values = array_column($allRatings, $name);
            $averages[$name] = count($values) > 0 ? round(array_sum($values) / count($values), 2) : null;
        }

        return $averages;
    }

    /**
     * Generate narrative: Key findings
     */
    private function generateKeyFindings(CommunityAssessmentSummary $summary)
    {
        return <<<TEXT
Community Needs Assessment Summary (Based on {$summary->total_responses} respondents)

Demographics:
- Average Age: {$summary->avg_age} years
- Average Family Size: {$summary->avg_family_size} members
- Current Avg Income: ₱{$summary->avg_income}

Skills & Training:
- {$summary->percent_interested_in_training}% interested in skills training
- Top Requested Skills: {implode(', ', $summary->top_requested_skills ?? [])}

Infrastructure:
- {$summary->percent_with_electricity}% have access to electricity
- {$summary->percent_with_own_toilet}% have own toilet facility

Top Community Challenges:
{implode("\n", array_map(fn($c) => "- {$c}", $summary->top_family_challenges ?? []))}

Health Concerns:
{implode("\n", array_map(fn($h) => "- {$h}", $summary->top_health_challenges ?? []))}

This baseline was automatically generated from the Community Needs Assessment form (F-CES-001).
Secretary review and approval required before finalization.
TEXT;
    }

    /**
     * Generate narrative: Community capacities
     */
    private function describeCapacities(CommunityAssessmentSummary $summary)
    {
        $capacities = [];
        
        if ($summary->percent_with_electricity > 50) {
            $capacities[] = "Majority of households ({$summary->percent_with_electricity}%) have access to electricity";
        }
        
        if ($summary->percent_interested_in_training > 60) {
            $capacities[] = "{$summary->percent_interested_in_training}% of community members are interested in skills development";
        }

        return implode("\n", $capacities);
    }

    /**
     * Generate narrative: Community challenges
     */
    private function describeChallenges(CommunityAssessmentSummary $summary)
    {
        $challenges = [];
        
        if (!empty($summary->top_family_challenges)) {
            $challenges[] = "Family: " . implode(", ", $summary->top_family_challenges);
        }
        
        if (!empty($summary->top_health_challenges)) {
            $challenges[] = "Health: " . implode(", ", $summary->top_health_challenges);
        }

        return implode("\n", $challenges);
    }
}
```

---

## PHASE 5: LIVEWIRE COMPONENT

### Component: `ManageCommunityNeedsAssessment.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CommunityNeedsAssessment;
use App\Models\CommunityAssessmentSummary;
use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Services\CommunityAssessmentAnalysisService;

class ManageCommunityNeedsAssessment extends Component
{
    use WithPagination;

    public $program_id;
    public $program;
    public $community_id;
    public $community;

    // UI State
    public $showForm = false;
    public $editingId = null;
    public $responseCount = 0;
    public $thresholdMet = false;
    public $summary = null;

    // Form Fields (All 9 sections)
    public $respondent_name;
    public $age;
    public $civil_status;
    public $sex;
    public $religion;
    public $educational_attainment;
    // ... all other fields from F-CES-001 ...

    public function mount($program_id, $community_id)
    {
        $this->program_id = $program_id;
        $this->community_id = $community_id;
        $this->program = ExtensionProgram::findOrFail($program_id);
        $this->community = Community::findOrFail($community_id);
        $this->loadSummary();
    }

    public function loadSummary()
    {
        $this->responseCount = CommunityNeedsAssessment::forProgramCommunity(
            $this->program_id,
            $this->community_id
        )->submitted()->count();

        $this->thresholdMet = $this->responseCount >= 20;
        $this->summary = CommunityAssessmentSummary::latestFor(
            $this->program_id,
            $this->community_id
        );
    }

    public function saveAssessment()
    {
        // Validation rules...
        $this->validate([
            'respondent_name' => 'required|string',
            'age' => 'required|numeric|min:5|max:100',
            // ... all fields ...
        ]);

        $data = [
            'program_id' => $this->program_id,
            'community_id' => $this->community_id,
            'respondent_name' => $this->respondent_name,
            'age' => $this->age,
            // ... all fields ...
            'status' => 'submitted',
            'created_by' => auth()->id(),
            'submitted_at' => now(),
        ];

        if ($this->editingId) {
            CommunityNeedsAssessment::findOrFail($this->editingId)->update($data);
        } else {
            CommunityNeedsAssessment::create($data);
        }

        $this->showForm = false;
        $this->resetFormFields();
        $this->loadSummary();

        // Check if threshold reached and auto-calculate
        if ($this->thresholdMet && !$this->summary) {
            $this->triggerAnalysis();
        }

        $this->dispatch('notify', message: 'Assessment saved successfully');
    }

    public function triggerAnalysis()
    {
        $service = new CommunityAssessmentAnalysisService();
        $result = $service->analyzeAssessments($this->program_id, $this->community_id);

        if ($result['success']) {
            $this->loadSummary();
            $this->dispatch('notify', message: $result['message']);
            $this->dispatch('baseline-generated', baseline_id: $result['baseline_id']);
        }
    }

    public function approveSummary()
    {
        if ($this->summary) {
            $this->summary->update(['status' => 'reviewed']);
            $this->dispatch('notify', message: 'Assessment summary approved');
        }
    }

    public function render()
    {
        $assessments = CommunityNeedsAssessment::forProgramCommunity(
            $this->program_id,
            $this->community_id
        )->paginate(10);

        return view('livewire.manage-community-needs-assessment', [
            'assessments' => $assessments,
            'responseCount' => $this->responseCount,
            'thresholdMet' => $this->thresholdMet,
            'summary' => $this->summary,
        ]);
    }

    private function resetFormFields()
    {
        $this->respondent_name = '';
        $this->age = '';
        $this->civil_status = '';
        // ... reset all fields ...
    }
}
```

---

## PHASE 6: BLADE VIEW

### View: `resources/views/livewire/manage-community-needs-assessment.blade.php`

```blade
<div class="p-6 bg-white rounded-lg shadow">
    <!-- Progress Indicator -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-blue-900">Community Needs Assessment</h2>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-900">{{ $responseCount }}</div>
                <div class="text-sm text-gray-600">responses collected ({{ $thresholdMet ? '✓ Threshold met' : "Need 20, have $responseCount" }})</div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-900 h-2.5 rounded-full" style="width: {{ min(($responseCount / 20) * 100, 100) }}%"></div>
        </div>
    </div>

    <!-- Summary Panel (if generated) -->
    @if ($summary)
        <div class="p-4 mb-6 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="font-bold text-green-900">Assessment Summary Generated</h3>
            <p class="text-sm text-green-800 mt-2">Based on {{ $summary->total_responses }} responses</p>
            
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div>
                    <div class="text-sm text-gray-600">Avg Age</div>
                    <div class="text-2xl font-bold">{{ $summary->avg_age }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Avg Family Size</div>
                    <div class="text-2xl font-bold">{{ $summary->avg_family_size }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Avg Income</div>
                    <div class="text-2xl font-bold">₱{{ number_format($summary->avg_income) }}</div>
                </div>
            </div>

            <button wire:click="approveSummary" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Approve & Finalize
            </button>
        </div>
    @endif

    <!-- New Assessment Button -->
    <button wire:click="$toggle('showForm')" class="mb-4 px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-800">
        + New Assessment Response
    </button>

    <!-- Form Modal -->
    @if ($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-96 overflow-y-auto">
                <h3 class="text-lg font-bold mb-4">Community Needs Assessment (F-CES-001)</h3>

                <form wire:submit="saveAssessment">
                    <!-- SECTION I: Identifying Information -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Respondent Name</label>
                        <input wire:model="respondent_name" type="text" class="w-full px-3 py-2 border rounded">
                        @error('respondent_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Age</label>
                        <input wire:model="age" type="number" class="w-full px-3 py-2 border rounded">
                        @error('age') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Additional fields following F-CES-001 sections -->
                    <!-- ... (20+ more fields) ... -->

                    <div class="flex gap-2 mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-800">
                            Save Response
                        </button>
                        <button wire:click="$toggle('showForm')" type="button" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Responses Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Respondent</th>
                    <th class="px-4 py-2 text-left">Age</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Submitted</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assessments as $assessment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $assessment->response_number ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $assessment->respondent_name }}</td>
                        <td class="px-4 py-2">{{ $assessment->age }}</td>
                        <td class="px-4 py-2">
                            <span class="text-xs px-2 py-1 rounded {{ $assessment->status == 'submitted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $assessment->submitted_at?->format('M d, Y') }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="edit({{ $assessment->id }})" class="text-blue-600 hover:underline text-xs">Edit</button>
                            <button wire:click="delete({{ $assessment->id }})" onclick="return confirm('Delete?')" class="text-red-600 hover:underline text-xs ml-2">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-600">No assessments yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $assessments->links() }}
    </div>
</div>
```

---

## PHASE 7: MIGRATION

### Migration File
Create with: `php artisan make:migration CreateCommunityNeedsAssessmentsTable`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table 1: Individual assessment responses
        Schema::create('community_needs_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->foreignId('community_id')->constrained('communities')->onDelete('cascade');
            $table->integer('response_number')->nullable();
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            
            // Response fields (matching F-CES-001)
            $table->string('respondent_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('sex')->nullable();
            $table->string('religion')->nullable();
            $table->string('educational_attainment')->nullable();
            
            // JSON fields for arrays
            $table->json('family_composition')->nullable();
            $table->json('livelihood_options')->nullable();
            $table->boolean('interested_in_training')->nullable();
            $table->json('desired_training')->nullable();
            $table->json('barangay_facilities')->nullable();
            $table->boolean('household_member_studying')->nullable();
            $table->boolean('interested_in_continuing_studies')->nullable();
            $table->json('areas_of_interest')->nullable();
            $table->string('preferred_time')->nullable();
            $table->json('preferred_days')->nullable();
            $table->json('common_illnesses')->nullable();
            $table->json('action_when_sick')->nullable();
            $table->json('barangay_medical_supplies')->nullable();
            $table->boolean('has_barangay_health_programs')->nullable();
            $table->boolean('benefits_from_programs')->nullable();
            $table->json('water_source')->nullable();
            $table->json('garbage_disposal')->nullable();
            $table->boolean('has_own_toilet')->nullable();
            $table->boolean('keeps_animals')->nullable();
            $table->string('house_type')->nullable();
            $table->string('tenure_status')->nullable();
            $table->boolean('has_electricity')->nullable();
            $table->json('barangay_recreational_facilities')->nullable();
            $table->json('use_of_free_time')->nullable();
            $table->boolean('member_of_organization')->nullable();
            $table->json('problems_family')->nullable();
            $table->json('problems_health')->nullable();
            $table->json('problems_education')->nullable();
            $table->json('problems_employment')->nullable();
            $table->json('problems_infrastructure')->nullable();
            $table->json('problems_economy')->nullable();
            $table->json('problems_security')->nullable();
            $table->json('barangay_service_ratings')->nullable();
            $table->text('general_feedback')->nullable();
            $table->boolean('available_for_training')->nullable();
            $table->text('reason_not_available')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['program_id', 'community_id']);
            $table->index('status');
        });

        // Table 2: Calculated summaries
        Schema::create('community_assessment_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->foreignId('community_id')->constrained('communities')->onDelete('cascade');
            $table->integer('total_responses');
            $table->boolean('response_threshold_met')->default(false);
            $table->timestamp('calculation_date');
            
            // Averages
            $table->decimal('avg_age', 5, 2)->nullable();
            $table->decimal('avg_family_size', 5, 2)->nullable();
            $table->decimal('avg_literacy_level', 3, 2)->nullable();
            $table->decimal('avg_income', 12, 2)->nullable();
            
            // Modals and top values
            $table->string('most_common_livelihood')->nullable();
            $table->string('most_common_primary_problem')->nullable();
            $table->string('most_common_primary_health_issue')->nullable();
            
            // Percentages
            $table->integer('percent_interested_in_training')->nullable();
            $table->integer('percent_interested_in_continuing_studies')->nullable();
            $table->integer('percent_with_electricity')->nullable();
            $table->integer('percent_with_own_toilet')->nullable();
            $table->integer('percent_with_benefits_from_health_programs')->nullable();
            
            // Service ratings
            $table->decimal('avg_law_enforcement_rating', 3, 2)->nullable();
            $table->decimal('avg_fire_protection_rating', 3, 2)->nullable();
            $table->decimal('avg_barangay_service_rating', 3, 2)->nullable();
            
            // Top values (JSON arrays)
            $table->json('top_family_challenges')->nullable();
            $table->json('top_health_challenges')->nullable();
            $table->json('top_education_challenges')->nullable();
            $table->json('top_employment_challenges')->nullable();
            $table->json('top_infrastructure_gaps')->nullable();
            $table->json('top_economic_needs')->nullable();
            $table->json('top_security_concerns')->nullable();
            $table->json('top_requested_skills')->nullable();
            
            // Baseline reference
            $table->foreignId('generated_baseline_id')->nullable()->constrained('program_baselines')->onDelete('set null');
            $table->boolean('baseline_auto_generated')->default(false);
            
            // Status tracking
            $table->enum('status', ['calculated', 'reviewed', 'baseline_created', 'archived'])->default('calculated');
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['program_id', 'community_id']);
            $table->index('status');
        });

        // Modify program_baselines to add assessment reference
        Schema::table('program_baselines', function (Blueprint $table) {
            $table->foreignId('assessment_summary_id')->nullable()->after('community_id')->constrained('community_assessment_summaries')->onDelete('set null');
            $table->boolean('auto_generated_from_assessment')->default(false)->after('status');
            $table->timestamp('auto_generated_at')->nullable()->after('auto_generated_from_assessment');
        });
    }

    public function down(): void
    {
        Schema::table('program_baselines', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['assessment_summary_id']);
            $table->dropColumn(['assessment_summary_id', 'auto_generated_from_assessment', 'auto_generated_at']);
        });

        Schema::dropIfExists('community_assessment_summaries');
        Schema::dropIfExists('community_needs_assessments');
    }
};
```

---

## PHASE 8: WORKFLOW SUMMARY

### Step-by-Step Process

1. **Secretary Creates Program**
   - Program title, budget, dates, communities

2. **Secretary Creates Logic Model**
   - Defines planned outputs, outcomes, targets

3. **Secretary Enters Community Needs Assessments**
   - Fills F-CES-001 form for minimum 20 community members
   - Each response is marked "submitted"
   - System counts responses in real-time

4. **System Auto-Triggers at 20 Responses**
   - Calculation Service runs automatically
   - Calculates averages from all 20+ responses
   - Generates CommunityAssessmentSummary record
   - Auto-creates baseline record (status: draft)

5. **Secretary Approves Baseline**
   - Logs into system
   - Sees "Baseline Auto-Generated" notification
   - Reviews calculated values
   - Can override any auto-calculated fields
   - Clicks "Approve & Finalize"
   - Baseline moves to "approved" status

6. **Program Proceeds with Approved Baseline**
   - Outputs recorded against approved baseline
   - KPI calculations use baseline targets
   - Program tracking begins

---

## KEY FEATURES

✅ **Automation:** 20 responses → Auto-calculation → Baseline auto-created
✅ **Transparency:** Secretary sees exact calculations
✅ **Flexibility:** Can override auto-calculated values before approval
✅ **Data-Driven:** Baseline grounded in actual community data
✅ **Audit Trail:** Tracks which assessment triggered baseline
✅ **Narrative reports:** Auto-generated key findings

---

## EFFORT ESTIMATION

| Phase | Task | Estimated Time |
|-------|------|-----------------|
| 1 | Planning & Understanding | 2 hours |
| 2 | Database design & migrations | 4 hours |
| 3 | Eloquent models | 3 hours |
| 4 | Calculation service | 6 hours |
| 5 | Livewire component | 5 hours |
| 6 | Blade views | 4 hours |
| 7 | Testing & debugging | 4 hours |
| **TOTAL** | **Full implementation** | **28 hours** |

### Reduced Scope (MVP):
- Core form (5 fields instead of 50) → 2 days
- Basic averaging (age, income, family size only) → 1 day
- Simple baseline auto-generation → 1 day
- **Total: 4 days**

