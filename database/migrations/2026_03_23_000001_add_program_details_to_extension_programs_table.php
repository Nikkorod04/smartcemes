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
        Schema::table('extension_programs', function (Blueprint $table) {
            // Timeline
            $table->date('planned_start_date')->nullable()->after('objectives');
            $table->date('planned_end_date')->nullable()->after('planned_start_date');
            
            // Beneficiaries
            $table->unsignedInteger('target_beneficiaries')->nullable()->after('planned_end_date');
            $table->json('beneficiary_categories')->nullable()->after('target_beneficiaries');
            
            // Budget
            $table->decimal('allocated_budget', 12, 2)->nullable()->after('beneficiary_categories');
            
            // Program Lead
            $table->unsignedBigInteger('program_lead_id')->nullable()->after('allocated_budget');
            
            // Partners
            $table->json('partners')->nullable()->after('program_lead_id');
            
            // Add foreign key for program_lead_id
            $table->foreign('program_lead_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extension_programs', function (Blueprint $table) {
            $table->dropForeign(['program_lead_id']);
            $table->dropColumn([
                'planned_start_date',
                'planned_end_date',
                'target_beneficiaries',
                'beneficiary_categories',
                'allocated_budget',
                'program_lead_id',
                'partners',
            ]);
        });
    }
};
