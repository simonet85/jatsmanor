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
        Schema::table('residences', function (Blueprint $table) {
            $table->integer('max_guests')->default(2)->after('size');
            $table->decimal('price_per_night', 10, 2)->after('price');
            $table->integer('surface')->nullable()->after('max_guests'); // Alias pour size en m²
            $table->boolean('is_active')->default(true)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residences', function (Blueprint $table) {
            $table->dropColumn([
                'max_guests',
                'price_per_night', 
                'surface',
                'is_active'
            ]);
        });
    }
};
