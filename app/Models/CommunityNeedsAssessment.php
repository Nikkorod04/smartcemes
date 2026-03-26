<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityNeedsAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'community_needs_assessments';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        // User & Community
        'user_id',
        'community_id',
        
        // Section I - Identifying Information
        'respondent_first_name',
        'respondent_middle_name',
        'respondent_last_name',
        'age',
        'civil_status',
        'sex',
        'religion',
        'educational_attainment',
        
        // Section II - Family Composition
        'family_members',
        
        // Section III - Economic Aspect
        'livelihoods',
        'interested_in_training',
        'desired_training',
        
        // Section IV - Educational Aspect
        'barangay_facilities',
        'household_member_studying',
        'interested_in_continuing_studies',
        'areas_of_interest',
        'preferred_time',
        'preferred_days',
        
        // Section V - Health, Sanitation, Environmental
        'common_illnesses',
        'action_when_sick',
        'barangay_medical_supplies',
        'has_barangay_health_programs',
        'benefits_from_programs',
        'programs_benefited',
        'water_source',
        'water_source_distance',
        'garbage_disposal',
        'has_own_toilet',
        'toilet_type',
        'keeps_animals',
        'animals_kept',
        
        // Section VI - Housing and Basic Amenities
        'housing_type',
        'tenure_status',
        'has_electricity',
        'light_source_no_power',
        'appliances',
        
        // Section VII - Recreational Facilities
        'barangay_recreational_facilities',
        'use_of_free_time',
        'member_of_organization',
        'group_type',
        'meeting_frequency',

        // Section VIII - Other Needs & Problems
        'problems_family',
        'problems_health',
        'problems_education',
        'problems_employment',
        'problems_infrastructure',
        'problems_economy',
        'problems_security',
        
        // Section IX - Summary & Ratings
        'barangay_service_rating_police',
        'barangay_service_rating_fire',
        'barangay_service_rating_bns',
        'barangay_service_rating_water',
        'barangay_service_rating_roads',
        'barangay_service_rating_clinic',
        'barangay_service_rating_market',
        'barangay_service_rating_community_center',
        'barangay_service_rating_lights',
        'general_feedback',
        'available_for_training',
        'reason_not_available',
        
        // Status & Tracking
        'form_progress',
        'status',
        'submission_notes',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'ip_address',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'family_members' => 'array',
        'age' => 'integer',
        'form_progress' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'barangay_service_rating_police' => 'integer',
        'barangay_service_rating_fire' => 'integer',
        'barangay_service_rating_bns' => 'integer',
        'barangay_service_rating_water' => 'integer',
        'barangay_service_rating_roads' => 'integer',
        'barangay_service_rating_clinic' => 'integer',
        'barangay_service_rating_market' => 'integer',
        'barangay_service_rating_community_center' => 'integer',
        'barangay_service_rating_lights' => 'integer',
    ];

    /**
     * Relationships
     */

    /**
     * Assessment belongs to a user (secretary who entered it).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Assessment belongs to a community.
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Assessment reviewed by another user (admin/reviewer).
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes
     */

    /**
     * Get submitted assessments only.
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Get draft assessments.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get approved assessments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get assessments for a specific community.
     */
    public function scopeForCommunity($query, $communityId)
    {
        return $query->where('community_id', $communityId);
    }

    /**
     * Get recent assessments (last 30 days).
     */
    public function scopeRecent($query)
    {
        return $query->where('submitted_at', '>=', now()->subDays(30))->orderBy('submitted_at', 'desc');
    }

    /**
     * Accessors & Mutators
     */

    /**
     * Format progress as percentage.
     */
    public function getFormProgressAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft' => 'badge-secondary',
            'submitted' => 'badge-info',
            'reviewed' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-light',
        };
    }

    /**
     * Get display status.
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'reviewed' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }

    /**
     * Calculate average barangay service rating.
     */
    public function getAverageServiceRatingAttribute()
    {
        $ratings = [
            $this->barangay_service_rating_police,
            $this->barangay_service_rating_fire,
            $this->barangay_service_rating_bns,
            $this->barangay_service_rating_water,
            $this->barangay_service_rating_roads,
            $this->barangay_service_rating_clinic,
            $this->barangay_service_rating_market,
            $this->barangay_service_rating_community_center,
            $this->barangay_service_rating_lights,
        ];
        
        $ratedCount = count(array_filter($ratings));
        if ($ratedCount === 0) {
            return 0;
        }
        
        return round(array_sum($ratings) / $ratedCount, 2);
    }

    /**
     * Methods
     */

    /**
     * Mark assessment as submitted.
     */
    public function markAsSubmitted(string $ipAddress = null): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    /**
     * Mark assessment as approved.
     */
    public function markAsApproved(int $userId, string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'submission_notes' => $notes,
        ]);
    }

    /**
     * Mark assessment as rejected.
     */
    public function markAsRejected(int $userId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'submission_notes' => $reason,
        ]);
    }

    /**
     * Count completed fields.
     */
    public function countCompletedFields(): int
    {
        $fields = [
            'respondent_first_name',
            'respondent_last_name',
            'age',
            'civil_status',
            'sex',
            'religion',
            'educational_attainment',
            'livelihoods',
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
            'programs_benefited',
            'water_source',
            'water_source_distance',
            'garbage_disposal',
            'has_own_toilet',
            'toilet_type',
            'keeps_animals',
            'animals_kept',
            'housing_type',
            'tenure_status',
            'has_electricity',
            'light_source_no_power',
            'appliances',
            'barangay_recreational_facilities',
            'use_of_free_time',
            'member_of_organization',
            'group_type',
            'meeting_frequency',
            'usual_activities',
            'household_member_in_group',
            'position_in_group',
            'problems_family',
            'problems_health',
            'problems_education',
            'problems_employment',
            'problems_infrastructure',
            'problems_economy',
            'problems_security',
            'barangay_service_rating_police',
            'barangay_service_rating_fire',
            'barangay_service_rating_bns',
            'barangay_service_rating_water',
            'barangay_service_rating_roads',
            'barangay_service_rating_clinic',
            'barangay_service_rating_market',
            'barangay_service_rating_community_center',
            'barangay_service_rating_lights',
            'general_feedback',
            'available_for_training',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return $completed;
    }

    /**
     * Calculate form completion percentage.
     */
    public function calculateFormProgress(): int
    {
        $totalFields = 62; // Total fields (excluding optional ones)
        $completedFields = $this->countCompletedFields();
        $percentage = ($completedFields / $totalFields) * 100;
        
        $this->update(['form_progress' => $percentage]);
        
        return round($percentage);
    }

    /**
     * Get summary of responses.
     */
    public function getSummary(): array
    {
        return [
            'respondent' => trim($this->respondent_first_name . ' ' . $this->respondent_middle_name . ' ' . $this->respondent_last_name),
            'age' => $this->age,
            'community' => $this->community->name ?? 'Unknown',
            'submitted_at' => $this->submitted_at?->format('Y-m-d H:i'),
            'status' => $this->status_label,
            'progress' => $this->form_progress . '%',
            'average_rating' => $this->average_service_rating,
        ];
    }

    /**
     * Check if assessment has been submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    /**
     * Export to array for reports.
     */
    public function toReportArray(): array
    {
        return [
            'respondent_first_name' => $this->respondent_first_name,
            'respondent_middle_name' => $this->respondent_middle_name,
            'respondent_last_name' => $this->respondent_last_name,
            'age' => $this->age,
            'civil_status' => $this->civil_status,
            'sex' => $this->sex,
            'religion' => $this->religion,
            'educational_attainment' => $this->educational_attainment,
            'livelihood' => $this->livelihoods,
            'interested_training' => $this->interested_in_training,
            'water_source' => $this->water_source,
            'housing_type' => $this->housing_type,
            'has_electricity' => $this->has_electricity,
            'health_problems' => $this->problems_health,
            'education_problems' => $this->problems_education,
            'employment_problems' => $this->problems_employment,
            'submitted_at' => $this->submitted_at,
        ];
    }
}
