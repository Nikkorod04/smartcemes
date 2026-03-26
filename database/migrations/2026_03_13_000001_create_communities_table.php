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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Barangay/Community name
            $table->string('municipality')->nullable(); // Municipality name
            $table->string('province')->nullable(); // Province
            $table->text('description')->nullable(); // Community description/background
            $table->string('contact_person')->nullable(); // Community leader/representative
            $table->string('contact_number')->nullable(); // Phone number
            $table->string('email')->nullable(); // Email address
            $table->text('address')->nullable(); // Full address/location details
            $table->decimal('latitude', 10, 8)->nullable(); // GPS latitude
            $table->decimal('longitude', 11, 8)->nullable(); // GPS longitude
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable(); // Secretary notes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('municipality');
            $table->index('province');
            $table->index('status');
            $table->fullText(['name', 'municipality', 'province'], 'communities_search_ft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
