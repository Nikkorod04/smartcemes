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
        Schema::create('program_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained('program_activities')->onDelete('set null');
            
            // Output Types
            $table->enum('output_type', ['training', 'materials', 'services', 'mentoring', 'assessment', 'other'])->default('training');
            $table->string('output_title'); // e.g., "Farmer Training Workshop"
            $table->text('description')->nullable();
            
            // Metrics
            $table->integer('quantity')->default(0); // e.g., 50 farmers trained
            $table->string('unit')->nullable(); // e.g., "farmers", "modules", "sessions"
            $table->integer('beneficiaries_reached')->default(0); // Total beneficiaries
            $table->json('beneficiary_ids')->nullable(); // Array of beneficiary IDs reached
            
            // Dates
            $table->date('output_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Documentation
            $table->text('outcomes')->nullable(); // What was achieved
            $table->json('attachments')->nullable(); // Photos, evidence files
            $table->text('notes')->nullable();
            
            // Status & Tracking
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('program_id');
            $table->index('activity_id');
            $table->index('output_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_outputs');
    }
};
