<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_assessment_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            
            // Assessment Data Count
            $table->integer('total_assessments')->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            
            // SECTION I - Identifying Information Averages
            $table->decimal('avg_age', 5, 2)->nullable();
            
            // Most common values from SECTION I
            $table->string('most_common_civil_status')->nullable();
            $table->string('most_common_sex')->nullable();
            $table->string('most_common_religion')->nullable();
            $table->string('most_common_educational_attainment')->nullable();
            
            // SECTION III - Economic Aspect
            $table->string('most_common_livelihood')->nullable();
            $table->integer('percent_interested_in_training')->nullable()->comment('0-100');
            $table->text('common_desired_trainings')->nullable()->comment('JSON or comma-separated');
            
            // SECTION IV - Educational Aspect
            $table->integer('percent_household_member_studying')->nullable()->comment('0-100');
            $table->integer('percent_interested_in_continuing_studies')->nullable()->comment('0-100');
            $table->string('most_common_preferred_time')->nullable();
            $table->string('most_common_preferred_days')->nullable();
            
            // SECTION V - Health, Sanitation, Environmental
            $table->string('most_common_water_source')->nullable();
            $table->string('most_common_water_source_distance')->nullable();
            $table->integer('percent_has_own_toilet')->nullable()->comment('0-100');
            $table->integer('percent_has_electricity')->nullable()->comment('0-100');
            $table->integer('percent_keeps_animals')->nullable()->comment('0-100');
            $table->text('common_health_programs')->nullable()->comment('Most mentioned');
            $table->integer('percent_benefits_from_health_programs')->nullable()->comment('0-100');
            
            // SECTION VI - Housing and Basic Amenities
            $table->string('most_common_housing_type')->nullable();
            $table->string('most_common_tenure_status')->nullable();
            $table->text('common_appliances')->nullable()->comment('Most common ones');
            
            // SECTION VII - Recreational Facilities & Organization
            $table->integer('percent_member_of_organization')->nullable()->comment('0-100');
            $table->string('most_common_meeting_frequency')->nullable();
            
            // SECTION VIII - Top Problems/Needs (stored as JSON)
            $table->json('top_family_problems')->nullable()->comment('Array of problems with frequency');
            $table->json('top_health_problems')->nullable();
            $table->json('top_education_problems')->nullable();
            $table->json('top_employment_problems')->nullable();
            $table->json('top_infrastructure_problems')->nullable();
            $table->json('top_economy_problems')->nullable();
            $table->json('top_security_problems')->nullable();
            
            // SECTION IX - Service Ratings (Averages out of 5)
            $table->decimal('avg_service_rating_police', 3, 2)->nullable();
            $table->decimal('avg_service_rating_fire', 3, 2)->nullable();
            $table->decimal('avg_service_rating_bns', 3, 2)->nullable();
            $table->decimal('avg_service_rating_water', 3, 2)->nullable();
            $table->decimal('avg_service_rating_roads', 3, 2)->nullable();
            $table->decimal('avg_service_rating_clinic', 3, 2)->nullable();
            $table->decimal('avg_service_rating_market', 3, 2)->nullable();
            $table->decimal('avg_service_rating_community_center', 3, 2)->nullable();
            $table->decimal('avg_service_rating_lights', 3, 2)->nullable();
            $table->decimal('overall_service_satisfaction', 3, 2)->nullable()->comment('Average of all 9 ratings');
            
            // Training Availability
            $table->integer('percent_available_for_training')->nullable()->comment('0-100');
            
            // General insights (key phrases from feedback)
            $table->json('key_feedback_themes')->nullable()->comment('Most mentioned themes');
            
            // Metadata
            $table->text('summary_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('community_id');
            $table->index('last_calculated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_assessment_summaries');
    }
};
