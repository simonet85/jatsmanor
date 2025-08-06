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
        Schema::create('residences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('short_description');
            $table->decimal('price', 8, 2);
            $table->string('location');
            $table->string('floor')->nullable();
            $table->integer('size')->nullable(); // m²
            $table->decimal('rating', 3, 2)->default(0);
            $table->enum('availability_status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->string('image');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('availability_status');
            $table->index('is_featured');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residences');
    }
};
