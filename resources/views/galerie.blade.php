@extends('layouts.app')

@section('title', 'Galerie - Jatsmanor')

@section('content')

    <!-- Galerie -->
    <main class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">
            {{ $pageTitle ?? 'Galerie Jatsmanor' }}
        </h2>
        
        @if(isset($subtitle))
            <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
                {{ $subtitle }}
            </p>
        @endif
        
        <!-- Filter buttons (optional) -->
        @if(isset($categories) && count($categories) > 0)
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <button class="filter-btn active bg-blue-800 text-white px-4 py-2 rounded text-sm" data-filter="all">
                    Tout voir
                </button>
                @foreach($categories as $category)
                    <button class="filter-btn bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm" data-filter="{{ strtolower($category) }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        @endif
        
        @include('partials.gallery-grid')
    </main>

    <!-- Modal pour afficher les images en grand -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <!-- Bouton fermer -->
            <button id="closeModalBtn" class="absolute -top-12 right-0 text-white text-3xl hover:text-gray-300 z-20">
                ✕
            </button>
            
            <!-- Image -->
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            
            <!-- Informations -->
            <div class="absolute bottom-4 left-4 right-4 text-white">
                <div class="bg-black bg-opacity-70 p-4 rounded-lg">
                    <h3 id="modalTitle" class="text-xl font-bold mb-2"></h3>
                    <p id="modalDescription" class="text-sm opacity-90"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Variables globales
let modal, modalImage, modalTitle, modalDescription, closeBtn;

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    modal = document.getElementById('galleryModal');
    modalImage = document.getElementById('modalImage');
    modalTitle = document.getElementById('modalTitle');
    modalDescription = document.getElementById('modalDescription');
    closeBtn = document.getElementById('closeModalBtn');
    
    // Ajouter les événements de fermeture
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    // Fermer en cliquant sur le fond
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});

// Fonction pour ouvrir le modal
function openModal(imageSrc, title, description) {
    console.log('Opening modal with:', imageSrc, title, description);
    
    if (!modal) {
        console.error('Modal not found');
        return;
    }
    
    modalImage.src = imageSrc;
    modalImage.alt = title || '';
    modalTitle.textContent = title || '';
    modalDescription.textContent = description || '';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

// Fonction pour fermer le modal
function closeModal() {
    console.log('Closing modal');
    
    if (!modal) {
        console.error('Modal not found');
        return;
    }
    
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Rendre les fonctions globales
window.openModal = openModal;
window.closeModal = closeModal;
// Gestion des filtres
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.bg-white.rounded.shadow');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-800', 'text-white');
                btn.classList.add('bg-gray-200', 'hover:bg-gray-300');
            });
            
            this.classList.add('active', 'bg-blue-800', 'text-white');
            this.classList.remove('bg-gray-200', 'hover:bg-gray-300');
            
            // Filtrer les éléments
            galleryItems.forEach(item => {
                const categoryElement = item.querySelector('.absolute.top-2.left-2 span, .absolute.top-2.left-2');
                const category = categoryElement ? categoryElement.textContent.toLowerCase() : '';
                
                if (filter === 'all' || category.includes(filter)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<style>
#galleryModal {
    transition: all 0.3s ease;
}
</style>
@endpush
