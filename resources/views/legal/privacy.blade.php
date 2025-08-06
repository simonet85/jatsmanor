@extends('layouts.app')

@section('title', 'Politique de Confidentialité')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Politique de Confidentialité</h1>
        
        <div class="prose prose-lg max-w-none">
            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">1. Collecte des données</h2>
            <p class="text-gray-600 mb-4">
                JatsManor collecte des informations personnelles uniquement dans le cadre de la réservation de nos services 
                et pour améliorer l'expérience utilisateur sur notre site.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">2. Types de données collectées</h2>
            <ul class="list-disc list-inside text-gray-600 mb-4 space-y-2">
                <li>Informations d'identification (nom, prénom, email)</li>
                <li>Informations de contact (téléphone, adresse)</li>
                <li>Données de réservation (dates, préférences)</li>
                <li>Données de navigation (cookies, adresse IP)</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">3. Utilisation des données</h2>
            <p class="text-gray-600 mb-4">
                Vos données personnelles sont utilisées pour :
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-4 space-y-2">
                <li>Traiter vos réservations</li>
                <li>Vous contacter concernant nos services</li>
                <li>Améliorer nos services</li>
                <li>Respecter nos obligations légales</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">4. Protection des données</h2>
            <p class="text-gray-600 mb-4">
                Nous mettons en place des mesures techniques et organisationnelles appropriées pour protéger vos données 
                personnelles contre tout accès non autorisé, modification, divulgation ou destruction.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">5. Partage des données</h2>
            <p class="text-gray-600 mb-4">
                Vos données personnelles ne sont pas vendues, échangées ou transmises à des tiers sans votre consentement, 
                sauf dans les cas prévus par la loi.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">6. Vos droits</h2>
            <p class="text-gray-600 mb-4">
                Conformément au RGPD, vous disposez des droits suivants :
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-4 space-y-2">
                <li>Droit d'accès à vos données</li>
                <li>Droit de rectification</li>
                <li>Droit à l'effacement</li>
                <li>Droit à la portabilité</li>
                <li>Droit d'opposition</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">7. Cookies</h2>
            <p class="text-gray-600 mb-4">
                Notre site utilise des cookies pour améliorer votre expérience de navigation. Vous pouvez désactiver 
                les cookies dans les paramètres de votre navigateur.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">8. Contact</h2>
            <p class="text-gray-600 mb-4">
                Pour exercer vos droits ou pour toute question concernant cette politique de confidentialité, 
                contactez-nous via notre <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">formulaire de contact</a>.
            </p>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Dernière mise à jour : {{ date('d/m/Y') }}
            </p>
            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
