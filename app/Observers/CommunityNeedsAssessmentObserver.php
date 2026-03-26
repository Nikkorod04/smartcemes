<?php

namespace App\Observers;

use App\Models\CommunityNeedsAssessment;
use App\Services\CommunityAssessmentCalculationService;

class CommunityNeedsAssessmentObserver
{
    protected $calculationService;

    public function __construct(CommunityAssessmentCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Handle the CommunityNeedsAssessment "created" event.
     */
    public function created(CommunityNeedsAssessment $assessment): void
    {
        // Only recalculate if assessment is submitted or approved
        if ($assessment->isSubmitted()) {
            $this->calculationService->calculateSummary($assessment->community_id);
        }
    }

    /**
     * Handle the CommunityNeedsAssessment "updated" event.
     */
    public function updated(CommunityNeedsAssessment $assessment): void
    {
        // Recalculate if status changed to submitted
        if ($assessment->isDirty('status') && $assessment->isSubmitted()) {
            $this->calculationService->calculateSummary($assessment->community_id);
        }

        // Recalculate if any assessment data changed
        if ($assessment->isDirty(['age', 'civil_status', 'sex', 'religion', 'educational_attainment', 
                                  'livelihoods', 'interested_in_training', 'desired_training',
                                  'household_member_studying', 'interested_in_continuing_studies',
                                  'preferred_time', 'preferred_days', 'water_source', 'water_source_distance',
                                  'has_own_toilet', 'has_electricity', 'keeps_animals', 'housing_type',
                                  'tenure_status', 'member_of_organization', 'meeting_frequency',
                                  'problems_family', 'problems_health', 'problems_education',
                                  'problems_employment', 'problems_infrastructure', 'problems_economy',
                                  'problems_security', 'barangay_service_rating_police',
                                  'barangay_service_rating_fire', 'barangay_service_rating_bns',
                                  'barangay_service_rating_water', 'barangay_service_rating_roads',
                                  'barangay_service_rating_clinic', 'barangay_service_rating_market',
                                  'barangay_service_rating_community_center', 'barangay_service_rating_lights',
                                  'general_feedback', 'available_for_training'])) {
            if ($assessment->isSubmitted()) {
                $this->calculationService->calculateSummary($assessment->community_id);
            }
        }
    }

    /**
     * Handle the CommunityNeedsAssessment "deleted" event.
     */
    public function deleted(CommunityNeedsAssessment $assessment): void
    {
        // Recalculate after deletion
        if ($assessment->community_id && $assessment->isSubmitted()) {
            $this->calculationService->calculateSummary($assessment->community_id);
        }
    }

    /**
     * Handle the CommunityNeedsAssessment "restored" event.
     */
    public function restored(CommunityNeedsAssessment $assessment): void
    {
        // Recalculate after restoration
        if ($assessment->isSubmitted()) {
            $this->calculationService->calculateSummary($assessment->community_id);
        }
    }
}
