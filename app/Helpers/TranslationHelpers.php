<?php

if (!function_exists('getTranslatedField')) {
    /**
     * Get translated field based on current locale
     * 
     * @param object $model
     * @param string $field
     * @return string
     */
    function getTranslatedField($model, $field)
    {
        $locale = app()->getLocale();
        
        // Si on est en anglais et que le champ traduit existe et n'est pas vide
        if ($locale === 'en') {
            $translatedField = $field . '_en';
            if (isset($model->$translatedField) && !empty($model->$translatedField)) {
                return $model->$translatedField;
            }
        }
        
        // Fallback vers le champ français
        return $model->$field ?? '';
    }
}

if (!function_exists('getResidenceName')) {
    /**
     * Get residence name in current locale
     * 
     * @param object $residence
     * @return string
     */
    function getResidenceName($residence)
    {
        return getTranslatedField($residence, 'name');
    }
}

if (!function_exists('getResidenceDescription')) {
    /**
     * Get residence description in current locale
     * 
     * @param object $residence
     * @return string
     */
    function getResidenceDescription($residence)
    {
        return getTranslatedField($residence, 'description');
    }
}

if (!function_exists('getResidenceShortDescription')) {
    /**
     * Get residence short description in current locale
     * 
     * @param object $residence
     * @return string
     */
    function getResidenceShortDescription($residence)
    {
        return getTranslatedField($residence, 'short_description');
    }
}
