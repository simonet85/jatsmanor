<?php

if (!function_exists('__t')) {
    /**
     * Helper pour les traductions avec fallback
     */
    function __t($key, $replace = [], $locale = null)
    {
        return trans("messages.{$key}", $replace, $locale);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Obtenir la locale actuelle
     */
    function current_locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('available_locales')) {
    /**
     * Obtenir les locales disponibles
     */
    function available_locales()
    {
        return config('app.available_locales', ['fr', 'en']);
    }
}

if (!function_exists('locale_name')) {
    /**
     * Obtenir le nom complet d'une locale
     */
    function locale_name($locale)
    {
        $names = [
            'fr' => 'Français',
            'en' => 'English',
        ];
        
        return $names[$locale] ?? $locale;
    }
}

if (!function_exists('locale_flag')) {
    /**
     * Obtenir l'emoji drapeau pour une locale
     */
    function locale_flag($locale)
    {
        $flags = [
            'fr' => '🇫🇷',
            'en' => '🇬🇧',
        ];
        
        return $flags[$locale] ?? '🌐';
    }
}

if (!function_exists('route_with_locale')) {
    /**
     * Générer une URL avec la locale
     */
    function route_with_locale($name, $parameters = [], $locale = null)
    {
        $locale = $locale ?: current_locale();
        $parameters['lang'] = $locale;
        
        return route($name, $parameters);
    }
}
