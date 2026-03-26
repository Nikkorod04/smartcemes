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
        Schema::create('program_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('extension_programs')->onDelete('cascade');
            $table->foreignId('logic_model_id')->nullable()->constrained('program_logic_models')->onDelete('set null');
            $table->string('activity_name');
            $table->dateTime('activity_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->json('facilitators')->nullable();
            $table->integer('target_attendees')->default(0);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_activities');
    }
};
