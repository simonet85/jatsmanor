<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer les locales disponibles depuis la config
        $availableLocales = config('app.available_locales', ['fr', 'en']);
        
        // 1. Vérifier si une langue est spécifiée dans l'URL
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }
        // 2. Vérifier si une langue est en session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
        }
        // 3. Vérifier l'en-tête Accept-Language du navigateur
        elseif ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            // Extraire les langues préférées
            $preferredLanguages = $this->parseAcceptLanguage($acceptLanguage);
            
            foreach ($preferredLanguages as $lang) {
                if (in_array($lang, $availableLocales)) {
                    $locale = $lang;
                    break;
                }
            }
        }
        
        // 4. Fallback vers la langue par défaut
        if (!isset($locale)) {
            $locale = config('app.locale', 'fr');
        }
        
        // Définir la locale pour cette requête
        App::setLocale($locale);
        
        // Sauvegarder en session pour les requêtes suivantes
        if (!Session::has('locale') || Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }
        
        // Partager la locale avec toutes les vues
        view()->share('currentLocale', $locale);
        view()->share('availableLocales', $availableLocales);
        
        return $next($request);
    }
    
    /**
     * Parse Accept-Language header
     */
    private function parseAcceptLanguage($acceptLanguage)
    {
        $languages = [];
        
        foreach (explode(',', $acceptLanguage) as $lang) {
            $lang = trim($lang);
            
            // Séparer la langue de la qualité (q)
            if (strpos($lang, ';') !== false) {
                [$language, ] = explode(';', $lang, 2);
            } else {
                $language = $lang;
            }
            
            // Extraire le code de langue (fr-FR -> fr)
            $language = trim($language);
            if (strpos($language, '-') !== false) {
                [$language, ] = explode('-', $language, 2);
            }
            
            $languages[] = strtolower($language);
        }
        
        return array_unique($languages);
    }
}
