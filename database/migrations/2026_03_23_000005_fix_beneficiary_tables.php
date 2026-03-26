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
        // Drop existing tables if they exist
        Schema::dropIfExists('beneficiary_impacts');
        Schema::dropIfExists('beneficiaries');

        // Create beneficiaries table
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->foreignId('community_id')->nullable()->constrained('communities')->onDelete('set null');
            $table->json('program_ids')->nullable();
            $table->string('beneficiary_category')->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();
            $table->string('occupation')->nullable();
            $table->string('educational_attainment')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('number_of_dependents')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'dropout'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            $table->index('community_id');
            $table->index('status');
            $table->index('beneficiary_category');
            $table->fullText(['first_name', 'last_name', 'email']);
        });

        // Create beneficiary_impacts table
        Schema::create('beneficiary_impacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('extension_programs')->onDelete('set null');
            $table->date('assessment_date');
            $table->enum('assessment_type', ['baseline', 'midterm', 'endline', 'post', 'follow-up'])->default('baseline');
            
            // Income Impact
            $table->decimal('baseline_income', 12, 2)->nullable();
            $table->decimal('post_intervention_income', 12, 2)->nullable();
            $table->decimal('income_change_percentage', 5, 2)->nullable();
            
            // Skills Impact
            $table->json('skills_acquired')->nullable();
            $table->integer('skills_level')->nullable();
            
            // Knowledge Impact
            $table->text('knowledge_gained')->nullable();
            $table->integer('knowledge_level')->nullable();
            
            // Behavioral Change
            $table->boolean('behavioral_change_observed')->default(false);
            $table->text('behavioral_change_description')->nullable();
            
            // Health Status
            $table->string('health_status_before')->nullable();
            $table->string('health_status_after')->nullable();
            
            // Confidence & Self-Efficacy
            $table->integer('confidence_level_before')->nullable();
            $table->integer('confidence_level_after')->nullable();
            
            // Satisfaction
            $table->integer('satisfaction_rating')->nullable();
            $table->text('testimonial')->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('beneficiary_id');
            $table->index('program_id');
            $table->index('assessment_type');
            $table->index('assessment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_impacts');
        Schema::dropIfExists('beneficiaries');
    }
};
