<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfessionalTranslationService;

class QuickTranslationTest extends Command
{
    protected $signature = 'test:quick';
    protected $description = 'Quick translation test';

    public function handle()
    {
        $this->info('🚀 Quick Translation Test');
        
        $service = new ProfessionalTranslationService();
        
        // Test une phrase simple
        $text = "Studio meublé à Abobo avec garage, eau, netflix";
        $this->info("🇫🇷 Original: {$text}");
        
        try {
            $translation = $service->translateToEnglish($text, false);
            $this->info("🇬🇧 Translation: {$translation}");
            
            // Vérifier si DeepL est disponible
            if ($service->isDeepLAvailable()) {
                $this->info("✅ Using DeepL API");
                $usage = $service->getUsageInfo();
                if ($usage) {
                    $this->info("📊 Usage: {$usage['characters_used']}/{$usage['characters_limit']}");
                }
            } else {
                $this->warn("⚠️  Using fallback translation");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
        }
        
        return 0;
    }
}
