<!-- Modal de galerie moderne -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-90 transition-opacity duration-300"></div>
    
    <!-- Modal Container -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <!-- Modal Content -->
        <div class="relative max-w-7xl max-h-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-b from-black to-transparent p-6">
                <div class="flex items-center justify-between text-white">
                    <div class="flex items-center space-x-4">
                        <div id="modalCategoryBadge" class="hidden px-3 py-1 bg-blue-600 rounded-full text-sm font-medium">
                            <i class="fas fa-tag mr-2"></i>
                            <span></span>
                        </div>
                    </div>
                    <button id="closeModalBtn" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-full transition-colors duration-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Image Container -->
            <div class="relative">
                <img id="modalImage" 
                     src="" 
                     alt="" 
                     class="max-w-full max-h-[80vh] object-contain mx-auto block">
                
                <!-- Loading Spinner -->
                <div id="modalLoader" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-white p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-2"></h3>
                        <p id="modalDescription" class="text-gray-600 text-lg leading-relaxed"></p>
                    </div>
                    <div class="ml-6 flex space-x-3">
                        <button id="downloadBtn" class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200" title="Télécharger l'image">
                            <i class="fas fa-download"></i>
                        </button>
                        <button id="shareBtn" class="p-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200" title="Partager">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                    <button id="prevBtn" class="flex items-center px-4 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chevron-left mr-2"></i>
                        Précédent
                    </button>
                    
                    <div class="flex items-center space-x-2">
                        <span id="modalCounter" class="text-sm text-gray-500"></span>
                        <div class="flex space-x-1">
                            <!-- Dots de navigation seront ajoutés ici -->
                        </div>
                    </div>
                    
                    <button id="nextBtn" class="flex items-center px-4 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                        Suivant
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables du modal
let modal, modalImage, modalTitle, modalDescription, modalCategoryBadge, modalLoader;
let closeBtn, prevBtn, nextBtn, downloadBtn, shareBtn, modalCounter;
let currentImageIndex = 0;
let modalImages = [];

// Initialisation du modal
document.addEventListener('DOMContentLoaded', function() {
    initializeModal();
});

function initializeModal() {
    // Récupérer les éléments du DOM
    modal = document.getElementById('galleryModal');
    modalImage = document.getElementById('modalImage');
    modalTitle = document.getElementById('modalTitle');
    modalDescription = document.getElementById('modalDescription');
    modalCategoryBadge = document.getElementById('modalCategoryBadge');
    modalLoader = document.getElementById('modalLoader');
    closeBtn = document.getElementById('closeModalBtn');
    prevBtn = document.getElementById('prevBtn');
    nextBtn = document.getElementById('nextBtn');
    downloadBtn = document.getElementById('downloadBtn');
    shareBtn = document.getElementById('shareBtn');
    modalCounter = document.getElementById('modalCounter');
    
    // Préparer la liste des images pour la navigation
    prepareImagesList();
    
    // Ajouter les événements
    addModalEventListeners();
    
    console.log('Modal initialisé avec', modalImages.length, 'images');
}

function prepareImagesList() {
    modalImages = [];
    const galleryItems = document.querySelectorAll('.gallery-item:not(.hidden) img[onclick]');
    
    galleryItems.forEach((img, index) => {
        const item = img.closest('.gallery-item');
        const category = item.getAttribute('data-category');
        
        modalImages.push({
            src: img.src,
            title: img.alt,
            description: img.getAttribute('onclick').match(/'([^']*)'/) ? img.getAttribute('onclick').match(/'[^']*',\s*'[^']*',\s*'([^']*)'/)?.[1] || '' : '',
            category: category,
            element: img
        });
    });
}

function addModalEventListeners() {
    // Fermeture
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    // Fermer en cliquant sur le backdrop
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Navigation clavier
    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    closeModal();
                    break;
                case 'ArrowLeft':
                    showPreviousImage();
                    break;
                case 'ArrowRight':
                    showNextImage();
                    break;
            }
        }
    });
    
    // Boutons de navigation
    if (prevBtn) {
        prevBtn.addEventListener('click', showPreviousImage);
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', showNextImage);
    }
    
    // Téléchargement
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadCurrentImage);
    }
    
    // Partage
    if (shareBtn) {
        shareBtn.addEventListener('click', shareCurrentImage);
    }
}

