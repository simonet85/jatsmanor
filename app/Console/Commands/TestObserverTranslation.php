<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Residence;
use App\Services\ProfessionalTranslationService;

class TestObserverTranslation extends Command
{
    protected $signature = 'test:observer';
    protected $description = 'Test if observer auto-translation is working';

    public function handle()
    {
        $this->info('🧪 Testing Observer Auto-Translation...');
        $this->newLine();

        // Test 1: Create a new residence
        $this->info('📝 Test 1: Creating new residence...');
        
        $newResidence = new Residence();
        $newResidence->name = 'Test Villa Moderne à Cocody';
        $newResidence->description = 'Magnifique villa avec piscine et jardin tropical';
        $newResidence->short_description = 'Villa luxueuse avec tous les équipements';
        $newResidence->price = 150000;
        $newResidence->price_per_night = 500; // Add the missing field
        $newResidence->location = 'Cocody';
        $newResidence->max_guests = 4;
        $newResidence->availability_status = 'available';
        $newResidence->image = 'test-villa.jpg'; // Add the missing image field
        
        try {
            $newResidence->save();
            
            $this->line("✅ Residence created with ID: {$newResidence->id}");
            $this->line("🇫🇷 Name FR: {$newResidence->name}");
            $this->line("🇬🇧 Name EN: " . ($newResidence->name_en ?: '❌ NOT TRANSLATED'));
            $this->line("🇫🇷 Description FR: " . substr($newResidence->description, 0, 50) . '...');
            $this->line("🇬🇧 Description EN: " . (($newResidence->description_en ? substr($newResidence->description_en, 0, 50) . '...' : '❌ NOT TRANSLATED')));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create residence: " . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Test 2: Update existing residence
        $this->info('📝 Test 2: Updating existing residence...');
        
        $existingResidence = Residence::first();
        if ($existingResidence) {
            $oldName = $existingResidence->name;
            $existingResidence->name = 'Résidence Mise à Jour ' . now()->format('H:i:s');
            $existingResidence->save();
            
            $this->line("✅ Updated residence ID: {$existingResidence->id}");
            $this->line("🔄 Old name: {$oldName}");
            $this->line("🔄 New name: {$existingResidence->name}");
            $this->line("🇬🇧 Updated EN: " . ($existingResidence->name_en ?: '❌ NOT TRANSLATED'));
        } else {
            $this->warn('⚠️  No existing residence found for update test');
        }

        $this->newLine();

        // Test 3: Check translation service directly
        $this->info('📝 Test 3: Direct translation service test...');
        
        $translationService = app(ProfessionalTranslationService::class);
        $testTranslation = $translationService->translateToEnglish('Villa de test avec piscine');
        $this->line("✅ Direct translation: Villa de test avec piscine → {$testTranslation}");

        $this->newLine();
        $this->info('🎉 Observer translation tests completed!');
        
        return 0;
    }
}
