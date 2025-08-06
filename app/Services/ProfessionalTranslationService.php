<?php

namespace App\Services;

use App\Models\Residence;
use DeepL\Translator;
use DeepL\TextResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProfessionalTranslationService
{
    private ?Translator $translator = null;
    
    public function __construct()
    {
        // Initialiser DeepL seulement si la clé API est configurée
        $apiKey = config('services.deepl.api_key');
        if ($apiKey) {
            try {
                $this->translator = new Translator($apiKey);
                
                // Tester immédiatement la connexion pour vérifier que tout fonctionne
                $usage = $this->translator->getUsage();
                Log::info('DeepL Translator initialized successfully. Usage: ' . $usage->character->count . '/' . $usage->character->limit);
                
            } catch (\Exception $e) {
                Log::error('Failed to initialize DeepL Translator: ' . $e->getMessage());
                
                // Si c'est un problème SSL, on le signale spécifiquement
                if (str_contains($e->getMessage(), 'SSL certificate') || 
                    str_contains($e->getMessage(), 'unable to get local issuer certificate')) {
                    Log::warning('DeepL SSL certificate issue detected. This is common in local development environments.');
                }
                
                $this->translator = null;
            }
        }
    }
    
    /**
     * Traduit un texte vers l'anglais en utilisant DeepL API
     */
    public function translateToEnglish(string $text, bool $useCache = true): string
    {
        if (empty(trim($text))) {
            return '';
        }
        
        // Générer une clé de cache unique
        $cacheKey = 'translation:deepl:' . md5($text);
        
        // Vérifier le cache d'abord
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            // Utiliser DeepL si disponible
            if ($this->translator) {
                $result = $this->translator->translateText(
                    $text,
                    'fr',  // Source: français
                    'en-US'   // Cible: anglais US
                );
                
                $translation = $result->text;
                
                // Post-traitement pour améliorer les traductions immobilières
                $translation = $this->postProcessTranslation($translation);
                
                // Mettre en cache pour 24h
                if ($useCache) {
                    Cache::put($cacheKey, $translation, now()->addDay());
                }
                
                Log::info("DeepL Translation: '{$text}' -> '{$translation}'");
                return $translation;
            }
        } catch (\Exception $e) {
            Log::warning("DeepL translation failed: " . $e->getMessage());
        }
        
        // Fallback vers notre dictionnaire local
        return $this->fallbackTranslation($text);
    }
    
    /**
     * Traduction de secours avec notre dictionnaire local
     */
    private function fallbackTranslation(string $text): string
    {
        $translations = [
            // Noms de résidences courants
            'Villa Deluxe Cocody' => 'Deluxe Villa Cocody',
            'Villa Luxueuse Cocody' => 'Luxury Villa Cocody',
            'Appartement Standing Plateau' => 'Premium Apartment Plateau',
            'Maison Familiale Marcory' => 'Family House Marcory',
            'Studio Confortable Treichville' => 'Comfortable Studio Treichville',
            'Résidence Standing Riviera' => 'Premium Residence Riviera',
            'Duplex Spacieux Yopougon' => 'Spacious Duplex Yopougon',
            'studio meublé' => 'Furnished Studio',
            
            // Descriptions courantes
            'Magnifique villa moderne' => 'Beautiful modern villa',
            'Appartement tout équipé' => 'Fully equipped apartment',
            'Maison familiale avec jardin' => 'Family house with garden',
            'Studio dans quartier calme' => 'Studio in quiet neighborhood',
            'Villa avec piscine privée' => 'Villa with private pool',
            'Résidence haut standing' => 'High-end residence',
            
            // Éléments de base
            'villa' => 'villa',
            'maison' => 'house',
            'appartement' => 'apartment',
            'studio' => 'studio',
            'résidence' => 'residence',
            'duplex' => 'duplex',
            'luxueuse' => 'luxury',
            'moderne' => 'modern',
            'spacieux' => 'spacious',
            'confortable' => 'comfortable',
            'standing' => 'premium',
            'familiale' => 'family',
            'meublé' => 'furnished',
            'équipé' => 'equipped',
        ];
        
        // Recherche exacte d'abord
        if (isset($translations[trim($text)])) {
            return $translations[trim($text)];
        }
        
        // Traduction par mots
        $translatedText = $text;
        foreach ($translations as $french => $english) {
            $translatedText = str_ireplace($french, $english, $translatedText);
        }
        
        return ucfirst(trim($translatedText));
    }
    
    /**
     * Traduit tous les champs d'une résidence
     */
    public function translateResidenceFields(Residence $residence): array
    {
        return [
            'name_en' => $this->translateToEnglish($residence->name),
            'description_en' => $this->translateToEnglish($residence->description ?? ''),
            'short_description_en' => $this->translateToEnglish($residence->short_description ?? ''),
        ];
    }
    
    /**
     * Traduit un batch de textes (plus efficace pour plusieurs traductions)
     */
    public function translateBatch(array $texts): array
    {
        if (!$this->translator || empty($texts)) {
            return array_map([$this, 'fallbackTranslation'], $texts);
        }
        
        try {
            $results = $this->translator->translateText(
                $texts,
                'fr',
                'en'
            );
            
            $translations = [];
            foreach ($results as $index => $result) {
                $translations[$index] = $result->text;
            }
            
            return $translations;
        } catch (\Exception $e) {
            Log::warning("DeepL batch translation failed: " . $e->getMessage());
            return array_map([$this, 'fallbackTranslation'], $texts);
        }
    }
    
    /**
     * Vérifier si DeepL est configuré et fonctionnel
     */
    public function isDeepLAvailable(): bool
    {
        if (!$this->translator) {
            return false;
        }
        
        try {
            // Test simple pour vérifier la connectivité
            $usage = $this->translator->getUsage();
            return true;
        } catch (\Exception $e) {
            Log::warning("DeepL not available: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir les informations d'usage DeepL
     */
    public function getUsageInfo(): ?array
    {
        if (!$this->translator) {
            return null;
        }
        
        try {
            $usage = $this->translator->getUsage();
            return [
                'characters_used' => $usage->character->count,
                'characters_limit' => $usage->character->limit,
                'percentage_used' => round(($usage->character->count / $usage->character->limit) * 100, 2),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Post-traitement pour améliorer les traductions immobilières
     */
    private function postProcessTranslation(string $translation): string
    {
        // Dictionnaire de corrections spécifiques pour l'immobilier ivoirien
        $corrections = [
            // Prépositions et connecteurs
            ' à ' => ' in ',
            ' avec ' => ' with ',
            ' au ' => ' in the ',
            ' du ' => ' of the ',
            ' de ' => ' of ',
            ' et ' => ' and ',
            ' ou ' => ' or ',
            
            // Termes immobiliers spécifiques
            'eau' => 'water',
            'netflix' => 'Netflix',
            'garage' => 'garage',
            'jardin' => 'garden',
            'piscine' => 'pool',
            'privée' => 'private',
            
            // Quartiers d'Abidjan (garder mais contextualiser)
            'à Abobo' => 'in Abobo',
            'à Cocody' => 'in Cocody',
            'à Marcory' => 'in Marcory',
            'à Yopougon' => 'in Yopougon',
            'au Plateau' => 'in Plateau',
            'à Adjamé' => 'in Adjamé',
            'à Koumassi' => 'in Koumassi',
            'à Treichville' => 'in Treichville',
            
            // Expressions courantes
            'au cœur du' => 'in the heart of',
            'au cœur de' => 'in the heart of',
            'dans un quartier' => 'in a neighborhood',
            'dans le quartier' => 'in the neighborhood',
            'haut standing' => 'high-end',
            'tout équipé' => 'fully equipped',
            'services inclus' => 'services included',
        ];
        
        // Appliquer les corrections
        foreach ($corrections as $french => $english) {
            $translation = str_ireplace($french, $english, $translation);
        }
        
        // Nettoyer les espaces multiples
        $translation = preg_replace('/\s+/', ' ', $translation);
        $translation = trim($translation);
        
        // Capitaliser la première lettre
        $translation = ucfirst(strtolower($translation));
        
        return $translation;
    }
}
