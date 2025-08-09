@extends('layouts.app')

@section('title', 'Galerie - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <!-- Header Section -->
    <section class="relative bg-gradient-to-r from-blue-800 to-blue-600 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ trans('messages.gallery.title') }}</h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">{{ trans('messages.gallery.subtitle') }}</p>
        </div>
    </section>

    <!-- Filter Section -->
    @if(isset($categories) && count($categories) > 0)
    <section class="bg-white shadow-sm py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-3">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-th-large mr-2"></i>
                    {{ __('messages.gallery.view_all') }}
                </button>
                @foreach($categories as $category)
                    @php
                        $key = 'messages.gallery.' . $category;
                        $t = __($key);
                        if ($t === $key) {
                            $t = \Illuminate\Support\Str::headline(\Illuminate\Support\Str::after($category, 'category_'));
                        }
                    @endphp
                    <button class="filter-btn" data-filter="{{ strtolower($category) }}">
                        <i class="fas fa-building mr-2"></i>
                        {{ $t }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Gallery Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            @if(isset($galleryItems) && $galleryItems->count() > 0)
                <div id="galleryGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($galleryItems as $item)
                        <div class="gallery-item" data-category="{{ strtolower($item['category'] ?? '') }}">
                            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                                <!-- Image -->
                                <div class="relative overflow-hidden bg-gray-200">
                                    <img 
                                        src="{{ str_starts_with($item['image_path'], 'storage/') ? asset($item['image_path']) : asset('storage/' . $item['image_path']) }}"
                                        alt="{{ $item['title'] }}"
                                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer gallery-image"
                                        data-src="{{ asset('storage/' . $item['image_path']) }}"
                                        data-title="{{ $item['title'] }}"
                                        data-description="{{ $item['description'] ?? '' }}"
                                        data-category="{{ $item['category'] ?? '' }}"
                                        loading="lazy"
                                        onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror=null;"
                                    />
                                    
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-4 left-4 right-4 text-white">
                                            <h3 class="font-bold text-lg mb-1 truncate">{{ $item['title'] }}</h3>
                                            <p class="text-sm opacity-90 line-clamp-2">{{ Str::limit($item['description'], 80) }}</p>
                                        </div>
                                    </div>

                                    <!-- Category Badge -->
                                    @if(isset($item['category']))
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white shadow-lg">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ ucfirst($item['category']) }}
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Primary Badge -->
                                    @if($item['is_primary'])
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white shadow-lg">
                                            <i class="fas fa-star mr-1"></i>
                                            Principal
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Zoom Icon -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-white bg-opacity-90 rounded-full p-3 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                            <i class="fas fa-search-plus text-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-images text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Aucune image disponible</h3>
                    <p class="text-gray-500">Les images de la galerie seront bientôt disponibles.</p>
                </div>
            @endif
        </div>
    </section>
</div>

@include('gallery.partials.modal')
@endsection

@push('styles')
<style>
.filter-btn {
    @apply px-6 py-3 rounded-full font-medium transition-all duration-300 transform hover:scale-105;
    @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
}

.filter-btn.active {
    @apply bg-blue-600 text-white shadow-lg;
}

.gallery-item {
    opacity: 1;
    transform: scale(1);
    transition: all 0.3s ease;
}

.gallery-item.hidden {
    opacity: 0;
    transform: scale(0.8);
    pointer-events: none;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gallery-item {
    animation: fadeInUp 0.6s ease forwards;
}

.gallery-item:nth-child(1) { animation-delay: 0.1s; }
.gallery-item:nth-child(2) { animation-delay: 0.2s; }
.gallery-item:nth-child(3) { animation-delay: 0.3s; }
.gallery-item:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@push('scripts')
<script>
// Variables globales
let currentFilter = 'all';
let galleryItems = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé, initialisation de la galerie...');
    initializeGallery();
    initializeFilters();
});

function initializeGallery() {
    galleryItems = document.querySelectorAll('.gallery-item');
    console.log(`Galerie initialisée avec ${galleryItems.length} items`);
    
    // Debug: afficher les catégories de chaque item
    galleryItems.forEach((item, index) => {
        const category = item.getAttribute('data-category');
        console.log(`Item ${index}: catégorie = "${category}"`);
    });
    
    // Add click event listeners to gallery images
    const galleryImages = document.querySelectorAll('.gallery-image');
    galleryImages.forEach(img => {
        img.addEventListener('click', function() {
            const src = this.getAttribute('data-src');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const category = this.getAttribute('data-category');
            openModal(src, title, description, category);
        });
    });
}

function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    console.log(`${filterButtons.length} boutons de filtre trouvés`);
    
    filterButtons.forEach(button => {
        const filter = button.getAttribute('data-filter');
        console.log(`Bouton trouvé: ${filter}`);
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            console.log(`Filtre cliqué: ${filter}`);
            
            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Appliquer le filtre
            filterGallery(filter);
            
            // Mettre à jour l'URL sans recharger la page
            const url = new URL(window.location);
            if (filter === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', filter);
            }
            window.history.pushState({}, '', url);
        });
    });
}

function filterGallery(filter) {
    console.log(`Application du filtre: ${filter}`);
    currentFilter = filter;
    let visibleCount = 0;
    
    galleryItems.forEach((item, index) => {
        const category = item.getAttribute('data-category');
        const shouldShow = filter === 'all' || category === filter || category.includes(filter.toLowerCase());
        
        console.log(`Item ${index}: catégorie="${category}", filtre="${filter}", visible=${shouldShow}`);
        
        if (shouldShow) {
            item.classList.remove('hidden');
            item.style.display = 'block';
            visibleCount++;
            // Animation d'apparition échelonnée
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, index * 50);
        } else {
            item.classList.add('hidden');
            item.style.display = 'none';
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
        }
    });
    
    console.log(`Filtre appliqué: ${filter}, ${visibleCount} items visibles`);
    
    // Déclencher l'événement personnalisé pour le modal
    document.dispatchEvent(new CustomEvent('filterChanged'));
}

// Fonction pour ouvrir le modal (sera surchargée par le modal)
window.openModal = function(imageSrc, title, description, category) {
    console.log('Opening modal:', { imageSrc, title, description, category });
    // Cette fonction sera implémentée dans le modal
};

// Initialiser le filtre depuis l'URL au chargement
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryFromUrl = urlParams.get('category');
    
    if (categoryFromUrl && categoryFromUrl !== 'all') {
        console.log(`Filtre depuis URL: ${categoryFromUrl}`);
        
        // Activer le bon bouton
        const targetButton = document.querySelector(`[data-filter="${categoryFromUrl}"]`);
        if (targetButton) {
            targetButton.click();
        }
    }
});
</script>
@endpush
