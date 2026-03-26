<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityAssessmentSummary extends Model
{
    use SoftDeletes;

    protected $table = 'community_assessment_summaries';

    protected $fillable = [
        'community_id',
        'total_assessments',
        'last_calculated_at',
        'avg_age',
        'most_common_civil_status',
        'most_common_sex',
        'most_common_religion',
        'most_common_educational_attainment',
        'most_common_livelihood',
        'percent_interested_in_training',
        'common_desired_trainings',
        'percent_household_member_studying',
        'percent_interested_in_continuing_studies',
        'most_common_preferred_time',
        'most_common_preferred_days',
        'most_common_water_source',
        'most_common_water_source_distance',
        'percent_has_own_toilet',
        'percent_has_electricity',
        'percent_keeps_animals',
        'common_health_programs',
        'percent_benefits_from_health_programs',
        'most_common_housing_type',
        'most_common_tenure_status',
        'common_appliances',
        'percent_member_of_organization',
        'most_common_meeting_frequency',
        'top_family_problems',
        'top_health_problems',
        'top_education_problems',
        'top_employment_problems',
        'top_infrastructure_problems',
        'top_economy_problems',
        'top_security_problems',
        'avg_service_rating_police',
        'avg_service_rating_fire',
        'avg_service_rating_bns',
        'avg_service_rating_water',
        'avg_service_rating_roads',
        'avg_service_rating_clinic',
        'avg_service_rating_market',
        'avg_service_rating_community_center',
        'avg_service_rating_lights',
        'overall_service_satisfaction',
        'percent_available_for_training',
        'key_feedback_themes',
        'summary_notes',
        'ai_summary',
        'ai_summary_generated_at',
    ];

    protected $casts = [
        'last_calculated_at' => 'datetime',
        'ai_summary_generated_at' => 'datetime',
        'top_family_problems' => 'array',
        'top_health_problems' => 'array',
        'top_education_problems' => 'array',
        'top_employment_problems' => 'array',
        'top_infrastructure_problems' => 'array',
        'top_economy_problems' => 'array',
        'top_security_problems' => 'array',
        'key_feedback_themes' => 'array',
    ];

    /**
     * Relationships
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the assessments that contributed to this summary
     */
    public function assessments()
    {
        return $this->community->needsAssessments();
    }
}
