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
        Schema::create('beneficiary_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->enum('enrollment_status', ['enrolled', 'active', 'completed', 'dropped_out', 'inactive'])->default('enrolled');
            $table->date('enrollment_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->decimal('participation_rate', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Composite unique constraint
            $table->unique(['beneficiary_id', 'program_id']);
            
            // Indexes
            $table->index('program_id');
            $table->index('enrollment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_program');
    }
};
