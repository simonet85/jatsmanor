<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Informations générales
            [
                'key' => 'site_name',
                'value' => 'Jatsmanor',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nom du site',
                'description' => 'Le nom de votre site web'
            ],
            [
                'key' => 'site_description',
                'value' => 'Résidences meublées premium à Abidjan',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Description du site',
                'description' => 'Description courte de votre site'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@jatsmanor.ci',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email de contact',
                'description' => 'Adresse email principale'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+225 07 07 07 07',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Téléphone',
                'description' => 'Numéro de téléphone principal'
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Cocody Résidentiel, Abidjan',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Adresse',
                'description' => 'Adresse physique complète'
            ],
            
            // Paramètres de réservation
            [
                'key' => 'min_stay_duration',
                'value' => '1',
                'type' => 'number',
                'group' => 'booking',
                'label' => 'Durée minimale de séjour (nuits)',
                'description' => 'Nombre minimum de nuits pour une réservation'
            ],
            [
                'key' => 'max_stay_duration',
                'value' => '30',
                'type' => 'number',
                'group' => 'booking',
                'label' => 'Durée maximale de séjour (nuits)',
                'description' => 'Nombre maximum de nuits pour une réservation'
            ],
            [
                'key' => 'cancellation_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'booking',
                'label' => 'Délai d\'annulation (heures)',
                'description' => 'Nombre d\'heures avant lesquelles on peut annuler'
            ],
            [
                'key' => 'cleaning_fee',
                'value' => '15000',
                'type' => 'number',
                'group' => 'booking',
                'label' => 'Frais de nettoyage (FCFA)',
                'description' => 'Montant des frais de ménage'
            ],
            [
                'key' => 'security_deposit',
                'value' => '50000',
                'type' => 'number',
                'group' => 'booking',
                'label' => 'Caution de sécurité (FCFA)',
                'description' => 'Montant de la caution demandée'
            ],
            [
                'key' => 'auto_confirm_bookings',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'booking',
                'label' => 'Confirmation automatique des réservations',
                'description' => 'Confirmer automatiquement les nouvelles réservations'
            ],
            
            // Paramètres frontend
            [
                'key' => 'hero_title',
                'value' => 'Séjournez dans nos résidences meublées de luxe',
                'type' => 'text',
                'group' => 'frontend',
                'label' => 'Titre principal de la page d\'accueil',
                'description' => 'Le titre affiché sur la section hero'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Confort, sécurité et flexibilité à Abidjan',
                'type' => 'text',
                'group' => 'frontend',
                'label' => 'Sous-titre de la page d\'accueil',
                'description' => 'Le sous-titre affiché sur la section hero'
            ],
            [
                'key' => 'hero_image',
                'value' => 'images/hero-bg.jpg',
                'type' => 'image',
                'group' => 'frontend',
                'label' => 'Image de fond de la section hero',
                'description' => 'Image de fond de la page d\'accueil'
            ],
            
            // Footer
            [
                'key' => 'footer_description',
                'value' => 'Résidences meublées à Abidjan - confort, sécurité et flexibilité à portée de clic.',
                'type' => 'textarea',
                'group' => 'frontend',
                'label' => 'Description du footer',
                'description' => 'Texte de description dans le footer'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
