@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-4">Résultats de recherche</h1>
        <p class="text-blue-100">
            {{ $residences->count() }} résidence(s) disponible(s) pour {{ $nights }} nuit(s)
            du {{ $arrival_date->format('d/m/Y') }} au {{ $departure_date->format('d/m/Y') }}
            pour {{ $guests }} invité(s)
        </p>
    </div>
</div>

<!-- Formulaire de recherche modifié -->
@include('partials.search-blade', ['marginTop' => '-mt-8'])

<div class="max-w-7xl mx-auto px-4 py-12">
    @if($residences->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($residences as $residence)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    @if($residence->images->first())
                        <img src="{{ asset('storage/' . $residence->images->first()->image_path) }}" 
                             alt="{{ $residence->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $residence->name }}</h3>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $residence->location }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-users mr-1"></i>Jusqu'à {{ $residence->capacity }} invité(s)
                        </p>
                        @if($residence->surface)
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-expand-arrows-alt mr-1"></i>{{ $residence->surface }} m²
                            </p>
                        @endif
                        
                        <!-- Équipements -->
                        @if($residence->amenities->count() > 0)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($residence->amenities->take(3) as $amenity)
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                            {{ $amenity->name }}
                                        </span>
                                    @endforeach
                                    @if($residence->amenities->count() > 3)
                                        <span class="text-gray-500 text-xs">
                                            +{{ $residence->amenities->count() - 3 }} autres
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Prix -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ format_fcfa($residence->price_per_night) }}
                                </p>
                                <p class="text-sm text-gray-500">par nuit</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold">
                                    {{ format_fcfa($residence->price_per_night * $nights) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $nights }} nuit(s)</p>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('residences.show', $residence) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-center py-2 px-4 rounded transition-colors">
                                Voir détails
                            </a>
                            <a href="{{ route('booking.create', ['residence' => $residence, 'arrival_date' => $arrival_date->format('Y-m-d'), 'departure_date' => $departure_date->format('Y-m-d'), 'guests' => $guests]) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition-colors">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination si nécessaire -->
        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                ← Retour à l'accueil
            </a>
        </div>
    @else
        <div class="text-center py-16">
            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Aucune résidence disponible</h2>
            <p class="text-gray-600 mb-8">
                Nous n'avons trouvé aucune résidence disponible pour vos critères de recherche.
                Essayez de modifier vos dates ou le nombre d'invités.
            </p>
            
            <div class="max-w-md mx-auto">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Suggestions :</h3>
                <ul class="text-left text-gray-600 space-y-2">
                    <li>• Essayez des dates différentes</li>
                    <li>• Réduisez le nombre d'invités</li>
                    <li>• Consultez toutes nos résidences</li>
                </ul>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('residences') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Voir toutes les résidences
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
