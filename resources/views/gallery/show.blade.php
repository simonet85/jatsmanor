@extends('layouts.app')

@section('title', $pageTitle . ' - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <section class="relative bg-gradient-to-r from-blue-800 to-blue-600 text-white py-16">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4">
            <nav class="mb-6">
                <a href="{{ route('galerie') }}" class="inline-flex items-center text-blue-200 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la galerie
                </a>
            </nav>
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $pageTitle }}</h1>
                <p class="text-xl opacity-90 max-w-3xl mx-auto">{{ $subtitle }}</p>
                <div class="mt-6 inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-full">
                    <i class="fas fa-images mr-2"></i>
                    <span>{{ $images->count() }} photos</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Residence Info -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-2">
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-600 leading-relaxed">{{ $residence->description }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @if($residence->location)
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                        <span class="font-medium">Localisation:</span>
                        <span class="ml-2 text-gray-600">{{ $residence->location }}</span>
                    </div>
                    @endif
                    
                    @if($residence->size)
                    <div class="flex items-center">
                        <i class="fas fa-ruler-combined text-blue-600 mr-3"></i>
                        <span class="font-medium">Surface:</span>
                        <span class="ml-2 text-gray-600">{{ $residence->size }} m²</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-blue-600 mr-3"></i>
                        <span class="font-medium">Ajouté le:</span>
                        <span class="ml-2 text-gray-600">{{ $residence->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Images Gallery -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            @if($images->count() > 0)
                <!-- Primary Image -->
                @php
                    $primaryImage = $images->where('is_primary', true)->first() ?? $images->first();
                    $otherImages = $images->where('id', '!=', $primaryImage->id ?? null);
                @endphp
                
                @if($primaryImage)
                <div class="mb-8">
                    <div class="relative group rounded-2xl overflow-hidden shadow-2xl">
                        <img 
                            src="{{ str_starts_with($primaryImage->image_path, 'storage/') ? asset($primaryImage->image_path) : asset('storage/' . $primaryImage->image_path) }}"
                            alt="{{ $residence->name }}"
                            class="w-full h-96 md:h-[500px] object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                            onclick="openModal('{{ str_starts_with($primaryImage->image_path, 'storage/') ? asset($primaryImage->image_path) : asset('storage/' . $primaryImage->image_path) }}', '{{ $residence->name }}', '{{ $primaryImage->description ?? $residence->description }}', '{{ $residence->location }}')"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-6 left-6 right-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold mb-2">Image principale</h3>
                                        @if($primaryImage->description)
                                        <p class="text-lg opacity-90">{{ $primaryImage->description }}</p>
                                        @endif
                                    </div>
                                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                                        <i class="fas fa-search-plus text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-6 left-6">
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i>
                                Image principale
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Other Images Grid -->
                @if($otherImages->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($otherImages as $image)
                    <div class="group relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <img 
                            src="{{ str_starts_with($image->image_path, 'storage/') ? asset($image->image_path) : asset('storage/' . $image->image_path) }}"
                            alt="{{ $residence->name }}"
                            class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer"
                            onclick="openModal('{{ str_starts_with($image->image_path, 'storage/') ? asset($image->image_path) : asset('storage/' . $image->image_path) }}', '{{ $residence->name }}', '{{ $image->description ?? $residence->description }}', '{{ $residence->location }}')"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                @if($image->description)
                                <p class="text-sm font-medium truncate">{{ $image->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="bg-white bg-opacity-90 rounded-full p-3">
                                <i class="fas fa-search-plus text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                {{ $loop->iteration + 1 }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            @else
                <div class="text-center py-20">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-images text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Aucune image disponible</h3>
                    <p class="text-gray-500">Les images de cette résidence seront bientôt disponibles.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Related Residences -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Autres résidences</h2>
            <div class="text-center">
                <a href="{{ route('galerie') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-images mr-2"></i>
                    Voir toute la galerie
                </a>
            </div>
        </div>
    </section>
</div>

@include('gallery.partials.modal')
@endsection

@push('scripts')
<script>
// Préparer les images pour le modal
document.addEventListener('DOMContentLoaded', function() {
    // Cette fonction sera gérée par le modal
    console.log('Page de résidence chargée');
});
</script>
@endpush
