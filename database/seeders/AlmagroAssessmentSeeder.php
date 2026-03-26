<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\CommunityAssessmentSummary;
use Illuminate\Database\Seeder;

class AlmagroAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds for Almagro, Samar community assessments.
     */
    public function run(): void
    {
        $community = Community::where('name', 'Almagro')
            ->where('municipality', 'Almagro')
            ->where('province', 'Samar')
            ->firstOrFail();

        $assessments = [
            [
                'total_assessments' => 145,
                'avg_age' => 38.7,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Coconut Fiber Processing',
                'percent_interested_in_training' => 72,
                'common_desired_trainings' => json_encode(['Entrepreneurship', 'Digital Literacy', 'Food Processing']),
                'percent_household_member_studying' => 65,
                'percent_interested_in_continuing_studies' => 58,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Deep Well',
                'most_common_water_source_distance' => 'Less than 100 meters',
                'percent_has_own_toilet' => 68,
                'percent_has_electricity' => 82,
                'percent_keeps_animals' => 45,
                'common_health_programs' => json_encode(['Maternal and Child Health', 'Immunization Drive', 'Blood Pressure Monitoring']),
                'percent_benefits_from_health_programs' => 71,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Refrigerator', 'Electric Fan', 'Television']),
                'percent_member_of_organization' => 56,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Financial Instability', 'frequency' => 82],
                    ['problem' => 'Health Issues', 'frequency' => 64],
                    ['problem' => 'Education Expenses', 'frequency' => 71]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Hypertension', 'frequency' => 58],
                    ['problem' => 'Diarrhea', 'frequency' => 42],
                    ['problem' => 'Respiratory Infection', 'frequency' => 39]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'High School Dropout', 'frequency' => 47],
                    ['problem' => 'Limited Learning Materials', 'frequency' => 55],
                    ['problem' => 'Teacher Shortage', 'frequency' => 31]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Seasonal Work Only', 'frequency' => 76],
                    ['problem' => 'Low Income', 'frequency' => 89],
                    ['problem' => 'No Skills Training', 'frequency' => 63]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Poor Roads', 'frequency' => 68],
                    ['problem' => 'Limited Water Supply', 'frequency' => 52],
                    ['problem' => 'Unstable Electricity', 'frequency' => 44]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Market Access Issues', 'frequency' => 61],
                    ['problem' => 'Volatile Coconut Prices', 'frequency' => 72],
                    ['problem' => 'Limited Capital', 'frequency' => 78]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Theft/Robbery', 'frequency' => 28],
                    ['problem' => 'Drunk Individuals', 'frequency' => 35],
                    ['problem' => 'Family Violence', 'frequency' => 22]
                ]),
                'avg_service_rating_police' => 3.5,
                'avg_service_rating_fire' => 3.2,
                'avg_service_rating_bns' => 3.8,
                'avg_service_rating_water' => 3.1,
                'avg_service_rating_roads' => 2.8,
                'avg_service_rating_clinic' => 3.9,
                'avg_service_rating_market' => 3.6,
                'avg_service_rating_community_center' => 3.4,
                'avg_service_rating_lights' => 3.3,
                'overall_service_satisfaction' => 3.4,
                'percent_available_for_training' => 68,
                'key_feedback_themes' => json_encode(['Economic Support', 'Health Services', 'Education Access', 'Skills Training', 'Infrastructure']),
                'summary_notes' => 'Assessment conducted Q1 2026. Strong interest in livelihood trainings. Primary concerns are economic stability and seasonal employment.'
            ],
            [
                'total_assessments' => 152,
                'avg_age' => 39.2,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Fishing',
                'percent_interested_in_training' => 68,
                'common_desired_trainings' => json_encode(['Sustainable Fishing', 'Fish Processing', 'Marine Conservation']),
                'percent_household_member_studying' => 62,
                'percent_interested_in_continuing_studies' => 55,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Barangay Water System',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 71,
                'percent_has_electricity' => 79,
                'percent_keeps_animals' => 52,
                'common_health_programs' => json_encode(['Maternal and Child Health', 'Nutrition Program', 'Immunization']),
                'percent_benefits_from_health_programs' => 69,
                'most_common_housing_type' => 'Wood and Nipa',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Refrigerator', 'Electric Fan', 'Television', 'Radio']),
                'percent_member_of_organization' => 53,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Low Income from Fishing', 'frequency' => 88],
                    ['problem' => 'Health Expenses', 'frequency' => 62],
                    ['problem' => 'Educational Support', 'frequency' => 68]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Diarrhea', 'frequency' => 51],
                    ['problem' => 'Respiratory Issues', 'frequency' => 44],
                    ['problem' => 'Hypertension', 'frequency' => 48]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'School Dropout', 'frequency' => 52],
                    ['problem' => 'Limited Materials', 'frequency' => 58],
                    ['problem' => 'Far School Distance', 'frequency' => 38]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Weather-Dependent Work', 'frequency' => 81],
                    ['problem' => 'Low Fish Prices', 'frequency' => 85],
                    ['problem' => 'No Alternative Livelihood', 'frequency' => 59]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Poor Port Facilities', 'frequency' => 73],
                    ['problem' => 'Damaged Roads', 'frequency' => 65],
                    ['problem' => 'No Cold Storage', 'frequency' => 67]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Unstable Fish Prices', 'frequency' => 79],
                    ['problem' => 'Transport Costs', 'frequency' => 64],
                    ['problem' => 'No Credit Access', 'frequency' => 71]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Piracy/Robbery at Sea', 'frequency' => 24],
                    ['problem' => 'Land-Based Theft', 'frequency' => 19],
                    ['problem' => 'Drug-Related Issues', 'frequency' => 15]
                ]),
                'avg_service_rating_police' => 3.3,
                'avg_service_rating_fire' => 3.0,
                'avg_service_rating_bns' => 3.2,
                'avg_service_rating_water' => 2.9,
                'avg_service_rating_roads' => 2.6,
                'avg_service_rating_clinic' => 3.7,
                'avg_service_rating_market' => 3.2,
                'avg_service_rating_community_center' => 3.1,
                'avg_service_rating_lights' => 3.1,
                'overall_service_satisfaction' => 3.1,
                'percent_available_for_training' => 64,
                'key_feedback_themes' => json_encode(['Market Access', 'Fish Processing', 'Port Development', 'Climate Resilience', 'Livelihood']),
                'summary_notes' => 'Fishing communities heavily reliant on weather. Need for storage and processing facilities. Strong desire for market cooperation.'
            ],
            [
                'total_assessments' => 138,
                'avg_age' => 37.8,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'Elementary Graduate',
                'most_common_livelihood' => 'Farming',
                'percent_interested_in_training' => 75,
                'common_desired_trainings' => json_encode(['Organic Farming', 'Irrigation Technology', 'Crop Diversification']),
                'percent_household_member_studying' => 68,
                'percent_interested_in_continuing_studies' => 61,
                'most_common_preferred_time' => 'Morning',
                'most_common_preferred_days' => 'Weekdays',
                'most_common_water_source' => 'Shallow Well',
                'most_common_water_source_distance' => 'Within 100 meters',
                'percent_has_own_toilet' => 65,
                'percent_has_electricity' => 81,
                'percent_keeps_animals' => 58,
                'common_health_programs' => json_encode(['Immunization', 'Maternal Health', 'Wellness Program']),
                'percent_benefits_from_health_programs' => 73,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Radio', 'Electric Fan', 'Mobile Phone Charger']),
                'percent_member_of_organization' => 61,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Crop Failure/Bad Harvest', 'frequency' => 76],
                    ['problem' => 'Medical Expenses', 'frequency' => 59],
                    ['problem' => 'School Fees', 'frequency' => 73]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Pesticide Poisoning', 'frequency' => 35],
                    ['problem' => 'Hypertension', 'frequency' => 52],
                    ['problem' => 'Malnutrition', 'frequency' => 28]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Dropout Due to Work', 'frequency' => 48],
                    ['problem' => 'Learning Materials Cost', 'frequency' => 62],
                    ['problem' => 'Teacher Absence', 'frequency' => 29]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Seasonal Employment', 'frequency' => 78],
                    ['problem' => 'Low Farm Income', 'frequency' => 84],
                    ['problem' => 'No Off-Season Jobs', 'frequency' => 65]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Poor Access to Markets', 'frequency' => 64],
                    ['problem' => 'No Irrigation System', 'frequency' => 71],
                    ['problem' => 'Bad Farm Roads', 'frequency' => 69]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Low Agricultural Prices', 'frequency' => 81],
                    ['problem' => 'High Input Costs', 'frequency' => 74],
                    ['problem' => 'Limited Farm Credit', 'frequency' => 72]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Crop/Animal Theft', 'frequency' => 32],
                    ['problem' => 'Wild Animal Damage', 'frequency' => 26],
                    ['problem' => 'Land Disputes', 'frequency' => 18]
                ]),
                'avg_service_rating_police' => 3.4,
                'avg_service_rating_fire' => 2.9,
                'avg_service_rating_bns' => 3.6,
                'avg_service_rating_water' => 3.2,
                'avg_service_rating_roads' => 2.7,
                'avg_service_rating_clinic' => 3.8,
                'avg_service_rating_market' => 3.1,
                'avg_service_rating_community_center' => 3.3,
                'avg_service_rating_lights' => 3.2,
                'overall_service_satisfaction' => 3.2,
                'percent_available_for_training' => 71,
                'key_feedback_themes' => json_encode(['Agricultural Support', 'Water Management', 'Price Stabilization', 'Crop Insurance', 'Market Access']),
                'summary_notes' => 'Agricultural sector vulnerable to climate. Farmers need irrigation and credit support. Strong interest in technology adoption.'
            ],
            [
                'total_assessments' => 148,
                'avg_age' => 36.5,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Mixed (Farming + Fishing)',
                'percent_interested_in_training' => 70,
                'common_desired_trainings' => json_encode(['Multi-purpose Livelihood', 'Business Start-up', 'Aquaculture']),
                'percent_household_member_studying' => 64,
                'percent_interested_in_continuing_studies' => 57,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Community Well',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 69,
                'percent_has_electricity' => 80,
                'percent_keeps_animals' => 49,
                'common_health_programs' => json_encode(['Family Planning', 'Immunization', 'Health Education']),
                'percent_benefits_from_health_programs' => 70,
                'most_common_housing_type' => 'Mixed Materials',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Electric Fan', 'Mobile Phone']),
                'percent_member_of_organization' => 54,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Income Instability', 'frequency' => 85],
                    ['problem' => 'Healthcare Access', 'frequency' => 61],
                    ['problem' => 'Children Education', 'frequency' => 70]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Hypertension', 'frequency' => 55],
                    ['problem' => 'Waterborne Diseases', 'frequency' => 45],
                    ['problem' => 'Malaria Risk', 'frequency' => 24]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Early Dropout', 'frequency' => 50],
                    ['problem' => 'Material Scarcity', 'frequency' => 56],
                    ['problem' => 'Limited Scholarship', 'frequency' => 48]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Dual income vulnerability', 'frequency' => 77],
                    ['problem' => 'Unstable Income', 'frequency' => 88],
                    ['problem' => 'Need Diversification', 'frequency' => 62]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Poor Road Network', 'frequency' => 66],
                    ['problem' => 'Water Supply Issues', 'frequency' => 54],
                    ['problem' => 'Storage Facilities', 'frequency' => 59]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Price Volatility', 'frequency' => 75],
                    ['problem' => 'Limited Market Options', 'frequency' => 63],
                    ['problem' => 'Lack of Capital', 'frequency' => 76]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Petty Theft', 'frequency' => 26],
                    ['problem' => 'Disputes Resolution', 'frequency' => 21],
                    ['problem' => 'Occasional Violence', 'frequency' => 12]
                ]),
                'avg_service_rating_police' => 3.4,
                'avg_service_rating_fire' => 3.1,
                'avg_service_rating_bns' => 3.7,
                'avg_service_rating_water' => 3.0,
                'avg_service_rating_roads' => 2.7,
                'avg_service_rating_clinic' => 3.8,
                'avg_service_rating_market' => 3.3,
                'avg_service_rating_community_center' => 3.2,
                'avg_service_rating_lights' => 3.2,
                'overall_service_satisfaction' => 3.2,
                'percent_available_for_training' => 67,
                'key_feedback_themes' => json_encode(['Livelihood Diversification', 'Income Stability', 'Infrastructure', 'Market Support']),
                'summary_notes' => 'Communities with dual livelihoods face unique challenges. Need integrated support systems.'
            ],
            [
                'total_assessments' => 141,
                'avg_age' => 38.9,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Coconut Fiber Processing',
                'percent_interested_in_training' => 73,
                'common_desired_trainings' => json_encode(['Product Quality', 'Management Skills', 'Export Preparation']),
                'percent_household_member_studying' => 66,
                'percent_interested_in_continuing_studies' => 59,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Deep Well',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 70,
                'percent_has_electricity' => 83,
                'percent_keeps_animals' => 46,
                'common_health_programs' => json_encode(['Occupational Health', 'Immunization', 'Wellness']),
                'percent_benefits_from_health_programs' => 68,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Fan', 'Refrigerator', 'Mobile Phone']),
                'percent_member_of_organization' => 58,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Labor Income Variability', 'frequency' => 79],
                    ['problem' => 'Healthcare Cost', 'frequency' => 57],
                    ['problem' => 'Children Education', 'frequency' => 69]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Dust Inhalation Issues', 'frequency' => 62],
                    ['problem' => 'Skin Irritation', 'frequency' => 48],
                    ['problem' => 'Hypertension', 'frequency' => 51]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Child Labor in Processing', 'frequency' => 43],
                    ['problem' => 'Limited Learning Materials', 'frequency' => 54],
                    ['problem' => 'School Transportation', 'frequency' => 35]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Low Wages', 'frequency' => 82],
                    ['problem' => 'No Benefits', 'frequency' => 75],
                    ['problem' => 'Limited Job Security', 'frequency' => 68]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Poor Factory Roads', 'frequency' => 56],
                    ['problem' => 'Limited Water Supply', 'frequency' => 48],
                    ['problem' => 'Waste Management', 'frequency' => 51]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Fiber Price Fluctuation', 'frequency' => 73],
                    ['problem' => 'Limited Markets', 'frequency' => 62],
                    ['problem' => 'High Input Cost', 'frequency' => 58]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Workplace Safety Concerns', 'frequency' => 44],
                    ['problem' => 'Petty Theft', 'frequency' => 21],
                    ['problem' => 'Factory-Related Issues', 'frequency' => 15]
                ]),
                'avg_service_rating_police' => 3.5,
                'avg_service_rating_fire' => 3.3,
                'avg_service_rating_bns' => 3.9,
                'avg_service_rating_water' => 3.2,
                'avg_service_rating_roads' => 2.8,
                'avg_service_rating_clinic' => 3.6,
                'avg_service_rating_market' => 3.4,
                'avg_service_rating_community_center' => 3.5,
                'avg_service_rating_lights' => 3.4,
                'overall_service_satisfaction' => 3.4,
                'percent_available_for_training' => 69,
                'key_feedback_themes' => json_encode(['Worker Protection', 'Better Wages', 'Occupational Safety', 'Market Development', 'Skills Enhancement']),
                'summary_notes' => 'Processing workers concerned about health and safety. Need for quality improvement and better market access.'
            ],
            [
                'total_assessments' => 146,
                'avg_age' => 39.1,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Small Trade/Retail',
                'percent_interested_in_training' => 74,
                'common_desired_trainings' => json_encode(['Business Management', 'Financial Literacy', 'Marketing']),
                'percent_household_member_studying' => 63,
                'percent_interested_in_continuing_studies' => 54,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Public Faucet',
                'most_common_water_source_distance' => 'Within 50 meters',
                'percent_has_own_toilet' => 73,
                'percent_has_electricity' => 85,
                'percent_keeps_animals' => 41,
                'common_health_programs' => json_encode(['Community Health', 'Nutrition Support', 'Family Planning']),
                'percent_benefits_from_health_programs' => 72,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Refrigerator', 'Electric Fan', 'Mobile Phone Charger']),
                'percent_member_of_organization' => 59,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Business Capital Shortage', 'frequency' => 81],
                    ['problem' => 'Family Health Issues', 'frequency' => 58],
                    ['problem' => 'Children Education', 'frequency' => 67]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Hypertension', 'frequency' => 54],
                    ['problem' => 'Stress-Related', 'frequency' => 41],
                    ['problem' => 'Poor Nutrition', 'frequency' => 35]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Expensive Education', 'frequency' => 59],
                    ['problem' => 'Limited Materials', 'frequency' => 51],
                    ['problem' => 'School Distance', 'frequency' => 32]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Unstable Income', 'frequency' => 84],
                    ['problem' => 'Limited Capital', 'frequency' => 79],
                    ['problem' => 'Market Competition', 'frequency' => 56]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Market Location Issues', 'frequency' => 52],
                    ['problem' => 'Insufficient Electricity', 'frequency' => 38],
                    ['problem' => 'Poor Shop Condition', 'frequency' => 44]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'High Goods Cost', 'frequency' => 77],
                    ['problem' => 'Unstable Demand', 'frequency' => 65],
                    ['problem' => 'No Business Registry', 'frequency' => 48]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Theft/Robbery', 'frequency' => 38],
                    ['problem' => 'Debt Collection Risk', 'frequency' => 26],
                    ['problem' => 'Harassment', 'frequency' => 12]
                ]),
                'avg_service_rating_police' => 3.6,
                'avg_service_rating_fire' => 3.2,
                'avg_service_rating_bns' => 3.8,
                'avg_service_rating_water' => 3.3,
                'avg_service_rating_roads' => 3.0,
                'avg_service_rating_clinic' => 3.9,
                'avg_service_rating_market' => 3.5,
                'avg_service_rating_community_center' => 3.6,
                'avg_service_rating_lights' => 3.5,
                'overall_service_satisfaction' => 3.5,
                'percent_available_for_training' => 72,
                'key_feedback_themes' => json_encode(['Capital Access', 'Business Support', 'Financial Literacy', 'Security', 'Market Development']),
                'summary_notes' => 'Small traders need microfinance and business training. Interested in cooperative schemes.'
            ],
            [
                'total_assessments' => 144,
                'avg_age' => 37.3,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Seasonal Employment',
                'percent_interested_in_training' => 69,
                'common_desired_trainings' => json_encode(['Construction Skills', 'Welding', 'Heavy Equipment Operation']),
                'percent_household_member_studying' => 67,
                'percent_interested_in_continuing_studies' => 60,
                'most_common_preferred_time' => 'Afternoon',
                'most_common_preferred_days' => 'Weekdays',
                'most_common_water_source' => 'Deep Well',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 66,
                'percent_has_electricity' => 78,
                'percent_keeps_animals' => 44,
                'common_health_programs' => json_encode(['Health Screening', 'Immunization', 'First Aid Training']),
                'percent_benefits_from_health_programs' => 65,
                'most_common_housing_type' => 'Wood and Mixed',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Radio', 'Mobile Phone', 'Electric Fan']),
                'percent_member_of_organization' => 51,
                'most_common_meeting_frequency' => 'Quarterly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Irregular Income', 'frequency' => 89],
                    ['problem' => 'Healthcare Needs', 'frequency' => 60],
                    ['problem' => 'School Costs', 'frequency' => 72]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Work-Related Injuries', 'frequency' => 47],
                    ['problem' => 'Hypertension', 'frequency' => 49],
                    ['problem' => 'Respiratory Issues', 'frequency' => 38]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'School Discontinuation', 'frequency' => 54],
                    ['problem' => 'Lack of Resources', 'frequency' => 61],
                    ['problem' => 'Transportation Cost', 'frequency' => 39]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Seasonal Work Only', 'frequency' => 92],
                    ['problem' => 'Off-season Hunger', 'frequency' => 64],
                    ['problem' => 'No Skills', 'frequency' => 58]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Worksite Infrastructure', 'frequency' => 49],
                    ['problem' => 'Poor Transportation', 'frequency' => 61],
                    ['problem' => 'Limited Facilities', 'frequency' => 43]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Unstable Income', 'frequency' => 88],
                    ['problem' => 'Debt Accumulation', 'frequency' => 73],
                    ['problem' => 'No Savings Buffer', 'frequency' => 81]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Workplace Accidents', 'frequency' => 42],
                    ['problem' => 'Theft at Worksites', 'frequency' => 22],
                    ['problem' => 'Labor Disputes', 'frequency' => 18]
                ]),
                'avg_service_rating_police' => 3.3,
                'avg_service_rating_fire' => 3.1,
                'avg_service_rating_bns' => 3.5,
                'avg_service_rating_water' => 2.9,
                'avg_service_rating_roads' => 2.6,
                'avg_service_rating_clinic' => 3.5,
                'avg_service_rating_market' => 3.0,
                'avg_service_rating_community_center' => 3.0,
                'avg_service_rating_lights' => 3.0,
                'overall_service_satisfaction' => 3.1,
                'percent_available_for_training' => 65,
                'key_feedback_themes' => json_encode(['Job Stability', 'Skills Training', 'Off-season Support', 'Worker Protection', 'Safety Training']),
                'summary_notes' => 'Seasonal workers face income gaps. Need skills training for year-round employment options.'
            ],
            [
                'total_assessments' => 150,
                'avg_age' => 38.4,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Farming',
                'percent_interested_in_training' => 71,
                'common_desired_trainings' => json_encode(['Vegetable Farming', 'Hog Raising', 'Soil Management']),
                'percent_household_member_studying' => 65,
                'percent_interested_in_continuing_studies' => 56,
                'most_common_preferred_time' => 'Morning',
                'most_common_preferred_days' => 'Weekdays',
                'most_common_water_source' => 'Shallow Well',
                'most_common_water_source_distance' => 'Within 100 meters',
                'percent_has_own_toilet' => 67,
                'percent_has_electricity' => 82,
                'percent_keeps_animals' => 61,
                'common_health_programs' => json_encode(['Immunization', 'Maternal Care', 'Nutrition Program']),
                'percent_benefits_from_health_programs' => 71,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Radio', 'Electric Fan']),
                'percent_member_of_organization' => 62,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Crop Failure Risk', 'frequency' => 74],
                    ['problem' => 'Healthcare Costs', 'frequency' => 58],
                    ['problem' => 'Education Expenses', 'frequency' => 68]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Chemical Exposure', 'frequency' => 42],
                    ['problem' => 'Hypertension', 'frequency' => 51],
                    ['problem' => 'Water-Related Illness', 'frequency' => 31]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Student Dropout', 'frequency' => 46],
                    ['problem' => 'Learning Materials Gap', 'frequency' => 60],
                    ['problem' => 'School Distance', 'frequency' => 34]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Unpredictable Harvest', 'frequency' => 76],
                    ['problem' => 'Low Income', 'frequency' => 83],
                    ['problem' => 'No Alternatives', 'frequency' => 61]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'No Irrigation', 'frequency' => 68],
                    ['problem' => 'Bad Farm Roads', 'frequency' => 67],
                    ['problem' => 'Limited Water Access', 'frequency' => 49]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Low Farm Prices', 'frequency' => 79],
                    ['problem' => 'High Input Cost', 'frequency' => 72],
                    ['problem' => 'Limited Credit', 'frequency' => 70]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Crop Theft', 'frequency' => 29],
                    ['problem' => 'Animal Predation', 'frequency' => 24],
                    ['problem' => 'Land Issues', 'frequency' => 16]
                ]),
                'avg_service_rating_police' => 3.4,
                'avg_service_rating_fire' => 2.9,
                'avg_service_rating_bns' => 3.6,
                'avg_service_rating_water' => 3.1,
                'avg_service_rating_roads' => 2.7,
                'avg_service_rating_clinic' => 3.7,
                'avg_service_rating_market' => 3.2,
                'avg_service_rating_community_center' => 3.3,
                'avg_service_rating_lights' => 3.1,
                'overall_service_satisfaction' => 3.2,
                'percent_available_for_training' => 68,
                'key_feedback_themes' => json_encode(['Irrigation Development', 'Price Support', 'Crop Insurance', 'Market Access', 'Seeds Support']),
                'summary_notes' => 'Farmers need climate-resilient techniques and market support systems.'
            ],
            [
                'total_assessments' => 143,
                'avg_age' => 38.2,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Fishing',
                'percent_interested_in_training' => 66,
                'common_desired_trainings' => json_encode(['Fish Farming', 'Boat Maintenance', 'Net Making']),
                'percent_household_member_studying' => 61,
                'percent_interested_in_continuing_studies' => 53,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Barangay System',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 72,
                'percent_has_electricity' => 80,
                'percent_keeps_animals' => 48,
                'common_health_programs' => json_encode(['Community Health', 'Basic Health', 'Nutrition']),
                'percent_benefits_from_health_programs' => 68,
                'most_common_housing_type' => 'Wood and Nipa',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Radio', 'Mobile Phone', 'Electric Fan']),
                'percent_member_of_organization' => 52,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Fish Catch Variability', 'frequency' => 86],
                    ['problem' => 'Medical Needs', 'frequency' => 59],
                    ['problem' => 'School Expenses', 'frequency' => 66]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Gastrointestinal Issues', 'frequency' => 48],
                    ['problem' => 'Respiratory Infection', 'frequency' => 40],
                    ['problem' => 'Hypertension', 'frequency' => 46]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Dropout Rate', 'frequency' => 51],
                    ['problem' => 'Material Shortage', 'frequency' => 57],
                    ['problem' => 'School Distance', 'frequency' => 36]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Weather Dependent', 'frequency' => 83],
                    ['problem' => 'Market Access', 'frequency' => 62],
                    ['problem' => 'Unstable Income', 'frequency' => 85]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'No Fish Storage', 'frequency' => 71],
                    ['problem' => 'Poor Port Facilities', 'frequency' => 69],
                    ['problem' => 'Bad Roads to Market', 'frequency' => 63]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Low Fish Prices', 'frequency' => 78],
                    ['problem' => 'High Equipment Cost', 'frequency' => 67],
                    ['problem' => 'No Credit Facility', 'frequency' => 69]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Fishing Ground Conflict', 'frequency' => 28],
                    ['problem' => 'Piracy Risk', 'frequency' => 15],
                    ['problem' => 'Theft', 'frequency' => 17]
                ]),
                'avg_service_rating_police' => 3.2,
                'avg_service_rating_fire' => 3.0,
                'avg_service_rating_bns' => 3.3,
                'avg_service_rating_water' => 2.8,
                'avg_service_rating_roads' => 2.5,
                'avg_service_rating_clinic' => 3.6,
                'avg_service_rating_market' => 3.1,
                'avg_service_rating_community_center' => 3.0,
                'avg_service_rating_lights' => 3.0,
                'overall_service_satisfaction' => 3.0,
                'percent_available_for_training' => 62,
                'key_feedback_themes' => json_encode(['Fish Storage', 'Market Cooperation', 'Equipment Support', 'Price Stabilization', 'Fishing Rights']),
                'summary_notes' => 'Fishing communities need cold storage and cooperative marketing systems.'
            ],
            [
                'total_assessments' => 147,
                'avg_age' => 39.0,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'Elementary Graduate',
                'most_common_livelihood' => 'Coconut Fiber Processing',
                'percent_interested_in_training' => 70,
                'common_desired_trainings' => json_encode(['Process Improvement', 'Quality Control', 'Safety Training']),
                'percent_household_member_studying' => 64,
                'percent_interested_in_continuing_studies' => 57,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Deep Well',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 68,
                'percent_has_electricity' => 81,
                'percent_keeps_animals' => 47,
                'common_health_programs' => json_encode(['Occupational Health', 'Safety Training', 'Health Screening']),
                'percent_benefits_from_health_programs' => 66,
                'most_common_housing_type' => 'Mixed Materials',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Fan', 'Mobile Phone Charger']),
                'percent_member_of_organization' => 55,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Processing Work Seasonality', 'frequency' => 75],
                    ['problem' => 'Health Related', 'frequency' => 71],
                    ['problem' => 'Child Education', 'frequency' => 68]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Dust-Related Illness', 'frequency' => 68],
                    ['problem' => 'Respiratory Disease', 'frequency' => 55],
                    ['problem' => 'Skin Condition', 'frequency' => 41]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Children Labor in Processing', 'frequency' => 51],
                    ['problem' => 'Limited Resources', 'frequency' => 56],
                    ['problem' => 'School Availability', 'frequency' => 33]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Work Seasonality', 'frequency' => 74],
                    ['problem' => 'Low Compensation', 'frequency' => 80],
                    ['problem' => 'No Job Security', 'frequency' => 65]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Processing Facility Issues', 'frequency' => 49],
                    ['problem' => 'Water Scarcity', 'frequency' => 45],
                    ['problem' => 'Waste Disposal', 'frequency' => 52]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Fiber Price Volatility', 'frequency' => 71],
                    ['problem' => 'Limited Market Access', 'frequency' => 60],
                    ['problem' => 'Working Capital Shortage', 'frequency' => 63]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Workplace Hazards', 'frequency' => 48],
                    ['problem' => 'Petty Thefts', 'frequency' => 19],
                    ['problem' => 'Disputes', 'frequency' => 13]
                ]),
                'avg_service_rating_police' => 3.5,
                'avg_service_rating_fire' => 3.2,
                'avg_service_rating_bns' => 3.8,
                'avg_service_rating_water' => 3.1,
                'avg_service_rating_roads' => 2.8,
                'avg_service_rating_clinic' => 3.7,
                'avg_service_rating_market' => 3.3,
                'avg_service_rating_community_center' => 3.4,
                'avg_service_rating_lights' => 3.3,
                'overall_service_satisfaction' => 3.3,
                'percent_available_for_training' => 68,
                'key_feedback_themes' => json_encode(['Safety Standards', 'Health Protection', 'Wage Improvement', 'Market Development', 'Skills Development']),
                'summary_notes' => 'Processing workers need health protection systems and occupational safety training.'
            ],
            [
                'total_assessments' => 140,
                'avg_age' => 40.1,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Small Business',
                'percent_interested_in_training' => 75,
                'common_desired_trainings' => json_encode(['Advanced Business', 'Digital Marketing', 'Accounting']),
                'percent_household_member_studying' => 68,
                'percent_interested_in_continuing_studies' => 62,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Public Faucet',
                'most_common_water_source_distance' => 'Within 50 meters',
                'percent_has_own_toilet' => 75,
                'percent_has_electricity' => 86,
                'percent_keeps_animals' => 38,
                'common_health_programs' => json_encode(['Family Health', 'Nutrition Support', 'Wellness Program']),
                'percent_benefits_from_health_programs' => 74,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Refrigerator', 'Fan', 'Microwave']),
                'percent_member_of_organization' => 65,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Business Expansion Cost', 'frequency' => 76],
                    ['problem' => 'Family Health', 'frequency' => 55],
                    ['problem' => 'Education Investment', 'frequency' => 64]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Stress-Related', 'frequency' => 48],
                    ['problem' => 'Hypertension', 'frequency' => 52],
                    ['problem' => 'Insomnia/Fatigue', 'frequency' => 36]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Higher Education Cost', 'frequency' => 61],
                    ['problem' => 'Limited Scholarship', 'frequency' => 49],
                    ['problem' => 'Course Availability', 'frequency' => 32]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Business Growth Limit', 'frequency' => 72],
                    ['problem' => 'Capital Access', 'frequency' => 77],
                    ['problem' => 'Competition', 'frequency' => 54]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Shop Space Limitation', 'frequency' => 46],
                    ['problem' => 'Electricity Supply', 'frequency' => 35],
                    ['problem' => 'Market Facility', 'frequency' => 41]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Rising Commodity Cost', 'frequency' => 74],
                    ['problem' => 'Unstable Consumer Demand', 'frequency' => 58],
                    ['problem' => 'Banking Access', 'frequency' => 52]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Shop Robbery Risk', 'frequency' => 31],
                    ['problem' => 'Bad Debt Risk', 'frequency' => 24],
                    ['problem' => 'Fraud Concern', 'frequency' => 14]
                ]),
                'avg_service_rating_police' => 3.7,
                'avg_service_rating_fire' => 3.3,
                'avg_service_rating_bns' => 3.9,
                'avg_service_rating_water' => 3.4,
                'avg_service_rating_roads' => 3.1,
                'avg_service_rating_clinic' => 4.0,
                'avg_service_rating_market' => 3.6,
                'avg_service_rating_community_center' => 3.7,
                'avg_service_rating_lights' => 3.6,
                'overall_service_satisfaction' => 3.6,
                'percent_available_for_training' => 75,
                'key_feedback_themes' => json_encode(['Access to Credit', 'Business Expansion', 'Advanced Skills', 'Market Growth', 'Employee Training']),
                'summary_notes' => 'Thriving small business sector seeking growth support and advanced business training.'
            ],
            [
                'total_assessments' => 151,
                'avg_age' => 37.6,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Farming and Fishing',
                'percent_interested_in_training' => 72,
                'common_desired_trainings' => json_encode(['Innovative Farming', 'Aquaculture', 'Market Skills']),
                'percent_household_member_studying' => 66,
                'percent_interested_in_continuing_studies' => 58,
                'most_common_preferred_time' => 'Morning',
                'most_common_preferred_days' => 'Weekdays',
                'most_common_water_source' => 'Community Well',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 70,
                'percent_has_electricity' => 81,
                'percent_keeps_animals' => 54,
                'common_health_programs' => json_encode(['Community Health', 'Immunization', 'First Aid']),
                'percent_benefits_from_health_programs' => 70,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Fan', 'Mobile Phone']),
                'percent_member_of_organization' => 60,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Income from Multiple Sources', 'frequency' => 82],
                    ['problem' => 'Health Care Access', 'frequency' => 60],
                    ['problem' => 'Education Fees', 'frequency' => 71]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Work-Related Fatigue', 'frequency' => 50],
                    ['problem' => 'Waterborne Illness', 'frequency' => 43],
                    ['problem' => 'Hypertension', 'frequency' => 47]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Early Dropout', 'frequency' => 49],
                    ['problem' => 'Material Scarcity', 'frequency' => 58],
                    ['problem' => 'Commute Distance', 'frequency' => 35]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Dual Livelihood Strain', 'frequency' => 79],
                    ['problem' => 'Seasonal Volatility', 'frequency' => 76],
                    ['problem' => 'Income Unpredictability', 'frequency' => 87]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Market Access Road', 'frequency' => 65],
                    ['problem' => 'Processing Facilities', 'frequency' => 57],
                    ['problem' => 'Storage Options', 'frequency' => 56]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Product Price Instability', 'frequency' => 77],
                    ['problem' => 'Limited Cash Flow', 'frequency' => 75],
                    ['problem' => 'No Business Savings', 'frequency' => 72]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Theft Risk', 'frequency' => 27],
                    ['problem' => 'Disputes Resolution', 'frequency' => 19],
                    ['problem' => 'Family Safety', 'frequency' => 11]
                ]),
                'avg_service_rating_police' => 3.4,
                'avg_service_rating_fire' => 3.0,
                'avg_service_rating_bns' => 3.6,
                'avg_service_rating_water' => 3.1,
                'avg_service_rating_roads' => 2.8,
                'avg_service_rating_clinic' => 3.8,
                'avg_service_rating_market' => 3.3,
                'avg_service_rating_community_center' => 3.4,
                'avg_service_rating_lights' => 3.2,
                'overall_service_satisfaction' => 3.3,
                'percent_available_for_training' => 70,
                'key_feedback_themes' => json_encode(['Technology Adoption', 'Market Linkage', 'Income Stabilization', 'Infrastructure Support', 'Cooperative Development']),
                'summary_notes' => 'Integrated farming-fishing communities need holistic support systems and better market access.'
            ],
            [
                'total_assessments' => 142,
                'avg_age' => 38.6,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Multiple Sources',
                'percent_interested_in_training' => 73,
                'common_desired_trainings' => json_encode(['Livelihood Diversification', 'Leadership', 'Cooperative Management']),
                'percent_household_member_studying' => 65,
                'percent_interested_in_continuing_studies' => 59,
                'most_common_preferred_time' => 'Evening',
                'most_common_preferred_days' => 'Weekends',
                'most_common_water_source' => 'Mixed Sources',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 71,
                'percent_has_electricity' => 82,
                'percent_keeps_animals' => 50,
                'common_health_programs' => json_encode(['Family Welfare', 'Health Education', 'Maternal Health']),
                'percent_benefits_from_health_programs' => 71,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Fan', 'Refrigerator']),
                'percent_member_of_organization' => 64,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Time Management Stress', 'frequency' => 81],
                    ['problem' => 'Healthcare Access', 'frequency' => 61],
                    ['problem' => 'School Costs', 'frequency' => 70]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Fatigue', 'frequency' => 57],
                    ['problem' => 'Stress Disorders', 'frequency' => 44],
                    ['problem' => 'Chronic Diseases', 'frequency' => 49]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Student Absenteeism', 'frequency' => 45],
                    ['problem' => 'School Supplies Cost', 'frequency' => 59],
                    ['problem' => 'Basic Literacy Gap', 'frequency' => 34]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Work-Life Balance', 'frequency' => 84],
                    ['problem' => 'Income Inconsistency', 'frequency' => 80],
                    ['problem' => 'Skill Gap', 'frequency' => 62]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Market Accessibility', 'frequency' => 61],
                    ['problem' => 'Storage Facilities', 'frequency' => 54],
                    ['problem' => 'Service Facilities', 'frequency' => 47]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Multiple Price Volatility', 'frequency' => 76],
                    ['problem' => 'Income Uncertainty', 'frequency' => 79],
                    ['problem' => 'Limited Capital Access', 'frequency' => 73]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Various Theft Risks', 'frequency' => 28],
                    ['problem' => 'Conflict Resolution', 'frequency' => 20],
                    ['problem' => 'Safety Concerns', 'frequency' => 13]
                ]),
                'avg_service_rating_police' => 3.5,
                'avg_service_rating_fire' => 3.1,
                'avg_service_rating_bns' => 3.7,
                'avg_service_rating_water' => 3.2,
                'avg_service_rating_roads' => 2.9,
                'avg_service_rating_clinic' => 3.8,
                'avg_service_rating_market' => 3.4,
                'avg_service_rating_community_center' => 3.5,
                'avg_service_rating_lights' => 3.3,
                'overall_service_satisfaction' => 3.4,
                'percent_available_for_training' => 71,
                'key_feedback_themes' => json_encode(['Integrated Support System', 'Time Management', 'Income Stability', 'Cooperative Development', 'Technology Integration']),
                'summary_notes' => 'Communities with multiple income sources need integrated and flexible support systems.'
            ],
            [
                'total_assessments' => 154,
                'avg_age' => 37.9,
                'most_common_civil_status' => 'Married',
                'most_common_sex' => 'Female',
                'most_common_religion' => 'Roman Catholic',
                'most_common_educational_attainment' => 'High School Graduate',
                'most_common_livelihood' => 'Agriculture & Related',
                'percent_interested_in_training' => 74,
                'common_desired_trainings' => json_encode(['Modern Farming', 'Value Addition', 'Marketing Skills']),
                'percent_household_member_studying' => 67,
                'percent_interested_in_continuing_studies' => 60,
                'most_common_preferred_time' => 'Morning',
                'most_common_preferred_days' => 'Weekdays',
                'most_common_water_source' => 'Groundwater Wells',
                'most_common_water_source_distance' => '100-200 meters',
                'percent_has_own_toilet' => 72,
                'percent_has_electricity' => 83,
                'percent_keeps_animals' => 57,
                'common_health_programs' => json_encode(['Wellness Initiative', 'Immunization', 'Health Screening']),
                'percent_benefits_from_health_programs' => 72,
                'most_common_housing_type' => 'Concrete Block',
                'most_common_tenure_status' => 'Owned',
                'common_appliances' => json_encode(['Television', 'Fan', 'Radio']),
                'percent_member_of_organization' => 63,
                'most_common_meeting_frequency' => 'Monthly',
                'top_family_problems' => json_encode([
                    ['problem' => 'Agricultural Income', 'frequency' => 80],
                    ['problem' => 'Health Service Access', 'frequency' => 62],
                    ['problem' => 'Education Funding', 'frequency' => 72]
                ]),
                'top_health_problems' => json_encode([
                    ['problem' => 'Pesticide Exposure', 'frequency' => 38],
                    ['problem' => 'Heat-Related Issues', 'frequency' => 41],
                    ['problem' => 'Chronic Conditions', 'frequency' => 50]
                ]),
                'top_education_problems' => json_encode([
                    ['problem' => 'Academic Support', 'frequency' => 46],
                    ['problem' => 'Costs and Finance', 'frequency' => 61],
                    ['problem' => 'Attendance Issues', 'frequency' => 37]
                ]),
                'top_employment_problems' => json_encode([
                    ['problem' => 'Agriculture Dependency', 'frequency' => 75],
                    ['problem' => 'Low Farm Returns', 'frequency' => 82],
                    ['problem' => 'Limited Opportunities', 'frequency' => 64]
                ]),
                'top_infrastructure_problems' => json_encode([
                    ['problem' => 'Irrigation Systems', 'frequency' => 70],
                    ['problem' => 'Road Connectivity', 'frequency' => 68],
                    ['problem' => 'Post-Harvest Facility', 'frequency' => 61]
                ]),
                'top_economy_problems' => json_encode([
                    ['problem' => 'Commodity Prices', 'frequency' => 80],
                    ['problem' => 'Production Costs', 'frequency' => 73],
                    ['problem' => 'Credit Availability', 'frequency' => 71]
                ]),
                'top_security_problems' => json_encode([
                    ['problem' => 'Crop Loss Prevention', 'frequency' => 30],
                    ['problem' => 'Property Protection', 'frequency' => 23],
                    ['problem' => 'Community Issues', 'frequency' => 16]
                ]),
                'avg_service_rating_police' => 3.5,
                'avg_service_rating_fire' => 2.9,
                'avg_service_rating_bns' => 3.7,
                'avg_service_rating_water' => 3.2,
                'avg_service_rating_roads' => 2.8,
                'avg_service_rating_clinic' => 3.8,
                'avg_service_rating_market' => 3.3,
                'avg_service_rating_community_center' => 3.4,
                'avg_service_rating_lights' => 3.2,
                'overall_service_satisfaction' => 3.3,
                'percent_available_for_training' => 72,
                'key_feedback_themes' => json_encode(['Agricultural Innovation', 'Value Chain Development', 'Price Stabilization', 'Infrastructure Investment', 'Farmer Support']),
                'summary_notes' => 'Strong agricultural base with potential for modernization. Communities ready for technology adoption.'
            ]
        ];

        foreach ($assessments as $assessment) {
            CommunityAssessmentSummary::create([
                'community_id' => $community->id,
                'total_assessments' => $assessment['total_assessments'],
                'last_calculated_at' => now(),
                'avg_age' => $assessment['avg_age'],
                'most_common_civil_status' => $assessment['most_common_civil_status'],
                'most_common_sex' => $assessment['most_common_sex'],
                'most_common_religion' => $assessment['most_common_religion'],
                'most_common_educational_attainment' => $assessment['most_common_educational_attainment'],
                'most_common_livelihood' => $assessment['most_common_livelihood'],
                'percent_interested_in_training' => $assessment['percent_interested_in_training'],
                'common_desired_trainings' => $assessment['common_desired_trainings'],
                'percent_household_member_studying' => $assessment['percent_household_member_studying'],
                'percent_interested_in_continuing_studies' => $assessment['percent_interested_in_continuing_studies'],
                'most_common_preferred_time' => $assessment['most_common_preferred_time'],
                'most_common_preferred_days' => $assessment['most_common_preferred_days'],
                'most_common_water_source' => $assessment['most_common_water_source'],
                'most_common_water_source_distance' => $assessment['most_common_water_source_distance'],
                'percent_has_own_toilet' => $assessment['percent_has_own_toilet'],
                'percent_has_electricity' => $assessment['percent_has_electricity'],
                'percent_keeps_animals' => $assessment['percent_keeps_animals'],
                'common_health_programs' => $assessment['common_health_programs'],
                'percent_benefits_from_health_programs' => $assessment['percent_benefits_from_health_programs'],
                'most_common_housing_type' => $assessment['most_common_housing_type'],
                'most_common_tenure_status' => $assessment['most_common_tenure_status'],
                'common_appliances' => $assessment['common_appliances'],
                'percent_member_of_organization' => $assessment['percent_member_of_organization'],
                'most_common_meeting_frequency' => $assessment['most_common_meeting_frequency'],
                'top_family_problems' => $assessment['top_family_problems'],
                'top_health_problems' => $assessment['top_health_problems'],
                'top_education_problems' => $assessment['top_education_problems'],
                'top_employment_problems' => $assessment['top_employment_problems'],
                'top_infrastructure_problems' => $assessment['top_infrastructure_problems'],
                'top_economy_problems' => $assessment['top_economy_problems'],
                'top_security_problems' => $assessment['top_security_problems'],
                'avg_service_rating_police' => $assessment['avg_service_rating_police'],
                'avg_service_rating_fire' => $assessment['avg_service_rating_fire'],
                'avg_service_rating_bns' => $assessment['avg_service_rating_bns'],
                'avg_service_rating_water' => $assessment['avg_service_rating_water'],
                'avg_service_rating_roads' => $assessment['avg_service_rating_roads'],
                'avg_service_rating_clinic' => $assessment['avg_service_rating_clinic'],
                'avg_service_rating_market' => $assessment['avg_service_rating_market'],
                'avg_service_rating_community_center' => $assessment['avg_service_rating_community_center'],
                'avg_service_rating_lights' => $assessment['avg_service_rating_lights'],
                'overall_service_satisfaction' => $assessment['overall_service_satisfaction'],
                'percent_available_for_training' => $assessment['percent_available_for_training'],
                'key_feedback_themes' => $assessment['key_feedback_themes'],
                'summary_notes' => $assessment['summary_notes'],
            ]);
        }
    }
}
