<?php

namespace Database\Seeders;

use App\Models\ExtensionProgram;
use App\Models\ProgramLogicModel;
use App\Models\ProgramBaseline;
use App\Models\ProgramActivity;
use App\Models\ProgramOutput;
use App\Models\Beneficiary;
use App\Models\User;
use App\Models\Community;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaraBasaTutoringProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the admin user
        $admin = User::where('email', 'cesoadmin@lnu.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run DatabaseSeeder first.');
            return;
        }

        // Get 4 random communities (Tacloban City barangays)
        $communities = Community::whereIn('name', ['North Pabahay', 'San Jose', 'Diit', 'Magallanes'])
            ->get();

        if ($communities->count() < 4) {
            $this->command->error('Required communities not found. Please ensure CommunitySeeder has run first.');
            return;
        }

        // 1. Create the Extension Program
        $program = ExtensionProgram::create([
            'title' => 'Tara Basa Tutoring Program',
            'description' => 'A comprehensive tutoring and literacy enhancement program designed to provide quality educational support to out-of-school youth and struggling students. The program focuses on Filipino language proficiency, basic literacy skills, and confidence building through personalized and group tutoring sessions.',
            'goals' => 'Improve literacy and language proficiency among youth and students in marginalized communities',
            'objectives' => 'To provide 175 students with quality tutoring services; To increase Filipino language proficiency by 40%; To develop critical thinking and communication skills; To build student confidence and academic self-efficacy',
            'status' => 'active',
            'allocated_budget' => 2500000,
            'planned_start_date' => Carbon::create(2026, 3, 25),
            'planned_end_date' => Carbon::create(2026, 4, 14),
            'beneficiary_categories' => ['out-of-school-youth', 'struggling-students', 'indigenous-peoples'],
            'related_communities' => $communities->pluck('id')->toArray(),
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->command->info("✓ Extension Program created: {$program->title}");

        // 2. Create Program Logic Model
        $logicModel = ProgramLogicModel::create([
            'program_id' => $program->id,
            'inputs' => [
                'personnel' => '3 Master Tutors, 12 Peer Tutors, 1 Program Coordinator',
                'partners' => 'Department of Education, Local Government Unit, Barangay Government, Community Organizations',
                'resources' => '500 sets of learning materials, 175 notebooks and workbooks, 10 laptops with educational software, Tutoring center equipment and furniture',
            ],
            'activities' => [
                ['title' => 'Orientation and Assessment', 'description' => 'Student intake and baseline literacy assessment'],
                ['title' => 'Group Tutoring Sessions', 'description' => 'Weekly group tutoring in Filipino language and basic literacy'],
                ['title' => 'One-on-One Mentoring', 'description' => 'Individual tutoring sessions for struggling students'],
                ['title' => 'Community Reading Program', 'description' => 'Monthly reading sessions and book club activities'],
                ['title' => 'Parent Awareness Workshop', 'description' => 'Training for parents on supporting student learning'],
            ],
            'outputs' => [
                'trainees' => '175 students provided with tutoring services',
                'materials' => '500 sets of learning materials and 175 workbooks distributed',
                'services' => '60 tutoring sessions with 10-15 students per session, 175 individual mentoring sessions',
            ],
            'outcomes' => [
                'Improved Filipino language proficiency in 70% of students',
                'Enhanced reading comprehension and writing skills',
                'Increased student confidence and academic motivation',
                'Better school attendance and academic performance',
                'Developed peer learning and mentoring relationships',
            ],
            'impacts' => [
                'Reduced educational inequality in marginalized communities',
                'Improved youth literacy and foundational skills',
                'Strengthened community support for education',
                'Contribution to SDG 4: Quality Education',
            ],
            'status' => 'active',
            'notes' => 'Comprehensive literacy support program with strong community engagement',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->command->info("✓ Logic Model created");

        // 3. Create Beneficiaries (175 students distributed across 4 communities)
        $beneficiariesPerCommunity = 44; // 44 + 44 + 44 + 43 = 175
        $beneficiaryIds = [];
        $firstNames = ['Maria', 'Juan', 'Rosa', 'Pedro', 'Ana', 'Carlos', 'Francisca', 'Miguel', 'Josefa', 'Antonio', 'Isabel', 'Manuel', 'Elena', 'Jose', 'Carmen', 'Luis', 'Lucia', 'Fernando', 'Margot', 'Diego'];
        $lastNames = ['Santos', 'Cruz', 'Garcia', 'Reyes', 'Lopez', 'Fernandez', 'Torres', 'Morales', 'Gutierrez', 'Romero', 'Castillo', 'Ramirez', 'Vargas', 'Mendoza', 'Navarro', 'Pacheco', 'Robles', 'Soliz', 'Herrera', 'Vega'];

        $count = 0;
        foreach ($communities as $index => $community) {
            $communityCount = ($index < 3) ? $beneficiariesPerCommunity : 43;
            
            for ($i = 0; $i < $communityCount; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $dob = Carbon::create(2000 + rand(4, 12), rand(1, 12), rand(1, 28));
                
                $beneficiary = Beneficiary::create([
                    'community_id' => $community->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'date_of_birth' => $dob,
                    'age' => Carbon::now()->year - $dob->year,
                    'gender' => rand(0, 1) ? 'Male' : 'Female',
                    'address' => "{$community->name}, {$community->municipality}",
                    'phone' => '09' . rand(100000000, 999999999),
                    'barangay' => $community->name,
                    'municipality' => $community->municipality,
                    'province' => $community->province,
                    'educational_attainment' => ['Elementary', 'High School', 'College'][rand(0, 2)],
                    'beneficiary_category' => 'student',
                    'status' => 'active',
                    'program_ids' => [$program->id],
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
                
                // Attach to program through pivot table
                $beneficiary->programs()->attach($program->id, [
                    'enrollment_status' => 'enrolled',
                    'enrollment_date' => Carbon::create(2026, 3, 25),
                    'participation_rate' => 100,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
                
                $beneficiaryIds[] = $beneficiary->id;
                $count++;
            }
        }

        $this->command->info("✓ Created $count beneficiaries across 4 communities");

        // 4. Create Program Baseline Assessment
        $baseline = ProgramBaseline::create([
            'program_id' => $program->id,
            'community_id' => $communities[0]->id, // First community
            'baseline_assessment_date' => Carbon::create(2026, 3, 20),
            'target_beneficiaries_count' => 175,
            'target_literacy_level' => 2, // Scale 1-5, starting at basic level
            'target_average_income' => 8000,
            'target_skills' => ['Reading', 'Writing', 'Filipino Language Proficiency', 'Critical Thinking'],
            'status' => 'approved',
            'notes' => 'Baseline assessment shows significant literacy gaps in target communities. 60% of students cannot read proficiently. Program intervention is critical.',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->command->info("✓ Program Baseline created");

        // 5. Create Program Activities
        $activities = [];
        
        // Week 1 - March 25-29
        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Student Intake and Orientation - Batch 1',
            'description' => 'Registration and orientation of students from North Pabahay',
            'activity_date' => Carbon::create(2026, 3, 25),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Tutoring Center - North Pabahay',
            'facilitators' => ['Program Coordinator'],
            'target_attendees' => 44,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Student Intake and Orientation - Batch 2',
            'description' => 'Registration and orientation of students from San Jose',
            'activity_date' => Carbon::create(2026, 3, 26),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Tutoring Center - San Jose',
            'facilitators' => ['Program Coordinator'],
            'target_attendees' => 44,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Week 2 - Tutoring sessions
        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Filipino Language Group Tutoring - Session 1',
            'description' => 'First group tutoring session focusing on phonics and basic reading comprehension',
            'activity_date' => Carbon::create(2026, 3, 30),
            'start_time' => '13:00',
            'end_time' => '16:00',
            'location' => 'Tutoring Center - Multiple Branches',
            'facilitators' => ['Master Tutor 1', 'Master Tutor 2', 'Master Tutor 3'],
            'target_attendees' => 88,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Individual Mentoring Sessions',
            'description' => 'One-on-one mentoring for struggling students to provide personalized support',
            'activity_date' => Carbon::create(2026, 4, 1),
            'start_time' => '09:00',
            'end_time' => '11:30',
            'location' => 'Tutoring Center - Multiple Branches',
            'facilitators' => ['Peer Tutor Group'],
            'target_attendees' => 50,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Community Reading Program - Book Club Launch',
            'description' => 'Launch of monthly community reading sessions and book club activities',
            'activity_date' => Carbon::create(2026, 4, 5),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'location' => 'Community Centers',
            'facilitators' => ['Master Tutors'],
            'target_attendees' => 75,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $activities[] = ProgramActivity::create([
            'program_id' => $program->id,
            'activity_name' => 'Parent Awareness Workshop',
            'description' => 'Training for parents on supporting student learning at home',
            'activity_date' => Carbon::create(2026, 4, 10),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'location' => 'Barangay Halls',
            'facilitators' => ['Program Coordinator', 'Master Tutors'],
            'target_attendees' => 120,
            'status' => 'completed',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->command->info("✓ Created " . count($activities) . " activities");

        // 6. Create Program Outputs
        $outputs = [];

        // Output 1: Baseline Literacy Assessment
        $outputs[] = ProgramOutput::create([
            'program_id' => $program->id,
            'activity_id' => $activities[0]->id,
            'output_type' => 'assessment',
            'output_title' => 'Baseline Literacy Assessment Completed',
            'description' => 'Baseline assessment conducted for all 175 registered students to determine literacy levels and learning needs',
            'quantity' => 175,
            'unit' => 'students',
            'beneficiaries_reached' => 175,
            'output_date' => Carbon::create(2026, 3, 27),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'outcomes' => 'Assessment results show 60% of students at basic literacy level, 30% at pre-reader level, 10% at intermediate level',
            'status' => 'completed',
            'notes' => 'Comprehensive baseline conducted using standardized literacy assessment tools',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Output 2: Learning Materials Distributed
        for ($batch = 0; $batch < 5; $batch++) {
            $outputs[] = ProgramOutput::create([
                'program_id' => $program->id,
                'activity_id' => $activities[0]->id,
                'output_type' => 'materials',
                'output_title' => 'Learning Materials Distribution - Batch ' . ($batch + 1),
                'description' => 'Distribution of workbooks, notebooks, and learning materials to tutoring students',
                'quantity' => 35,
                'unit' => 'sets',
                'beneficiaries_reached' => 35,
                'output_date' => Carbon::create(2026, 3, 25)->addDays($batch + 1),
                'outcomes' => 'Students received complete learning material packages including workbooks, pens, and reference materials',
                'status' => 'completed',
                'notes' => 'Distribution included 35 workbooks, 35 notebooks, and basic school supplies',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Output 3: Tutoring Sessions
        for ($week = 1; $week <= 3; $week++) {
            $outputs[] = ProgramOutput::create([
                'program_id' => $program->id,
                'activity_id' => $activities[2]->id,
                'output_type' => 'training',
                'output_title' => 'Filipino Group Tutoring Session - Week ' . $week,
                'description' => 'Weekly group tutoring session focusing on reading, writing, and language skills',
                'quantity' => $week,
                'unit' => 'sessions',
                'beneficiaries_reached' => 88,
                'output_date' => Carbon::create(2026, 3, 30)->addDays(($week - 1) * 7),
                'start_time' => '13:00',
                'end_time' => '16:00',
                'outcomes' => 'Students completed lessons on: Week 1 - Phonics & Sound Recognition; Week 2 - Word Building & Sight Words; Week 3 - Sentences & Comprehension',
                'status' => 'completed',
                'notes' => 'Sessions conducted with 10-15 students per tutor, using interactive learning methods',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        // Output 4: Individual Mentoring
        $outputs[] = ProgramOutput::create([
            'program_id' => $program->id,
            'activity_id' => $activities[3]->id,
            'output_type' => 'mentoring',
            'output_title' => 'Individual Mentoring Sessions',
            'description' => 'Personalized mentoring sessions provided to 50 struggling students',
            'quantity' => 50,
            'unit' => 'mentoring sessions',
            'beneficiaries_reached' => 50,
            'output_date' => Carbon::create(2026, 4, 1),
            'start_time' => '09:00',
            'end_time' => '11:30',
            'outcomes' => 'Students showing improvement in focus areas, with 80% demonstrating progress in their target literacy skills',
            'status' => 'completed',
            'notes' => '50 students received customized mentoring addressing individual learning gaps',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Output 5: Community Reading Program Launch
        $outputs[] = ProgramOutput::create([
            'program_id' => $program->id,
            'activity_id' => $activities[4]->id,
            'output_type' => 'mentoring',
            'output_title' => 'Community Reading Program Launch',
            'description' => 'Launch of monthly community reading sessions and book club activities',
            'quantity' => 75,
            'unit' => 'participants',
            'beneficiaries_reached' => 75,
            'output_date' => Carbon::create(2026, 4, 5),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'outcomes' => '75 students engaged in reading activities with increased enthusiasm for reading',
            'status' => 'completed',
            'notes' => 'Book club established with peer leaders identified for ongoing program sustainability',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Output 6: Parent Awareness Workshop
        $outputs[] = ProgramOutput::create([
            'program_id' => $program->id,
            'activity_id' => $activities[5]->id,
            'output_type' => 'training',
            'output_title' => 'Parent Awareness and Support Workshop',
            'description' => 'Workshop conducted for parents on supporting student learning and literacy development at home',
            'quantity' => 120,
            'unit' => 'parents',
            'beneficiaries_reached' => 120,
            'output_date' => Carbon::create(2026, 4, 10),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'outcomes' => '120 parents equipped with strategies to support their children\'s learning, with 95% expressing commitment to implement home support',
            'status' => 'completed',
            'notes' => 'Parents received materials on effective home learning strategies and were engaged in peer learning',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->command->info("✓ Created " . count($outputs) . " program outputs");

        // Final Summary
        $this->command->info("\n" . str_repeat("=", 60));
        $this->command->info("TARA BASA TUTORING PROGRAM SEEDING COMPLETE!");
        $this->command->info(str_repeat("=", 60));
        $this->command->line("Program Details:");
        $this->command->line("  • Title: {$program->title}");
        $this->command->line("  • Budget: ₱" . number_format($program->allocated_budget));
        $this->command->line("  • Duration: {$program->planned_start_date->format('M d, Y')} - {$program->planned_end_date->format('M d, Y')}");
        $this->command->line("  • Beneficiaries: " . count($beneficiaryIds) . " students");
        $this->command->line("  • Communities: " . $communities->pluck('name')->join(', '));
        $this->command->line("\nSeeded Components:");
        $this->command->line("  ✓ Extension Program");
        $this->command->line("  ✓ Program Logic Model");
        $this->command->line("  ✓ " . count($beneficiaryIds) . " Beneficiaries");
        $this->command->line("  ✓ Program Baseline Assessment");
        $this->command->line("  ✓ " . count($activities) . " Activities");
        $this->command->line("  ✓ " . count($outputs) . " Outputs");
        $this->command->info(str_repeat("=", 60) . "\n");
    }
}
