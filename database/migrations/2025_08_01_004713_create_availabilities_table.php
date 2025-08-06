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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residence_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_available')->default(true);
            $table->decimal('price_override', 8, 2)->nullable();
            $table->integer('min_stay_override')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['residence_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
