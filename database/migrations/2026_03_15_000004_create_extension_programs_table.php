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
        Schema::create('extension_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('description')->nullable();
            $table->text('goals')->nullable();
            $table->text('objectives')->nullable();
            $table->string('cover_image')->nullable(); // File path for cover image
            $table->json('gallery_images')->nullable(); // JSON array of image paths
            $table->text('activities')->nullable(); // List of activities
            $table->json('related_communities')->nullable(); // JSON array of community IDs
            $table->json('attachments')->nullable(); // JSON array of file paths (PDFs, docs, etc.)
            $table->enum('status', ['active', 'inactive', 'planning', 'completed'])->default('active');
            $table->text('notes')->nullable();
            
            // Tracking
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('created_by');
            $table->fullText(['title', 'description', 'goals', 'objectives'], 'programs_search_ft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extension_programs');
    }
};
