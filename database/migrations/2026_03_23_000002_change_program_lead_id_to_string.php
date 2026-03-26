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
            // Drop the foreign key first
            $table->dropForeign(['program_lead_id']);
            
            // Change column type from unsignedBigInteger to string
            $table->string('program_lead_id', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extension_programs', function (Blueprint $table) {
            // Change column back to unsignedBigInteger
            $table->unsignedBigInteger('program_lead_id')->nullable()->change();
            
            // Re-add the foreign key
            $table->foreign('program_lead_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
