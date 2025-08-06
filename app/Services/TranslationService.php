<?php

namespace App\Services;

use App\Models\Residence;

class TranslationService
{
    /**
     * Service de traduction automatique (simulé)
     * En production, vous pourriez utiliser Google Translate API ou un autre service
     */
    public static function translateToEnglish(string $text): string
    {
        // Dictionnaire de traductions courantes pour les résidences
        $translations = [
            // Noms de résidences (traductions exactes en premier)
            'Villa Deluxe Cocody' => 'Deluxe Villa Cocody',
            'Villa Luxueuse Cocody' => 'Luxury Villa Cocody',
            'Appartement Standing Plateau' => 'Premium Apartment Plateau',
            'Appartement Moderne Plateau' => 'Modern Apartment Plateau',
            'Maison Familiale Marcory' => 'Family House Marcory',
            'Studio Confortable Treichville' => 'Comfortable Studio Treichville',
            'Résidence Standing Riviera' => 'Premium Residence Riviera',
            'Duplex Spacieux Yopougon' => 'Spacious Duplex Yopougon',
            'studio meublé' => 'furnished studio',
            
            // Descriptions communes (phrases complètes)
            'Maison familiale avec jardin à Marcory' => 'Family house with garden in Marcory',
            'Villa de luxe avec piscine privée' => 'Luxury villa with private pool',
            'Appartement moderne au cœur du plateau' => 'Modern apartment in the heart of Plateau',
            'Studio tout équipé dans un quartier calme' => 'Fully equipped studio in a quiet neighborhood',
            'Résidence haut standing avec services inclus' => 'High-end residence with included services',
            'Duplex spacieux idéal pour familles' => 'Spacious duplex ideal for families',
            
            // Adjectifs et qualificatifs
            'deluxe' => 'deluxe',
            'luxueuse' => 'luxury',
            'luxueux' => 'luxury',
            'standing' => 'premium',
            'haut standing' => 'high-end',
            'moderne' => 'modern',
            'confortable' => 'comfortable',
            'spacieux' => 'spacious',
            'spacieuse' => 'spacious',
            'familiale' => 'family',
            'familial' => 'family',
            'meublé' => 'furnished',
            'meublée' => 'furnished',
            'tout équipé' => 'fully equipped',
            'équipé' => 'equipped',
            'équipée' => 'equipped',
            
            // Types de logement
            'maison' => 'house',
            'villa' => 'villa',
            'appartement' => 'apartment',
            'studio' => 'studio',
            'résidence' => 'residence',
            'duplex' => 'duplex',
            
            // Éléments et caractéristiques
            'avec jardin' => 'with garden',
            'avec piscine' => 'with pool',
            'avec terrasse' => 'with terrace',
            'avec balcon' => 'with balcony',
            'avec parking' => 'with parking',
            'jardin' => 'garden',
            'piscine' => 'pool',
            'terrasse' => 'terrace',
            'balcon' => 'balcony',
            'parking' => 'parking',
            
            // Localisations et descriptions
            'quartier calme' => 'quiet neighborhood',
            'centre ville' => 'city center',
            'près de' => 'near',
            'proche de' => 'close to',
            'au cœur du' => 'in the heart of',
            'au cœur de' => 'in the heart of',
            'dans un' => 'in a',
            'dans une' => 'in a',
            
            // Services et commodités
            'services inclus' => 'included services',
            'service de ménage' => 'cleaning service',
            'sécurité 24h/24' => '24/7 security',
            'wifi gratuit' => 'free wifi',
            'climatisation' => 'air conditioning',
            'idéal pour familles' => 'ideal for families',
            'idéal pour' => 'ideal for',
            
            // Prépositions et mots de liaison
            'à' => 'in',
            'de' => 'of',
            'du' => 'of the',
            'des' => 'of the',
            'avec' => 'with',
            'pour' => 'for',
            'et' => 'and',
        ];
        
        // Nettoyer le texte d'entrée
        $text = trim($text);
        if (empty($text)) {
            return '';
        }
        
        // Recherche de traduction exacte d'abord (case insensitive)
        foreach ($translations as $french => $english) {
            if (strcasecmp($french, $text) === 0) {
                return $english;
            }
        }
        
        // Traduction par remplacement de mots (du plus spécifique au plus général)
        $translatedText = $text;
        
        // Trier les clés par longueur décroissante pour traiter les expressions longues en premier
        $sortedTranslations = $translations;
        uksort($sortedTranslations, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        foreach ($sortedTranslations as $french => $english) {
            $translatedText = str_ireplace($french, $english, $translatedText);
        }
        
        // Nettoyage final : capitalisation appropriée
        $translatedText = self::cleanupTranslation($translatedText);
        
        return $translatedText;
    }
    
    /**
     * Nettoie et améliore la traduction finale
     */
    private static function cleanupTranslation(string $text): string
    {
        // Supprimer les espaces multiples
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Capitaliser la première lettre
        $text = ucfirst(trim($text));
        
        // Corrections spécifiques courantes
        $corrections = [
            'villa ofluxe' => 'Deluxe Villa',
            'apartment Standing' => 'Premium Apartment',
            'house Family' => 'Family House',
            'studio furnished' => 'Furnished Studio',
        ];
        
        foreach ($corrections as $wrong => $correct) {
            $text = str_ireplace($wrong, $correct, $text);
        }
        
        return $text;
    }
    
    /**
     * Traduire les champs d'une résidence
     */
    public static function translateResidenceFields(Residence $residence): array
    {
        return [
            'name_en' => self::translateToEnglish($residence->name),
            'description_en' => self::translateToEnglish($residence->description ?? ''),
            'short_description_en' => self::translateToEnglish($residence->short_description ?? ''),
        ];
    }
}
