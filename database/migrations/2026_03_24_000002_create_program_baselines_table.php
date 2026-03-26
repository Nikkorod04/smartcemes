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
        Schema::create('program_baselines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->foreignId('community_id')->nullable()->constrained('communities')->onDelete('set null');
            
            // Baseline Information
            $table->date('baseline_assessment_date');
            $table->integer('target_beneficiaries_count')->nullable();
            
            // Target Indicators (these are targets/expectations)
            $table->integer('target_literacy_level')->nullable(); // 1-5 scale
            $table->decimal('target_average_income', 12, 2)->nullable();
            $table->json('target_skills')->nullable(); // Array of skills to develop
            
            // Existing Conditions
            $table->json('community_demographics')->nullable(); // Population, age groups, etc.
            $table->text('existing_capacities')->nullable(); // What community already has
            $table->text('existing_challenges')->nullable(); // Current gaps/problems
            
            // Partner Information
            $table->text('partner_info')->nullable(); // Partner organizations, roles
            $table->json('available_resources')->nullable(); // Available resources/facilities
            
            // Assessment Details
            $table->text('assessment_methodology')->nullable(); // How baseline was conducted
            $table->text('key_findings')->nullable(); // Summary of baseline findings
            $table->json('data_sources')->nullable(); // Where data came from
            
            // Status & Notes
            $table->enum('status', ['draft', 'approved', 'completed', 'archived'])->default('draft');
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('program_id');
            $table->index('community_id');
            $table->index('baseline_assessment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_baselines');
    }
};
