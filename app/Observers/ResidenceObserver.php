<?php

namespace App\Observers;

use App\Models\Residence;
use App\Services\ProfessionalTranslationService;

class ResidenceObserver
{
    private ProfessionalTranslationService $translationService;
    
    public function __construct(ProfessionalTranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
    /**
     * Handle the Residence "creating" event.
     * Se déclenche AVANT la création, permet de modifier les données
     */
    public function creating(Residence $residence): void
    {
        $this->autoTranslate($residence);
    }

    /**
     * Handle the Residence "updating" event.
     * Se déclenche AVANT la mise à jour
     */
    public function updating(Residence $residence): void
    {
        // Ne traduire que si les champs français ont changé
        if ($residence->isDirty(['name', 'description', 'short_description'])) {
            $this->autoTranslate($residence);
        }
    }

    /**
     * Traduit automatiquement les champs anglais
     */
    private function autoTranslate(Residence $residence): void
    {
        // Ne pas écraser une traduction manuelle existante (optionnel)
        // Commentez ces conditions si vous voulez toujours re-traduire
        $translations = $this->translationService->translateResidenceFields($residence);
        
        if (empty($residence->name_en)) {
            $residence->name_en = $translations['name_en'];
        }
        
        if (empty($residence->description_en)) {
            $residence->description_en = $translations['description_en'];
        }
        
        if (empty($residence->short_description_en)) {
            $residence->short_description_en = $translations['short_description_en'];
        }
    }

    /**
     * Handle the Residence "deleted" event.
     */
    public function deleted(Residence $residence): void
    {
        //
    }

    /**
     * Handle the Residence "restored" event.
     */
    public function restored(Residence $residence): void
    {
        //
    }

    /**
     * Handle the Residence "force deleted" event.
     */
    public function forceDeleted(Residence $residence): void
    {
        //
    }
}
