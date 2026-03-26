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
        Schema::create('activity_facilitators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_activity_id')->constrained('program_activities')->onDelete('cascade');
            $table->string('facilitator_name');
            $table->enum('facilitator_role', ['instructor', 'assistant', 'coordinator'])->default('instructor');
            $table->string('contact_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_facilitators');
    }
};
