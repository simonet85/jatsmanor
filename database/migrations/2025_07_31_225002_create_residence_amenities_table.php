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
        Schema::create('residence_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residence_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->boolean('is_included')->default(true);
            $table->decimal('additional_cost', 8, 2)->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicates
            $table->unique(['residence_id', 'amenity_id']);
            
            // Indexes
            $table->index('residence_id');
            $table->index('amenity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_amenities');
    }
};
