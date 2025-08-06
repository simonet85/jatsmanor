<?php

if (!function_exists('format_fcfa')) {
    /**
     * Formate un montant en FCFA avec des séparateurs de milliers
     * 
     * @param float|int $amount
     * @return string
     */
    function format_fcfa($amount)
    {
        return number_format($amount, 0, ',', '.') . ' FCFA';
    }
}

if (!function_exists('format_fcfa_per_night')) {
    /**
     * Formate un montant en FCFA par nuit
     * 
     * @param float|int $amount
     * @return string
     */
    function format_fcfa_per_night($amount)
    {
        return number_format($amount, 0, ',', '.') . ' FCFA / nuit';
    }
}

if (!function_exists('format_local_datetime')) {
    /**
     * Formate une date/heure dans le timezone local
     * 
     * @param \Carbon\Carbon $date
     * @param string $format
     * @return string
     */
    function format_local_datetime($date, $format = 'd/m/Y H:i')
    {
        return $date->setTimezone(config('app.timezone'))->format($format);
    }
}

if (!function_exists('format_local_date')) {
    /**
     * Formate une date dans le timezone local
     * 
     * @param \Carbon\Carbon $date
     * @param string $format
     * @return string
     */
    function format_local_date($date, $format = 'd/m/Y')
    {
        return $date->setTimezone(config('app.timezone'))->format($format);
    }
}

if (!function_exists('translate_status')) {
    /**
     * Traduit les statuts en français
     * 
     * @param string $status
     * @param string $type
     * @return string
     */
    function translate_status($status, $type = 'booking') {
        $translations = [
            'booking' => [
                'pending' => 'En attente',
                'confirmed' => 'Confirmé',
                'completed' => 'Terminé',
                'cancelled' => 'Annulé',
            ],
            'payment' => [
                'pending' => 'En attente',
                'paid' => 'Payé',
                'unpaid' => 'Non payé',
                'failed' => 'Échoué',
                'refunded' => 'Remboursé',
            ]
        ];

        return $translations[$type][$status] ?? ucfirst($status);
    }
}

if (!function_exists('translate_term')) {
    /**
     * Traduit les termes courants en français
     * 
     * @param string $term
     * @return string
     */
    function translate_term($term) {
        $translations = [
            'check-in' => 'Arrivée',
            'check-out' => 'Départ', 
            'check_in' => 'Arrivée',
            'check_out' => 'Départ',
            'guests' => 'Invités',
            'guest' => 'Invité',
            'night' => 'nuit',
            'nights' => 'nuits',
            'person' => 'personne',
            'persons' => 'personnes',
            'people' => 'personnes',
            'total' => 'Total',
            'duration' => 'Durée',
            'residence' => 'Résidence',
            'booking' => 'Réservation',
            'booking_reference' => 'Référence de réservation',
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'company' => 'Entreprise',
            'special_requests' => 'Demandes spéciales',
            'surface' => 'Surface',
            'location' => 'Localisation',
            'status' => 'Statut',
            'payment_status' => 'Statut de paiement',
            'cancel' => 'Annuler',
            'confirm' => 'Confirmer',
            'send' => 'Envoyer',
            'generate' => 'Générer',
            'download' => 'Télécharger',
            'back' => 'Retour',
            'save' => 'Enregistrer',
            'edit' => 'Modifier',
            'delete' => 'Supprimer',
            'view' => 'Voir',
            'details' => 'Détails',
        ];

        return $translations[strtolower($term)] ?? ucfirst($term);
    }
}

if (!function_exists('setting')) {
    /**
     * Récupère une valeur de paramètre
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null) {
        return \App\Models\Setting::get($key, $default);
    }
}
