@extends('dashboard.layout')

@section('title', 'Paramètres')

@section('content')
<h1 class="text-2xl md:text-3xl font-bold mb-8">Paramètres du site</h1>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto mb-10">
    <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du site</label>
            <input type="text" value="Jatsmanor" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="3">Résidences meublées premium à Abidjan</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
            <input type="email" value="contact@jatsmanor.ci" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input type="tel" value="+225 07 07 07 07" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input type="text" value="123 Cocody Résidentiel, Abidjan" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Sauvegarder
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">Paramètres de réservation</h2>
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Durée minimale de séjour (nuits)</label>
            <input type="number" min="1" value="1" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Durée maximale de séjour (nuits)</label>
            <input type="number" min="1" value="30" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Délai d'annulation (heures)</label>
            <input type="number" min="0" value="24" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Frais de nettoyage (FCFA)</label>
            <input type="number" min="0" value="15000" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Caution de sécurité (FCFA)</label>
            <input type="number" min="0" value="50000" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center">
            <input type="checkbox" id="auto-confirm" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="auto-confirm" class="ml-2 block text-sm text-gray-900">
                Confirmation automatique des réservations
            </label>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Sauvegarder
            </button>
        </div>
    </form>
</div>
@endsection
