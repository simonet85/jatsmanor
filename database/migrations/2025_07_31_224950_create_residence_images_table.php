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
        Schema::create('residence_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residence_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('alt_text');
            $table->boolean('is_primary')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('residence_id');
            $table->index(['residence_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_images');
    }
};
