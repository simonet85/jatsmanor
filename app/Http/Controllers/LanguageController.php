<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Changer la langue de l'application
     */
    public function switch(Request $request, $locale)
    {
        // Vérifier que la locale est supportée
        $availableLocales = config('app.available_locales', ['fr', 'en']);
        
        if (!in_array($locale, $availableLocales)) {
            return response()->json([
                'success' => false,
                'message' => 'Langue non supportée'
            ], 400);
        }
        
        // Sauvegarder la locale en session
        Session::put('locale', $locale);
        
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => 'Langue changée avec succès'
            ]);
        }
        
        // Rediriger vers la page précédente ou l'accueil
        return Redirect::back()->with('success', 'Langue changée vers ' . ($locale === 'fr' ? 'français' : 'anglais'));
    }
    
    /**
     * Obtenir la langue actuelle
     */
    public function current()
    {
        return response()->json([
            'current_locale' => app()->getLocale(),
            'available_locales' => config('app.available_locales', ['fr', 'en'])
        ]);
    }
    
    /**
     * Obtenir toutes les traductions pour JavaScript
     */
    public function translations($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        // Vérifier que la locale est supportée
        $availableLocales = config('app.available_locales', ['fr', 'en']);
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'fr');
        }
        
        try {
            $translations = include resource_path("lang/{$locale}/messages.php");
            
            return response()->json([
                'locale' => $locale,
                'translations' => $translations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Impossible de charger les traductions',
                'locale' => $locale
            ], 500);
        }
    }
}
