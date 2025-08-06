@extends('layouts.app')

@section('title', $residence->name . ' - Jatsmanor')

@section('content')
<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500">
    <a href="{{ route('residences') }}" class="hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Retour aux résidences
    </a>
</div>

<!-- Détails de la résidence -->
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <!-- Images -->
        <div>
            @php
                // Récupérer l'image principale (soit celle marquée comme principale, soit la première image, soit l'image legacy)
                $primaryImage = null;
                $allImages = $residence->images->sortBy('order_position');
                $primaryImageFromImages = $allImages->where('is_primary', true)->first();
                
                if ($primaryImageFromImages) {
                    $primaryImage = asset('storage/' . $primaryImageFromImages->image_path);
                } elseif ($allImages->count() > 0) {
                    $primaryImage = asset('storage/' . $allImages->first()->image_path);
                } elseif ($residence->image) {
                    $primaryImage = asset($residence->image);
                }
                
                // Calculer le total d'images
                $totalImages = $allImages->count();
                if ($residence->image && $allImages->count() === 0) {
                    $totalImages = 1;
                }
            @endphp
            
            @if($primaryImage)
                <!-- Image principale -->
                <div class="mb-4 relative">
                    <img id="mainImage" 
                         src="{{ $primaryImage }}?v={{ time() }}" 
                         alt="{{ $residence->name }}" 
                         class="w-full h-80 object-cover rounded-lg shadow-lg cursor-pointer transition-transform duration-300 hover:scale-105"
                         loading="lazy"
                         onclick="openImageModal(this.src)"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <div class="w-full h-80 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow-lg flex items-center justify-center" style="display: none;">
                        <i class="fas fa-home text-blue-400 text-4xl"></i>
                    </div>
                    <!-- Badge nombre d'images -->
                    @if($totalImages > 1)
                        <div class="absolute top-4 right-4 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-images mr-1"></i>
                            {{ $totalImages }} photos
                        </div>
                    @endif
                </div>
                
                <!-- Images secondaires (miniatures) -->
                @if($allImages->count() > 1)
                    @php
                        // Afficher les 3 premières images qui ne sont pas l'image principale
                        $secondaryImages = $allImages->where('is_primary', false)->take(3);
                        if ($secondaryImages->count() === 0 && $allImages->count() > 1) {
                            // Si pas d'image marquée comme principale, prendre les images suivantes
                            $secondaryImages = $allImages->skip(1)->take(3);
                        }
                    @endphp
                    @if($secondaryImages->count() > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($secondaryImages as $image)
                                <div class="relative group cursor-pointer" onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}?v={{ time() }}')">
                                    <img src="{{ asset('storage/' . $image->image_path) }}?v={{ time() }}" 
                                         alt="{{ $residence->name }}" 
                                         class="w-full h-24 object-cover rounded shadow transition-all duration-300 group-hover:shadow-lg group-hover:scale-105"
                                         loading="lazy" />
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded"></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @else
                <div class="w-full h-80 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow-lg flex items-center justify-center">
                    <i class="fas fa-home text-blue-400 text-4xl"></i>
                </div>
            @endif
        </div>

        <!-- Informations -->
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $residence->name }}</h1>
            
            @if($residence->reviews->count() > 0)
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex text-yellow-500">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $residence->reviews->avg('rating'))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600">
                        {{ number_format($residence->reviews->avg('rating'), 1) }} 
                        ({{ $residence->reviews->count() }} avis)
                    </span>
                </div>
            @endif

            <div class="flex items-center gap-4 text-gray-600 mb-4">
                <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $residence->location }}</span>
                <span><i class="fas fa-users mr-1"></i> {{ $residence->max_guests }} personnes max</span>
                <span><i class="fas fa-expand-arrows-alt mr-1"></i> {{ $residence->surface }}m²</span>
            </div>

            <div class="text-3xl font-bold text-blue-800 mb-6">
                {{ number_format($residence->price_per_night, 0, ',', '.') }} FCFA
                <span class="text-lg font-normal text-gray-600">/ nuit</span>
            </div>

            <p class="text-gray-700 mb-6">{{ $residence->description }}</p>

            <!-- Équipements -->
            @if($residence->amenities->count() > 0)
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Équipements</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($residence->amenities as $amenity)
                            <div class="flex items-center gap-2 text-sm">
                                <i class="{{ $amenity->icon ?? 'fas fa-check' }} text-green-600"></i>
                                <span>{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulaire de réservation rapide -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="font-semibold mb-4">Réserver maintenant</h3>
                <form action="{{ route('booking.create', $residence) }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Arrivée</label>
                            <input type="date" name="checkin" 
                                   value="{{ request('checkin') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full p-2 border rounded" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Départ</label>
                            <input type="date" name="checkout" 
                                   value="{{ request('checkout') }}"
                                   class="w-full p-2 border rounded" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Invités</label>
                        <select name="guests" class="w-full p-2 border rounded" required>
                            <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 personne</option>
                            <option value="2" {{ request('guests') == '2' || !request('guests') ? 'selected' : '' }}>2 personnes</option>
                            <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 personnes</option>
                            <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 personnes</option>
                        </select>
                    </div>
                    @auth
                        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-lg">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Réserver maintenant
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-lg text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Connectez-vous pour réserver
                        </a>
                    @endauth
                </form>
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Description complète -->
        <div class="md:col-span-2">
            <h2 class="text-2xl font-bold mb-4">À propos de cette résidence</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($residence->long_description ?? $residence->description)) !!}
            </div>

            <!-- Avis clients -->
            @if($residence->reviews->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Avis des clients</h2>
                    <div class="space-y-4">
                        @foreach($residence->reviews->take(3) as $review)
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-semibold">{{ $review->user->name }}</div>
                                    <div class="flex text-yellow-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-xs"></i>
                                            @else
                                                <i class="far fa-star text-xs"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                                <div class="text-xs text-gray-500 mt-2">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Informations de contact -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="font-semibold mb-4">Informations pratiques</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-blue-600"></i>
                        <div>
                            <div class="font-medium">Check-in</div>
                            <div class="text-gray-600">À partir de 14h00</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-blue-600"></i>
                        <div>
                            <div class="font-medium">Check-out</div>
                            <div class="text-gray-600">Avant 11h00</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-ban text-red-600"></i>
                        <div>
                            <div class="font-medium">Annulation</div>
                            <div class="text-gray-600">Gratuite jusqu'à 48h avant</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold mb-4">Besoin d'aide ?</h3>
                <div class="space-y-3 text-sm">
                    <a href="tel:+22507070707" class="flex items-center gap-3 hover:text-blue-600">
                        <i class="fas fa-phone text-blue-600"></i>
                        <span>+225 07 07 07 07</span>
                    </a>
                    <a href="mailto:contact@jatsmanor.ci" class="flex items-center gap-3 hover:text-blue-600">
                        <i class="fas fa-envelope text-blue-600"></i>
                        <span>contact@jatsmanor.ci</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-blue-600"></i>
                        <span>Support 24h/24</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les images en grand -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 items-center justify-center p-4" style="display: none;">
    <div class="relative max-w-4xl max-h-full">
        <!-- Bouton fermer -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Image -->
        <img id="modalImage" src="" alt="{{ $residence->name }}" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        
        <!-- Navigation si plusieurs images -->
        @if($totalImages > 1)
            <button id="prevImageBtn" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="nextImageBtn" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <!-- Indicateur de position -->
            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full text-sm">
                <span id="currentImageIndex">1</span> / <span id="totalImages">{{ $totalImages }}</span>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Variables globales pour la gestion des images
let currentImageIndex = 0;
let allImages = [];

// Initialiser les images au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initializeImages();
});

