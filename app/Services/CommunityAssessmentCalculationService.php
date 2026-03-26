<?php

namespace App\Services;

use App\Models\CommunityNeedsAssessment;
use App\Models\CommunityAssessmentSummary;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class CommunityAssessmentCalculationService
{
    /**
     * Recalculate assessment summary for a community
     * Called every time an assessment is submitted
     */
    public function calculateSummary($communityId)
    {
        // Get all submitted assessments for this community
        $assessments = CommunityNeedsAssessment::forCommunity($communityId)
            ->submitted()
            ->get();

        if ($assessments->isEmpty()) {
            return null;
        }

        $count = $assessments->count();

        // SECTION I: Identifying Information
        $avgAge = $assessments->avg('age');
        $commonCivilStatus = $this->getMostCommon($assessments->pluck('civil_status'));
        $commonSex = $this->getMostCommon($assessments->pluck('sex'));
        $commonReligion = $this->getMostCommon($assessments->pluck('religion'));
        $commonEducation = $this->getMostCommon($assessments->pluck('educational_attainment'));

        // SECTION III: Economic Aspect
        $commonLivelihood = $this->getMostCommon($assessments->pluck('livelihoods'));
        $percentTraining = $this->getPercentageYes($assessments->pluck('interested_in_training')) * 100;
        $commonTrainings = $this->getMostCommonMultiple($assessments->pluck('desired_training'), 5);

        // SECTION IV: Educational Aspect
        $percentStudying = $this->getPercentageYes($assessments->pluck('household_member_studying')) * 100;
        $percentContinueStudies = $this->getPercentageYes($assessments->pluck('interested_in_continuing_studies')) * 100;
        $commonPrefTime = $this->getMostCommon($assessments->pluck('preferred_time'));
        $commonPrefDays = $this->getMostCommon($assessments->pluck('preferred_days'));

        // SECTION V: Health, Sanitation, Environmental
        $commonWaterSource = $this->getMostCommon($assessments->pluck('water_source'));
        $commonWaterDistance = $this->getMostCommon($assessments->pluck('water_source_distance'));
        $percentOwnToilet = $this->getPercentageYes($assessments->pluck('has_own_toilet')) * 100;
        $percentElectricity = $this->getPercentageYes($assessments->pluck('has_electricity')) * 100;
        $percentKeepsAnimals = $this->getPercentageYes($assessments->pluck('keeps_animals')) * 100;
        $commonHealthPrograms = $this->getMostCommonMultiple($assessments->pluck('programs_benefited'), 3);
        $percentBenefitsPrograms = $this->getPercentageYes($assessments->pluck('benefits_from_programs')) * 100;

        // SECTION VI: Housing and Basic Amenities
        $commonHousingType = $this->getMostCommon($assessments->pluck('housing_type'));
        $commonTenureStatus = $this->getMostCommon($assessments->pluck('tenure_status'));
        $commonAppliances = $this->getMostCommonMultiple($assessments->pluck('appliances'), 5);

        // SECTION VII: Recreational Facilities
        $percentMemberOrg = $this->getPercentageYes($assessments->pluck('member_of_organization')) * 100;
        $commonMeetingFreq = $this->getMostCommon($assessments->pluck('meeting_frequency'));

        // SECTION VIII: Problems (get top problems per category)
        $topFamilyProblems = $this->getTopProblems($assessments->pluck('problems_family'), 5);
        $topHealthProblems = $this->getTopProblems($assessments->pluck('problems_health'), 5);
        $topEducationProblems = $this->getTopProblems($assessments->pluck('problems_education'), 5);
        $topEmploymentProblems = $this->getTopProblems($assessments->pluck('problems_employment'), 5);
        $topInfrastructure = $this->getTopProblems($assessments->pluck('problems_infrastructure'), 5);
        $topEconomy = $this->getTopProblems($assessments->pluck('problems_economy'), 5);
        $topSecurity = $this->getTopProblems($assessments->pluck('problems_security'), 5);

        // SECTION IX: Service Ratings (Averages)
        $avgPolice = $this->getAverageRating($assessments->pluck('barangay_service_rating_police'));
        $avgFire = $this->getAverageRating($assessments->pluck('barangay_service_rating_fire'));
        $avgBns = $this->getAverageRating($assessments->pluck('barangay_service_rating_bns'));
        $avgWater = $this->getAverageRating($assessments->pluck('barangay_service_rating_water'));
        $avgRoads = $this->getAverageRating($assessments->pluck('barangay_service_rating_roads'));
        $avgClinic = $this->getAverageRating($assessments->pluck('barangay_service_rating_clinic'));
        $avgMarket = $this->getAverageRating($assessments->pluck('barangay_service_rating_market'));
        $avgCommunityCenter = $this->getAverageRating($assessments->pluck('barangay_service_rating_community_center'));
        $avgLights = $this->getAverageRating($assessments->pluck('barangay_service_rating_lights'));

        // Overall satisfaction
        $allRatings = collect([
            $avgPolice, $avgFire, $avgBns, $avgWater, $avgRoads,
            $avgClinic, $avgMarket, $avgCommunityCenter, $avgLights
        ])->filter()->values();
        
        $overallSatisfaction = $allRatings->count() > 0 
            ? $allRatings->average() 
            : null;

        // Training availability
        $percentAvailableTraining = $this->getPercentageYes($assessments->pluck('available_for_training')) * 100;

        // Key feedback themes (extract common words from general_feedback)
        $keyThemes = $this->extractKeyThemes($assessments->pluck('general_feedback'));

        // Find or create summary for this community
        $summary = CommunityAssessmentSummary::firstOrCreate(
            ['community_id' => $communityId],
            [
                'total_assessments' => 0,
                'last_calculated_at' => now(),
            ]
        );

        // Update with new calculations
        $summary->update([
            'total_assessments' => $count,
            'last_calculated_at' => now(),
            'avg_age' => round($avgAge, 2),
            'most_common_civil_status' => $commonCivilStatus,
            'most_common_sex' => $commonSex,
            'most_common_religion' => $commonReligion,
            'most_common_educational_attainment' => $commonEducation,
            'most_common_livelihood' => $commonLivelihood,
            'percent_interested_in_training' => round($percentTraining),
            'common_desired_trainings' => is_array($commonTrainings) ? json_encode($commonTrainings) : $commonTrainings,
            'percent_household_member_studying' => round($percentStudying),
            'percent_interested_in_continuing_studies' => round($percentContinueStudies),
            'most_common_preferred_time' => $commonPrefTime,
            'most_common_preferred_days' => $commonPrefDays,
            'most_common_water_source' => $commonWaterSource,
            'most_common_water_source_distance' => $commonWaterDistance,
            'percent_has_own_toilet' => round($percentOwnToilet),
            'percent_has_electricity' => round($percentElectricity),
            'percent_keeps_animals' => round($percentKeepsAnimals),
            'common_health_programs' => is_array($commonHealthPrograms) ? json_encode($commonHealthPrograms) : $commonHealthPrograms,
            'percent_benefits_from_health_programs' => round($percentBenefitsPrograms),
            'most_common_housing_type' => $commonHousingType,
            'most_common_tenure_status' => $commonTenureStatus,
            'common_appliances' => is_array($commonAppliances) ? json_encode($commonAppliances) : $commonAppliances,
            'percent_member_of_organization' => round($percentMemberOrg),
            'most_common_meeting_frequency' => $commonMeetingFreq,
            'top_family_problems' => $topFamilyProblems,
            'top_health_problems' => $topHealthProblems,
            'top_education_problems' => $topEducationProblems,
            'top_employment_problems' => $topEmploymentProblems,
            'top_infrastructure_problems' => $topInfrastructure,
            'top_economy_problems' => $topEconomy,
            'top_security_problems' => $topSecurity,
            'avg_service_rating_police' => round($avgPolice, 2),
            'avg_service_rating_fire' => round($avgFire, 2),
            'avg_service_rating_bns' => round($avgBns, 2),
            'avg_service_rating_water' => round($avgWater, 2),
            'avg_service_rating_roads' => round($avgRoads, 2),
            'avg_service_rating_clinic' => round($avgClinic, 2),
            'avg_service_rating_market' => round($avgMarket, 2),
            'avg_service_rating_community_center' => round($avgCommunityCenter, 2),
            'avg_service_rating_lights' => round($avgLights, 2),
            'overall_service_satisfaction' => round($overallSatisfaction, 2),
            'percent_available_for_training' => round($percentAvailableTraining),
            'key_feedback_themes' => $keyThemes,
        ]);

        return $summary;
    }

    /**
     * Get the most common value from a collection
     */
    private function getMostCommon(Collection $items)
    {
        return $items
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();
    }

    /**
     * Get the top N most common values from a collection
     */
    private function getMostCommonMultiple(Collection $items, $limit = 5)
    {
        return $items
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take($limit)
            ->keys()
            ->toArray();
    }

    /**
     * Calculate percentage of 'yes' values in a collection
     */
    private function getPercentageYes(Collection $items)
    {
        $filtered = $items->filter();
        if ($filtered->isEmpty()) {
            return 0;
        }

        $yesCount = $filtered->filter(fn($item) => strtolower($item) === 'yes')->count();
        return $yesCount / $filtered->count();
    }

    /**
     * Get average of rating values (1-5 scale)
     */
    private function getAverageRating(Collection $items)
    {
        $filtered = $items->filter();
        return $filtered->isEmpty() ? null : $filtered->avg();
    }

    /**
     * Extract top problems from text fields
     * Problems are typically comma-separated or listed
     */
    private function getTopProblems(Collection $items, $limit = 5)
    {
        $problems = [];

        foreach ($items->filter() as $item) {
            // Split by comma if multiple problems listed
            $parts = array_map('trim', explode(',', $item));
            foreach ($parts as $part) {
                if (strlen($part) > 2) {
                    $problems[$part] = ($problems[$part] ?? 0) + 1;
                }
            }
        }

        arsort($problems);
        
        $top = array_slice($problems, 0, $limit, true);
        
        return array_keys($top);
    }

    /**
     * Extract key themes from feedback text
     * Looks for common words/phrases
     */
    private function extractKeyThemes(Collection $feedbacks)
    {
        $allText = $feedbacks->filter()->implode(' ');
        
        // Simple keyword extraction - can be enhanced with NLP later
        $keywords = [
            'livelihood' => 0,
            'training' => 0,
            'health' => 0,
            'education' => 0,
            'infrastructure' => 0,
            'water' => 0,
            'electricity' => 0,
            'employment' => 0,
            'skills' => 0,
            'support' => 0,
        ];

        foreach (array_keys($keywords) as $keyword) {
            $count = substr_count(strtolower($allText), $keyword);
            if ($count > 0) {
                $keywords[$keyword] = $count;
            }
        }

        // Get top 5 themes
        arsort($keywords);
        $topThemes = array_filter($keywords, fn($count) => $count > 0);
        
        return array_keys(array_slice($topThemes, 0, 5, true));
    }
}
