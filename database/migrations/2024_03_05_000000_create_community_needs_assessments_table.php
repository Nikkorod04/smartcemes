<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('community_needs_assessments', function (Blueprint $table) {
            // Primary & Foreign Keys
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Secretary who entered data
            $table->unsignedBigInteger('community_id')->nullable();
            
            // SECTION I - Identifying Information
            $table->string('respondent_first_name')->nullable();
            $table->string('respondent_middle_name')->nullable();
            $table->string('respondent_last_name')->nullable();
            $table->integer('age')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed', 'Separated', ''])->nullable();
            $table->enum('sex', ['Male', 'Female', 'Other', ''])->nullable();
            $table->string('religion')->nullable();
            $table->enum('educational_attainment', ['None', 'Elementary', 'High School', 'Vocational', 'College', 'Post-Graduate', ''])->nullable();

            // SECTION II - Family Composition (stored as JSON)
            $table->json('family_members')->nullable()->comment('Array of family member objects with firstName, middleName, lastName, age, sex, education, employment');
            
            // SECTION III - Economic Aspect
            $table->string('livelihoods')->nullable();
            $table->enum('interested_in_training', ['yes', 'no', ''])->nullable();
            $table->text('desired_training')->nullable();
            
            // SECTION IV - Educational Aspect
            $table->text('barangay_facilities')->nullable();
            $table->enum('household_member_studying', ['yes', 'no', ''])->nullable();
            $table->enum('interested_in_continuing_studies', ['yes', 'no', ''])->nullable();
            $table->text('areas_of_interest')->nullable();
            $table->enum('preferred_time', ['Morning (8:00-12:00)', 'Afternoon (1:30-5:00)', ''])->nullable();
            $table->text('preferred_days')->nullable();
            
            // SECTION V - Health, Sanitation, Environmental
            $table->text('common_illnesses')->nullable();
            $table->text('action_when_sick')->nullable();
            $table->text('barangay_medical_supplies')->nullable();
            $table->enum('has_barangay_health_programs', ['yes', 'no', ''])->nullable();
            $table->enum('benefits_from_programs', ['yes', 'no', ''])->nullable();
            $table->text('programs_benefited')->nullable();
            $table->string('water_source')->nullable();
            $table->enum('water_source_distance', ['Just outside', '250 meters away', 'No idea', ''])->nullable();
            $table->text('garbage_disposal')->nullable();
            $table->enum('has_own_toilet', ['yes', 'no', ''])->nullable();
            $table->text('toilet_type')->nullable();
            $table->enum('keeps_animals', ['yes', 'no', ''])->nullable();
            $table->text('animals_kept')->nullable();
            
            // SECTION VI - Housing and Basic Amenities
            $table->string('housing_type')->nullable();
            $table->text('tenure_status')->nullable();
            $table->enum('has_electricity', ['yes', 'no', ''])->nullable();
            $table->text('light_source_no_power')->nullable();
            $table->text('appliances')->nullable();
            
            // SECTION VII - Recreational Facilities
            $table->text('barangay_recreational_facilities')->nullable();
            $table->text('use_of_free_time')->nullable();
            $table->enum('member_of_organization', ['yes', 'no', ''])->nullable();
            $table->text('group_type')->nullable();
            $table->enum('meeting_frequency', ['Weekly', 'Monthly', 'Twice a month', 'Yearly', ''])->nullable();

            // SECTION VIII - Other Needs & Problems
            $table->text('problems_family')->nullable();
            $table->text('problems_health')->nullable();
            $table->text('problems_education')->nullable();
            $table->text('problems_employment')->nullable();
            $table->text('problems_infrastructure')->nullable();
            $table->text('problems_economy')->nullable();
            $table->text('problems_security')->nullable();
            
            // SECTION IX - Summary & Barangay Service Ratings
            $table->tinyInteger('barangay_service_rating_police')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_fire')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_bns')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_water')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_roads')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_clinic')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_market')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_community_center')->nullable()->comment('1-5 scale');
            $table->tinyInteger('barangay_service_rating_lights')->nullable()->comment('1-5 scale');
            $table->text('general_feedback')->nullable();
            $table->enum('available_for_training', ['yes', 'no', ''])->nullable();
            $table->text('reason_not_available')->nullable();
            
            // Status & Tracking
            $table->decimal('form_progress', 5, 2)->default(0)->comment('Percentage: 0-100');
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');
            $table->text('submission_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            
            // Audit & Metadata
            $table->timestamps();
            $table->softDeletes();
            $table->ipAddress('ip_address')->nullable();
            
            // Indexes
            $table->index('user_id');
            $table->index('community_id');
            $table->index('status');
            $table->index('submitted_at');
            $table->fullText(['respondent_first_name', 'respondent_last_name', 'general_feedback'], 'cna_search_ft'); // For search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_needs_assessments');
    }
};