// Initialiser la liste des images
function initializeImages() {
    allImages = [];
    
    // Ajouter l'image principale en premier (soit celle marquée comme principale, soit la première)
    @if($primaryImageFromImages)
        allImages.push('{{ asset('storage/' . $primaryImageFromImages->image_path) }}?v={{ time() }}');
        // Ajouter les autres images par ordre
        @foreach($allImages->where('is_primary', false)->sortBy('order_position') as $image)
            allImages.push('{{ asset('storage/' . $image->image_path) }}?v={{ time() }}');
        @endforeach
    @elseif($allImages->count() > 0)
        // Si pas d'image principale définie, ajouter toutes les images par ordre
        @foreach($allImages->sortBy('order_position') as $image)
            allImages.push('{{ asset('storage/' . $image->image_path) }}?v={{ time() }}');
        @endforeach
    @elseif($residence->image)
        // Image legacy uniquement
        allImages.push('{{ asset($residence->image) }}?v={{ time() }}');
    @endif
    
    currentImageIndex = 0;
}

// Changer l'image principale
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = imageSrc;
    
    // Mettre à jour l'index actuel
    currentImageIndex = allImages.indexOf(imageSrc);
    if (currentImageIndex === -1) currentImageIndex = 0;
    
    // Animation de transition
    mainImage.style.opacity = '0';
    setTimeout(() => {
        mainImage.style.opacity = '1';
    }, 150);
}

