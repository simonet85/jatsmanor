@extends('layouts.app')

@section('title', 'Conditions Générales')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Conditions Générales d'Utilisation</h1>
        
        <div class="prose prose-lg max-w-none">
            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">1. Objet</h2>
            <p class="text-gray-600 mb-4">
                Les présentes conditions générales d'utilisation (CGU) régissent l'utilisation du site web JatsManor 
                et les services de réservation de résidences proposés.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">2. Acceptation des conditions</h2>
            <p class="text-gray-600 mb-4">
                L'utilisation de ce site implique l'acceptation pleine et entière des conditions générales d'utilisation 
                ci-après décrites. Ces conditions d'utilisation sont susceptibles d'être modifiées ou complétées à tout moment.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">3. Réservations</h2>
            <p class="text-gray-600 mb-4">
                Toute réservation effectuée sur le site est soumise à confirmation. JatsManor se réserve le droit d'annuler 
                ou de modifier une réservation en cas de circonstances exceptionnelles.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">4. Paiement et annulation</h2>
            <p class="text-gray-600 mb-4">
                Le paiement des réservations s'effectue selon les modalités précisées lors de la réservation. 
                Les conditions d'annulation varient selon le type de réservation et sont communiquées avant confirmation.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">5. Responsabilité</h2>
            <p class="text-gray-600 mb-4">
                JatsManor met tout en œuvre pour fournir des informations exactes et à jour. Cependant, 
                nous ne pouvons garantir l'exhaustivité et l'exactitude de toutes les informations présentes sur le site.
            </p>

            <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">6. Contact</h2>
            <p class="text-gray-600 mb-4">
                Pour toute question concernant ces conditions générales, vous pouvez nous contacter via notre 
                <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">formulaire de contact</a>.
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
