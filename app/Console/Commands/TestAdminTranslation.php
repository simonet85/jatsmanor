<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Residence;
use App\Services\ProfessionalTranslationService;

class TestAdminTranslation extends Command
{
    protected $signature = 'test:admin-translation';
    protected $description = 'Test translation functionality via Admin interface methods';

    public function handle()
    {
        $this->info('🧪 Testing Admin Interface Translation Functionality...');
        $this->newLine();

        // Test 1: Simuler la création via le contrôleur Admin
        $this->info('📝 Test 1: Simulating admin create residence...');
        
        $residenceData = [
            'name' => 'Villa Admin Test Yopougon',
            'description' => 'Superbe villa moderne située dans le quartier résidentiel de Yopougon avec toutes les commodités',
            'short_description' => 'Villa moderne tout équipée à Yopougon',
            'location' => 'Yopougon',
            'price' => 180000,
            'price_per_night' => 600,
            'size' => 120,
            'surface' => 120,
            'max_guests' => 6,
            'floor' => '1er étage',
            'rating' => 4.5,
            'availability_status' => 'available',
            'image' => 'villa-admin-test.jpg',
            'is_featured' => false,
            'is_active' => true,
        ];
        
        try {
            $residence = Residence::create($residenceData);
            
            $this->line("✅ Residence created with ID: {$residence->id}");
            $this->line("🇫🇷 Name FR: {$residence->name}");
            $this->line("🇬🇧 Name EN: " . ($residence->name_en ?: '❌ NOT TRANSLATED'));
            $this->line("🇫🇷 Description FR: " . substr($residence->description, 0, 80) . '...');
            $this->line("🇬🇧 Description EN: " . (($residence->description_en ? substr($residence->description_en, 0, 80) . '...' : '❌ NOT TRANSLATED')));
            $this->line("🇫🇷 Short Desc FR: {$residence->short_description}");
            $this->line("🇬🇧 Short Desc EN: " . ($residence->short_description_en ?: '❌ NOT TRANSLATED'));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create residence: " . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Test 2: Simuler la mise à jour via le contrôleur Admin
        $this->info('📝 Test 2: Simulating admin update residence...');
        
        $existingResidence = Residence::first();
        if ($existingResidence) {
            $oldName = $existingResidence->name;
            $oldNameEn = $existingResidence->name_en;
            
            $existingResidence->update([
                'name' => 'Résidence Mise à Jour Admin ' . now()->format('H:i:s'),
                'description' => 'Description mise à jour avec de nouveaux équipements et services premium',
                'short_description' => 'Résidence premium mise à jour'
            ]);
            
            $this->line("✅ Updated residence ID: {$existingResidence->id}");
            $this->line("🔄 Old name FR: {$oldName}");
            $this->line("🔄 New name FR: {$existingResidence->name}");
            $this->line("🔄 Old name EN: {$oldNameEn}");
            $this->line("🔄 New name EN: " . ($existingResidence->name_en ?: '❌ NOT TRANSLATED'));
        } else {
            $this->warn('⚠️  No existing residence found for update test');
        }

        $this->newLine();

        // Test 3: Vérifier tous les champs traduits dans la base
        $this->info('📝 Test 3: Checking all translated fields in database...');
        
        $residences = Residence::select('id', 'name', 'name_en', 'description', 'description_en', 'short_description', 'short_description_en')
            ->take(3)
            ->get();

        foreach ($residences as $res) {
            $this->line("📍 ID {$res->id}: {$res->name}");
            $this->line("   ✅ Name EN: " . ($res->name_en ? '✅ ' . $res->name_en : '❌ NOT TRANSLATED'));
            $this->line("   ✅ Desc EN: " . ($res->description_en ? '✅ ' . substr($res->description_en, 0, 50) . '...' : '❌ NOT TRANSLATED'));
            $this->line("   ✅ Short EN: " . ($res->short_description_en ? '✅ ' . $res->short_description_en : '❌ NOT TRANSLATED'));
            $this->newLine();
        }

        // Test 4: Vérifier les traductions en temps réel
        $this->info('📝 Test 4: Testing real-time translation service...');
        
        $translationService = app(ProfessionalTranslationService::class);
        
        $testTexts = [
            'Appartement luxueux au Plateau avec vue panoramique',
            'Studio moderne équipé dans un quartier sécurisé',
            'Villa familiale avec jardin tropical et piscine'
        ];

        foreach ($testTexts as $text) {
            $translation = $translationService->translateToEnglish($text);
            $this->line("🔄 {$text}");
            $this->line("   → {$translation}");
        }

        $this->newLine();
        $this->info('🎉 Admin translation tests completed!');
        
        return 0;
    }
}
