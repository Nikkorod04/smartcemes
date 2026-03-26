<?php

namespace Database\Seeders;

use App\Models\ExtensionProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExtensionProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user (created by DatabaseSeeder)
        $admin = User::where('email', 'cesoadmin@lnu.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run DatabaseSeeder first.');
            return;
        }

        $programs = $this->generatePrograms($admin);
        
        foreach ($programs as $program) {
            ExtensionProgram::create($program);
        }
        
        $this->command->info('50 extension programs seeded successfully!');
    }

    private function generatePrograms($admin): array
    {
        $statuses = ['active', 'planning', 'completed', 'inactive'];
        $program_types = [
            'Agricultural Skills Training', 'Health & Nutrition Campaign', 'Livelihood Development Program', 'Youth Empowerment Initiative',
            'Environmental Conservation', 'Education Support Program', 'Community Infrastructure', 'Disaster Risk Reduction Training',
            'Water & Sanitation Program', 'Women Empowerment Project', 'Children Development Program', 'Livelihood for PWD',
            'Climate Smart Agriculture', 'Fishing Community Development', 'Food Processing Livelihood', 'Handicrafts & Weaving Project',
            'Public Health Awareness', 'Maternal Health Program', 'Senior Citizens Support', 'WASH Program',
            'Barangay Road Improvement', 'Water System Installation', 'School Facility Enhancement', 'Learning Support Program',
            'Cooperative Development', 'Microfinance Access Program', 'Business Startup Training', 'Market Access Initiative',
            'Farm-to-Market Road', 'Irrigation System Development', 'Organic Farming Project', 'Livestock Production Training',
            'Fisheries Management', 'Aquaculture Development', 'Forest Protection Program', 'Mangrove Restoration',
            'Renewable Energy Project', 'Waste Management Program', 'Community Health Post', 'Nutrition Program for Children',
            'Drug Abuse Prevention', 'Mental Health Support', 'Gender-Based Violence Prevention', 'Child Labor Elimination',
            'Early Childhood Development', 'Adult Literacy Program', 'Skills Training Center', 'Job Placement Support',
        ];

        $descriptions = [
            'Comprehensive program to improve productivity and introduce modern techniques while building farmer capacity and cooperatives.',
            'Initiative promoting health practices, nutrition education, and preventive health measures including maternal and child health.',
            'Program creating income opportunities through skills training in food processing, handicrafts, and business management.',
            'Program developing youth leadership, entrepreneurial skills, and civic engagement through mentoring and training.',
            'Program focused on sustainable resource management and building community climate resilience.',
            'Educational initiative providing scholarships, materials, facility improvement, and early childhood development.',
            'Infrastructure improvement project for facilities, water access, sanitation, and public spaces.',
            'Training program building community capacity in disaster risk reduction and emergency response.',
            'Program ensuring access to safe water and sanitation facilities with hygiene promotion.',
            'Program empowering women through skills training, leadership development, and economic opportunities.',
            'Program supporting child development through educational and health interventions.',
            'Livelihood program providing skills training and business support for persons with disabilities.',
            'Training program promoting climate-smart agricultural practices for sustainable farming.',
            'Program developing fishing communities through sustainable practices and livelihood improvements.',
            'Program teaching food processing skills to add value to agricultural products.',
            'Program training women in traditional and modern handicraft production.',
            'Public health awareness campaign promoting healthy lifestyles and disease prevention.',
            'Program supporting maternal health services and reducing maternal mortality.',
            'Support program for senior citizens including health, social services, and social security linkages.',
            'Water, Sanitation and Hygiene program ensuring community access to clean water.',
            'Road improvement project connecting barangay to markets and services.',
            'Water system installation ensuring reliable water supply to communities.',
            'School facility enhancement including renovation, equipment, and learning materials.',
            'Learning support including remedial classes, tutoring, and mentoring programs.',
            'Program supporting establishment and management of community cooperatives.',
            'Program providing microfinance access to entrepreneurs and farmers.',
            'Training program for small business startup and management.',
            'Program linking farmers and producers directly to market opportunities.',
            'Farm-to-market road construction facilitating agricultural produce transportation.',
            'Irrigation system development improving agricultural water management.',
            'Program promoting organic farming methods and certification.',
            'Training program in livestock production and veterinary care.',
            'Sustainable fisheries management program promoting responsible fishing.',
            'Aquaculture development program including fish and shrimp farming training.',
            'Forest protection program preventing illegal logging and promoting reforestation.',
            'Program restoring mangrove forests for coastal protection and biodiversity.',
            'Renewable energy project installing solar or wind systems.',
            'Waste management program promoting segregation, recycling, and composting.',
            'Community health post establishment providing primary health services.',
            'Nutrition program for children ensuring proper diet and micronutrient intake.',
            'Drug abuse prevention program especially targeting youth.',
            'Mental health support program addressing psychosocial needs.',
            'Program preventing gender-based violence and supporting victims.',
            'Program eliminating child labor and ensuring child protection.',
            'Early childhood development program for children ages 0-5.',
            'Adult literacy program enabling basic reading and writing skills.',
            'Skills training center offering various vocational courses.',
            'Job placement support program linking skilled workers to employers.',
        ];

        $activities_list = [
            ['Training workshops', 'Farm visits', 'Input distribution', 'Cooperative building'],
            ['Health talks', 'Medical missions', 'Nutrition classes', 'Health screening'],
            ['Skills training', 'Business planning', 'Capital provision', 'Market linkage'],
            ['Leadership seminar', 'Mentoring', 'Community service', 'Youth organization'],
            ['Tree planting', 'Watershed management', 'Environmental monitoring', 'Community engagement'],
            ['Scholarships', 'Material distribution', 'Facility improvement', 'Parent engagement'],
            ['Infrastructure design', 'Construction', 'Community mobilization', 'Training'],
            ['Disaster drills', 'Risk assessment', 'Preparedness training', 'Response planning'],
            ['Water system improvement', 'Hygiene training', 'Sanitation facilities', 'Health promotion'],
            ['Skills training', 'Leadership development', 'Economic support', 'Organizational development'],
            ['Educational support', 'Health services', 'Nutrition programs', 'Protection initiatives'],
            ['Skills training', 'Livelihood support', 'Mentoring', 'Market access'],
            ['Farmer training', 'Demonstration farms', 'Input support', 'Marketing assistance'],
            ['Fishing gear improvement', 'Sustainable practices', 'Market linkage', 'Cooperative building'],
            ['Processing training', 'Equipment provision', 'Product development', 'Packaging design'],
            ['Skills training', 'Equipment provision', 'Market linkage', 'Quality improvement'],
            ['Awareness campaigns', 'Community education', 'Visual materials', 'Regular communication'],
            ['Prenatal services', 'Safe delivery', 'Postnatal care', 'Family planning'],
            ['Health services', 'Social assistance', 'Livelihood support', 'Community engagement'],
            ['System installation', 'Maintenance training', 'Hygiene education', 'Monitoring'],
            ['Survey and design', 'Construction', 'Community labor', 'Maintenance training'],
            ['System design', 'Installation', 'Maintenance training', 'User management'],
            ['Needs assessment', 'Renovation', 'Equipment procurement', 'Staff training'],
            ['Tutoring sessions', 'Mentoring', 'Study groups', 'Progress monitoring'],
            ['Cooperative registration', 'Management training', 'Accounting', 'Marketing'],
            ['Loan processing', 'Business training', 'Monitoring', 'Networking'],
            ['Business planning', 'Basic accounting', 'Marketing training', 'Startup support'],
            ['Buyer identification', 'Logistics support', 'Quality assurance', 'Price negotiation'],
            ['Road survey', 'Construction', 'Community labor', 'Maintenance planning'],
            ['System design', 'Construction', 'Maintenance training', 'User fee management'],
            ['Farmer training', 'Input support', 'Certification assistance', 'Market linkage'],
            ['Training workshops', 'Veterinary services', 'Feed provision', 'Marketing support'],
            ['Monitoring program', 'Livelihood training', 'Sustainable practices', 'Resource management'],
            ['Training workshops', 'Hatchery establishment', 'Feed provision', 'Marketing training'],
            ['Reforestation drives', 'Environmental monitoring', 'Community engagement', 'Livelihood support'],
            ['Mangrove planting', 'Coastal protection', 'Biodiversity monitoring', 'Livelihood activities'],
            ['Installation', 'Maintenance training', 'User management', 'Community benefit'],
            ['Segregation training', 'Composting facility', 'Recycling program', 'Livelihood support'],
            ['Infrastructure setup', 'Staff training', 'Equipment provision', 'Supplies management'],
            ['Nutrition training', 'Food provision', 'Mother support groups', 'Health monitoring'],
            ['Awareness campaign', 'Youth counseling', 'Referral services', 'Community mobilization'],
            ['Counseling services', 'Community support', 'Referral linkage', 'Support group formation'],
            ['Awareness campaign', 'Victim support', 'Community mobilization', 'Referral services'],
            ['Child protection', 'Education support', 'Community mobilization', 'Livelihood assistance'],
            ['Playgroup activities', 'Parent education', 'Health services', 'Nutrition support'],
            ['Literacy classes', 'Tutor training', 'Learning materials', 'Community mobilization'],
            ['Curriculum development', 'Instructor training', 'Equipment provision', 'Skills certification'],
            ['Skills assessment', 'Job matching', 'Employer linkage', 'Placement support'],
        ];

        $programs = [];
        for ($i = 1; $i <= 50; $i++) {
            $program_index = ($i - 1) % count($program_types);
            $description_index = ($i - 1) % count($descriptions);
            $activities_index = min($i - 1, count($activities_list) - 1);
            
            $status = $statuses[($i - 1) % count($statuses)];
            
            // Calculate dates based on status
            if ($status === 'active') {
                $months_ago = rand(1, 6);
                $created = Carbon::now()->subMonths($months_ago);
                $start = Clone($created);
                $end = Carbon::now()->addMonths(rand(1, 8));
            } elseif ($status === 'planning') {
                $created = Carbon::now()->subMonths(rand(0, 2));
                $start = Carbon::now()->addMonths(rand(1, 4));
                $end = $start->clone()->addMonths(rand(6, 12));
            } elseif ($status === 'completed') {
                $months_ago = rand(6, 24);
                $created = Carbon::now()->subMonths($months_ago);
                $start = $created->clone();
                $end = $created->clone()->addMonths(rand(3, 12));
            } else { // inactive
                $months_ago = rand(6, 18);
                $created = Carbon::now()->subMonths($months_ago);
                $start = $created->clone();
                $end = $created->clone()->addMonths(rand(2, 6));
            }
            
            // Random beneficiary count
            $beneficiary_count = [50, 75, 100, 120, 150, 175, 200, 250, 300, 350, 400, 450, 500];
            $target_beneficiaries = $beneficiary_count[array_rand($beneficiary_count)];
            
            // Random budget
            $budget = rand(10, 50) * 10000;
            
            // Random communities (1-3 per program)
            $community_count = rand(1, min(3, 34)); // at most 3 communities
            $communities = [];
            for ($c = 0; $c < $community_count; $c++) {
                $communities[] = rand(1, 34);
            }
            $communities = array_unique($communities);
            
            // Random categories
            $all_categories = ['farmers', 'fishermen', 'women', 'youth', 'seniors', 'children', 'students', 'general', 'PWD'];
            $category_count = rand(1, 3);
            $categories = [];
            for ($cat = 0; $cat < $category_count; $cat++) {
                $categories[] = $all_categories[array_rand($all_categories)];
            }
            $categories = array_unique($categories);
            
            // Common partner organizations
            $partner_pools = [
                ['Department of Agriculture', 'Local Farmers Cooperative', 'Dumaguete Agriculture Association'],
                ['DOH', 'Rural Health Unit', 'Philippine Red Cross', 'LNU Medical Center'],
                ['TESDA', 'Women Cooperative', 'Microfinance Partners', 'Business Development Center'],
                ['Department of Education', 'Schools Division Office', 'Local Schools'],
                ['DENR', 'Environmental Advocates', 'Climate Action Network'],
                ['DPWH', 'LGU', 'Barangay Council', 'Engineering Contractors'],
                ['OCD', 'Philippine Disaster Resilience Foundation', 'Local Government Unit'],
                ['NGO Partners', 'Community Organizations', 'Civil Society Groups'],
            ];
            $partner_pool = $partner_pools[array_rand($partner_pools)];
            $partner_count = rand(2, 4);
            $partners = array_slice($partner_pool, 0, $partner_count);
            
            $programs[] = [
                'title' => $program_types[$program_index],
                'description' => $descriptions[$description_index],
                'status' => $status,
                'notes' => $this->generateNotes($status),
                'cover_image' => 'program.png',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => $created,
                'planned_start_date' => $start->toDateString(),
                'planned_end_date' => $end->toDateString(),
                'target_beneficiaries' => $target_beneficiaries,
                'beneficiary_categories' => json_encode(array_values($categories)),
                'allocated_budget' => (float) $budget,
                'program_lead_id' => $admin->name,
                'related_communities' => json_encode(array_values($communities)),
            ];
        }
        
        return $programs;
    }

    private function generateNotes($status): string
    {
        $notes_templates = [
            'active' => [
                'Program progressing well. Community engagement strong.',
                'Monthly monitoring conducted. Beneficiaries satisfied with implementation.',
                'Activities on schedule. Good partnership with stakeholders.',
                'High participation rate. Program objectives being achieved.',
                'Regular communication maintained with community. Progress positive.',
            ],
            'planning' => [
                'Scheduled to launch next quarter. Community consultations ongoing.',
                'Planning phase completed. Resource mobilization underway.',
                'Community commitments confirmed. Implementation timeline prepared.',
                'Pre-implementation activities ongoing. Cost estimates finalized.',
                'Partnership agreements being finalized. Training of trainers scheduled.',
            ],
            'completed' => [
                'Successfully completed. All outputs delivered. Community satisfied.',
                'Program concluded with positive results. Sustainability mechanisms established.',
                'Objectives achieved. Community taking over program ownership.',
                'Final evaluation completed. Impact documented. Best practices documented.',
                'Program closure completed. Beneficiaries capacitated for continuity.',
            ],
            'inactive' => [
                'Program suspended pending funding. Community support maintained.',
                'Temporarily halted due to external factors. Re-activation planned.',
                'Program on standby. Community interest remains high.',
                'Budget constraints. Alternative funding sources being explored.',
                'Implementation challenges. Technical assessment completed. Ready for restart.',
            ],
        ];
        
        $current_notes = $notes_templates[$status] ?? ['No notes available'];
        return $current_notes[array_rand($current_notes)];
    }
}
