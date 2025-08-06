<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Residence;
use App\Services\ProfessionalTranslationService;

class TranslateResidences extends Command
{
    private ProfessionalTranslationService $translationService;
    
    public function __construct(ProfessionalTranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'residences:translate {--force : Force re-translation even if English fields exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all existing residences to English';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting residence translation...');
        $this->info('Translation service: ' . ($this->translationService->isDeepLAvailable() ? 'DeepL API' : 'Local fallback'));
        
        // Afficher l'usage DeepL si disponible
        if ($this->translationService->isDeepLAvailable()) {
            $usage = $this->translationService->getUsageInfo();
            if ($usage) {
                $this->line("DeepL usage: {$usage['characters_used']}/{$usage['characters_limit']} ({$usage['percentage_used']}%)");
            }
        }
        
        $force = $this->option('force');
        
        $residences = Residence::all();
        $translated = 0;
        $skipped = 0;
        
        foreach ($residences as $residence) {
            // Skip if already translated and not forcing
            if (!$force && !empty($residence->name_en) && !empty($residence->description_en)) {
                $skipped++;
                continue;
            }
            
            $translations = $this->translationService->translateResidenceFields($residence);
            
            $residence->update([
                'name_en' => $translations['name_en'],
                'description_en' => $translations['description_en'],
                'short_description_en' => $translations['short_description_en'],
            ]);
            
            $translated++;
            $this->line("✓ Translated: {$residence->name} -> {$translations['name_en']}");
        }
        
        $this->newLine();
        $this->info("Translation completed!");
        $this->info("Translated: {$translated} residences");
        $this->info("Skipped: {$skipped} residences");
        
        if ($skipped > 0 && !$force) {
            $this->comment("Use --force to re-translate existing English fields");
        }
    }
}