// Ouvrir le modal d'image
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    // Trouver l'index de l'image actuelle
    currentImageIndex = allImages.indexOf(imageSrc);
    if (currentImageIndex === -1) currentImageIndex = 0;
    
    // Afficher l'image dans le modal
    modalImage.src = imageSrc;
    updateImageCounter();
    
    // Afficher le modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Animation d'entrée
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

// Fermer le modal d'image
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    
    // Animation de sortie
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 300);
}

// Image précédente
function previousImage() {
    if (allImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
    const modalImage = document.getElementById('modalImage');
    
    // Animation de transition
    modalImage.style.opacity = '0';
    setTimeout(() => {
        modalImage.src = allImages[currentImageIndex];
        modalImage.style.opacity = '1';
        updateImageCounter();
        
        // Mettre à jour l'image principale aussi
        document.getElementById('mainImage').src = allImages[currentImageIndex];
    }, 150);
}

// Image suivante
function nextImage() {
    if (allImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex + 1) % allImages.length;
    const modalImage = document.getElementById('modalImage');
    
    // Animation de transition
    modalImage.style.opacity = '0';
    setTimeout(() => {
        modalImage.src = allImages[currentImageIndex];
        modalImage.style.opacity = '1';
        updateImageCounter();
        
        // Mettre à jour l'image principale aussi
        document.getElementById('mainImage').src = allImages[currentImageIndex];
    }, 150);
}

// Mettre à jour le compteur d'images
function updateImageCounter() {
    const currentIndexElement = document.getElementById('currentImageIndex');
    if (currentIndexElement) {
        currentIndexElement.textContent = currentImageIndex + 1;
    }
}

// Gestion des touches du clavier
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageModal');
    if (modal.style.display === 'flex') {
        switch(e.key) {
            case 'Escape':
                closeImageModal();
                break;
            case 'ArrowLeft':
                previousImage();
                break;
            case 'ArrowRight':
                nextImage();
                break;
        }
    }
});

// Fermer le modal en cliquant à l'extérieur
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Validation des dates de réservation
document.querySelector('input[name="checkin"]').addEventListener('change', function() {
    const checkinDate = this.value;
    const checkoutInput = document.querySelector('input[name="checkout"]');
    
    if (checkinDate) {
        const minCheckout = new Date(checkinDate);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutInput.min = minCheckout.toISOString().split('T')[0];
        
        if (checkoutInput.value && new Date(checkoutInput.value) <= new Date(checkinDate)) {
            checkoutInput.value = minCheckout.toISOString().split('T')[0];
        }
    }
});

// Style CSS pour les transitions
const style = document.createElement('style');
style.textContent = `
    #imageModal {
        transition: opacity 0.3s ease;
    }
    
    #modalImage {
        transition: opacity 0.15s ease;
    }
    
    #mainImage {
        transition: opacity 0.15s ease, transform 0.3s ease;
    }
    
    .group:hover #mainImage {
        transform: scale(1.02);
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
