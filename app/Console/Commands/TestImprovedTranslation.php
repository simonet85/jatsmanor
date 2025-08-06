<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfessionalTranslationService;

class TestImprovedTranslation extends Command
{
    protected $signature = 'test:translation';
    protected $description = 'Test improved translation with post-processing';

    public function handle()
    {
        $this->info('🧪 Testing improved translation system...');
        
        $service = new ProfessionalTranslationService();
        
        // Tests avec des phrases problématiques
        $testPhrases = [
            'Studio meublé à Abobo sogephia avec garage, eau, netflix',
            'Appartement moderne au cœur du Plateau',
            'Maison familiale avec jardin à Marcory',
            'Villa luxueuse avec piscine privée à Cocody',
            'Résidence haut standing avec services inclus',
            'Studio tout équipé dans un quartier calme',
        ];
        
        foreach ($testPhrases as $phrase) {
            $this->info("Testing: {$phrase}");
            
            try {
                $translation = $service->translateToEnglish($phrase, false); // Force new translation
                $this->info("✅ Result: {$translation}");
                $this->newLine();
            } catch (\Exception $e) {
                $this->error("❌ Error: {$e->getMessage()}");
            }
        }
        
        // Vérifier le statut DeepL
        if ($service->isDeepLAvailable()) {
            $this->info('✅ DeepL is available and working');
            $usage = $service->getUsageInfo();
            if ($usage) {
                $this->info("📊 Usage: {$usage['characters_used']}/{$usage['characters_limit']} ({$usage['percentage_used']}%)");
            }
        } else {
            $this->warn('⚠️  DeepL not available - using fallback');
        }
        
        return 0;
    }
}
