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
        Schema::create('program_logic_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            
            // Program Logic Model Components (JSON)
            $table->json('inputs')->nullable(); // Budget, personnel, partners, resources
            $table->json('activities')->nullable(); // Planned activities list
            $table->json('outputs')->nullable(); // Expected trainees, materials, services
            $table->json('outcomes')->nullable(); // Expected knowledge/skill gains
            $table->json('impacts')->nullable(); // Contribution to SDGs/goals
            
            // Metadata
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('program_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_logic_models');
    }
};
