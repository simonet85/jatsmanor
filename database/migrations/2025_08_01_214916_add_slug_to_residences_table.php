<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Residence;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Ajouter la colonne slug sans contrainte unique
        Schema::table('residences', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Étape 2: Générer les slugs pour les résidences existantes
        $residences = Residence::all();
        foreach ($residences as $residence) {
            $baseSlug = Str::slug($residence->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Vérifier l'unicité du slug
            while (Residence::where('slug', $slug)->where('id', '!=', $residence->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $residence->update(['slug' => $slug]);
        }

        // Étape 3: Ajouter la contrainte unique maintenant que tous les slugs sont remplis
        Schema::table('residences', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residences', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