// Fonction pour ouvrir le modal
window.openModal = function(imageSrc, title, description, category) {
    console.log('Ouverture du modal:', { imageSrc, title, description, category });
    
    // Trouver l'index de l'image actuelle
    currentImageIndex = modalImages.findIndex(img => img.src === imageSrc);
    if (currentImageIndex === -1) currentImageIndex = 0;
    
    // Afficher le modal
    showModalImage(currentImageIndex);
    
    // Afficher le modal avec animation
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        modal.querySelector('.absolute.inset-0').style.opacity = '1';
        modal.querySelector('.relative.flex').style.transform = 'scale(1)';
    });
};

function showModalImage(index) {
    if (index < 0 || index >= modalImages.length) return;
    
    const imageData = modalImages[index];
    currentImageIndex = index;
    
    // Afficher le loader
    modalLoader.style.display = 'flex';
    modalImage.style.opacity = '0';
    
    // Charger l'image
    const img = new Image();
    img.onload = function() {
        modalImage.src = imageData.src;
        modalImage.alt = imageData.title;
        modalTitle.textContent = imageData.title;
        modalDescription.textContent = imageData.description;
        
        // Afficher la catégorie
        if (imageData.category) {
            modalCategoryBadge.classList.remove('hidden');
            modalCategoryBadge.querySelector('span').textContent = imageData.category;
        } else {
            modalCategoryBadge.classList.add('hidden');
        }
        
        // Masquer le loader et afficher l'image
        modalLoader.style.display = 'none';
        modalImage.style.opacity = '1';
        
        // Mettre à jour le compteur
        modalCounter.textContent = `${index + 1} / ${modalImages.length}`;
        
        // Mettre à jour les boutons de navigation
        prevBtn.style.opacity = index > 0 ? '1' : '0.5';
        prevBtn.disabled = index === 0;
        nextBtn.style.opacity = index < modalImages.length - 1 ? '1' : '0.5';
        nextBtn.disabled = index === modalImages.length - 1;
    };
    
    img.onerror = function() {
        modalLoader.style.display = 'none';
        modalImage.src = '{{ asset("images/placeholder.jpg") }}';
        modalImage.style.opacity = '1';
    };
    
    img.src = imageData.src;
}

function showPreviousImage() {
    if (currentImageIndex > 0) {
        showModalImage(currentImageIndex - 1);
    }
}

function showNextImage() {
    if (currentImageIndex < modalImages.length - 1) {
        showModalImage(currentImageIndex + 1);
    }
}

function closeModal() {
    console.log('Fermeture du modal');
    
    // Animation de sortie
    const backdrop = modal.querySelector('.absolute.inset-0');
    const content = modal.querySelector('.relative.flex');
    
    backdrop.style.opacity = '0';
    content.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset des styles
        backdrop.style.opacity = '';
        content.style.transform = '';
    }, 300);
}

function downloadCurrentImage() {
    if (modalImages[currentImageIndex]) {
        const link = document.createElement('a');
        link.href = modalImages[currentImageIndex].src;
        link.download = `${modalImages[currentImageIndex].title}.jpg`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

function shareCurrentImage() {
    if (navigator.share && modalImages[currentImageIndex]) {
        navigator.share({
            title: modalImages[currentImageIndex].title,
            text: modalImages[currentImageIndex].description,
            url: window.location.href
        });
    } else {
        // Fallback - copier l'URL dans le presse-papier
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Lien copié dans le presse-papier !');
        });
    }
}

// Mettre à jour la liste des images quand le filtre change
document.addEventListener('filterChanged', function() {
    prepareImagesList();
});
</script>
@endpush

<style>
#galleryModal {
    backdrop-filter: blur(10px);
}

#galleryModal .relative.flex {
    transform: scale(0.95);
    transition: transform 0.3s ease;
}

#galleryModal:not(.hidden) .relative.flex {
    transform: scale(1);
}

#modalImage {
    transition: opacity 0.3s ease;
}

/* Animations pour les boutons */
#galleryModal button {
    transition: all 0.2s ease;
}

#galleryModal button:hover {
    transform: translateY(-1px);
}

#galleryModal button:active {
    transform: translateY(0);
}
</style>
