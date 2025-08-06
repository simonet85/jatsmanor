<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfessionalTranslationService;

class TestDeepLTranslation extends Command
{
    protected $signature = 'deepl:test {text? : Text to translate}';
    protected $description = 'Test DeepL translation service';

    private ProfessionalTranslationService $translationService;
    
    public function __construct(ProfessionalTranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    public function handle()
    {
        $this->info('🔍 Testing DeepL Translation Service');
        $this->newLine();
        
        // Vérifier la disponibilité
        if ($this->translationService->isDeepLAvailable()) {
            $this->info('✅ DeepL API is available and configured');
            
            // Afficher l'usage
            $usage = $this->translationService->getUsageInfo();
            if ($usage) {
                $this->line("📊 Usage: {$usage['characters_used']}/{$usage['characters_limit']} characters ({$usage['percentage_used']}%)");
            }
        } else {
            $this->warn('⚠️  DeepL API not available - using fallback translation');
            $this->comment('To enable DeepL:');
            $this->comment('1. Get API key from https://www.deepl.com/pro-api');
            $this->comment('2. Add DEEPL_API_KEY=your-key to .env file');
        }
        
        $this->newLine();
        
        // Texte à traduire
        $text = $this->argument('text');
        if (!$text) {
            $text = $this->ask('Enter French text to translate', 'Villa luxueuse avec piscine privée à Cocody');
        }
        
        if (empty($text)) {
            $this->error('No text provided');
            return;
        }
        
        // Traduire
        $this->info("🇫🇷 Original: {$text}");
        
        $startTime = microtime(true);
        $translation = $this->translationService->translateToEnglish($text, false); // Sans cache pour le test
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        $this->info("🇬🇧 Translation: {$translation}");
        $this->comment("⏱️  Duration: {$duration}ms");
        
        $this->newLine();
        
        // Tests supplémentaires
        if ($this->confirm('Run additional tests?', false)) {
            $this->runAdditionalTests();
        }
    }
    
    private function runAdditionalTests()
    {
        $testTexts = [
            'Appartement moderne au cœur du Plateau',
            'Studio tout équipé dans un quartier calme',
            'Maison familiale avec jardin à Marcory',
            'Résidence haut standing avec services inclus',
        ];
        
        $this->info('🧪 Running batch translation test...');
        
        foreach ($testTexts as $text) {
            $translation = $this->translationService->translateToEnglish($text);
            $this->line("• {$text} → {$translation}");
        }
        
        $this->newLine();
        $this->info('✅ Tests completed');
    }
}
