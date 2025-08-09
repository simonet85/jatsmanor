@extends('dashboard.layout')

@section('title', 'Gérer les résidences')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Gérer les résidences</h1>
            <p class="mt-2 text-sm text-gray-600">Gérez vos résidences, ajoutez de nouvelles propriétés et modifiez les existantes.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-sm transition-colors duration-200" 
                    id="btn-add-residence">
                <i class="fas fa-plus mr-2"></i>Ajouter une résidence
            </button>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="search-input" 
                           placeholder="Rechercher une résidence..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64">
                </div>
                <select id="status-filter" 
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les résidences</option>
                    <option value="active">Actives</option>
                    <option value="inactive">Inactives</option>
                </select>
            </div>
            <div class="text-sm text-gray-500">
                Total: <span id="total-count">{{ $residences->total() }}</span> résidences
            </div>
        </div>
    </div>

    <!-- Tableau des résidences -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Résidence
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Localisation
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Prix/Nuit
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                @include('dashboard.partials.residences-table', compact('residences'))
            </table>
        </div>
        
        <!-- Pagination -->
        @if($residences->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $residences->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Ajouter/Éditer -->
<div id="modal-residence" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900" id="modal-title">Ajouter une résidence</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="residence-form" enctype="multipart/form-data" class="p-6">
            <input type="hidden" id="residence-slug" name="residence_slug">
            <input type="hidden" id="form-method" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la résidence *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                    <div class="text-red-500 text-sm mt-1 hidden" id="name-error"></div>
                </div>

                <!-- Localisation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                    <div class="text-red-500 text-sm mt-1 hidden" id="location-error"></div>
                </div>

                <!-- Prix par nuit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix par nuit (FCFA) *</label>
                    <input type="number" 
                           id="price_per_night" 
                           name="price_per_night" 
                           min="0" 
                           step="1000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                    <div class="text-red-500 text-sm mt-1 hidden" id="price_per_night-error"></div>
                </div>

                <!-- Superficie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Superficie (m²)</label>
                    <input type="number" 
                           id="size" 
                           name="size" 
                           min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="text-red-500 text-sm mt-1 hidden" id="size-error"></div>
                </div>

                <!-- Nombre d'invités max -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invités maximum *</label>
                    <input type="number" 
                           id="max_guests" 
                           name="max_guests" 
                           min="1" 
                           max="20"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                    <div class="text-red-500 text-sm mt-1 hidden" id="max_guests-error"></div>
                </div>

                <!-- Description courte -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description courte</label>
                    <input type="text" 
                           id="short_description" 
                           name="short_description" 
                           maxlength="255"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Une description brève de la résidence">
                    <div class="text-red-500 text-sm mt-1 hidden" id="short_description-error"></div>
                </div>

                <!-- Description complète -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description complète *</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              required></textarea>
                    <div class="text-red-500 text-sm mt-1 hidden" id="description-error"></div>
                </div>

                <!-- Image principale -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image principale de la résidence</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="image-placeholder">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Cliquez pour télécharger une image</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                            </div>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <div class="text-red-500 text-sm mt-1 hidden" id="image-error"></div>
                    <div id="current-image" class="mt-2 hidden">
                        <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                        <img id="current-image-preview" src="" alt="Image actuelle" class="w-20 h-20 object-cover rounded-lg">
                    </div>
                </div>

                <!-- Images secondaires -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Images secondaires (jusqu'à 10 images)</label>
                    
                    <!-- Zone d'upload multiple -->
                    <div class="flex items-center justify-center w-full mb-4">
                        <label for="additional-images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-images text-blue-400 text-2xl mb-2"></i>
                                <p class="text-sm text-blue-600">Cliquez pour ajouter des images</p>
                                <p class="text-xs text-blue-500">Sélectionnez plusieurs images (PNG, JPG, GIF jusqu'à 5MB chacune)</p>
                            </div>
                            <input id="additional-images" type="file" class="hidden" accept="image/*" multiple>
                        </label>
                    </div>
                    
                    <!-- Galerie des images existantes -->
                    <div id="images-gallery" class="grid grid-cols-2 md:grid-cols-4 gap-4" style="display: none;"></div>
                    
                    <!-- Preview des nouvelles images à uploader -->
                    <div id="new-images-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" style="display: none;"></div>
                </div>

                <!-- Options -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-6">
                        <label class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Résidence active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Résidence mise en avant</span>
                        </label>
                    </div>
                </div>

                <!-- Équipements -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Équipements disponibles</label>
                    <div id="amenities-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <!-- Les équipements seront chargés dynamiquement ici -->
                    </div>
                    <div class="text-red-500 text-sm mt-1 hidden" id="amenities-error"></div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="closeModal()" 
                        class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Annuler
                </button>
                <button type="submit" 
                        id="submit-btn"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-spinner fa-spin mr-2 hidden" id="loading-spinner"></i>
                    <span id="submit-text">Ajouter</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Container pour les toasts -->
<div id="toast-container" class="fixed top-4 right-4 z-[60] space-y-2"></div>

@endsection

@push('styles')
<style>
    .toast {
        animation: slideInRight 0.3s ease-out;
    }
    .toast.removing {
        animation: slideOutRight 0.3s ease-in;
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>
@endpush

@push('scripts')
<script>
// Configuration CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// Variables globales
let currentResidenceSlug = null;
let searchTimeout = null;
let pendingImages = [];
let residenceImages = [];

// Fonctions utilitaires
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.className = `toast ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 max-w-sm`;
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button onclick="removeToast(this)" class="ml-auto text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto-remove après 5 secondes
    setTimeout(() => removeToast(toast.querySelector('button')), 5000);
}

function removeToast(button) {
    const toast = button.closest('.toast');
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
}

function openModal(title = 'Ajouter une résidence', residenceSlug = null) {
    console.log('Opening modal with title:', title, 'and slug:', residenceSlug);
    document.getElementById('modal-title').textContent = title;
    const modal = document.getElementById('modal-residence');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    currentResidenceSlug = residenceSlug;
    document.getElementById('residence-slug').value = residenceSlug || '';
    document.getElementById('form-method').value = residenceSlug ? 'PUT' : 'POST';
    document.getElementById('submit-text').textContent = residenceSlug ? 'Mettre à jour' : 'Ajouter';
    
    // Reset form
    document.getElementById('residence-form').reset();
    clearErrors();
    document.getElementById('current-image').classList.add('hidden');
    
    // Load amenities
    console.log('Loading amenities...');
    loadAmenities();
    
    if (residenceSlug) {
        loadResidenceData(residenceSlug);
    }
}

function closeModal() {
    const modal = document.getElementById('modal-residence');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    currentResidenceSlug = null;
    
    // Nettoyer les images en attente
    pendingImages = [];
    residenceImages = [];
    document.getElementById('new-images-preview').innerHTML = '';
    document.getElementById('new-images-preview').style.display = 'none';
    document.getElementById('images-gallery').innerHTML = '';
    document.getElementById('images-gallery').style.display = 'none';
    document.getElementById('current-image').classList.add('hidden');
}

function clearErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(error => {
        error.classList.add('hidden');
        error.textContent = '';
    });
    document.querySelectorAll('.border-red-500').forEach(input => {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    });
}

function showErrors(errors) {
    clearErrors();
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(field + '-error');
        const inputElement = document.getElementById(field);
        
        if (errorElement && inputElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    });
}

async function loadResidenceData(residenceSlug) {
    try {
        const response = await fetch(`/admin/residences/${residenceSlug}/edit`);
        const data = await response.json();
        
        if (data.residence) {
            const residence = data.residence;
            
            // Remplir le formulaire
            document.getElementById('name').value = residence.name || '';
            document.getElementById('location').value = residence.location || '';
            document.getElementById('price_per_night').value = residence.price_per_night || '';
            document.getElementById('size').value = residence.size || '';
            document.getElementById('max_guests').value = residence.max_guests || '';
            document.getElementById('short_description').value = residence.short_description || '';
            document.getElementById('description').value = residence.description || '';
            document.getElementById('is_active').checked = residence.is_active || false;
            document.getElementById('is_featured').checked = residence.is_featured || false;
            
            // Afficher l'image actuelle si elle existe
            if (residence.image) {
                const currentImageDiv = document.getElementById('current-image');
                const currentImagePreview = document.getElementById('current-image-preview');
                // Utiliser l'image telle qu'elle est stockée (avec /storage/ déjà inclus)
                currentImagePreview.src = residence.image;
                currentImagePreview.onerror = function() {
                    this.style.display = 'none';
                    currentImageDiv.innerHTML = '<p class="text-sm text-gray-500">Image non disponible</p>';
                };
                currentImageDiv.classList.remove('hidden');
            }
            
            // Charger les images secondaires
            await loadResidenceImages(residenceSlug);
            
            // Charger les équipements de la résidence
            if (residence.amenities) {
                const selectedAmenities = residence.amenities.map(amenity => amenity.id);
                updateAmenitiesSelection(selectedAmenities);
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement des données:', error);
        showToast('Erreur lors du chargement des données', 'error');
    }
}

async function submitForm(event) {
    console.log('Submit form called');
    event.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const submitText = document.getElementById('submit-text');
    
    // Afficher le loading
    submitBtn.disabled = true;
    loadingSpinner.classList.remove('hidden');
    submitText.textContent = currentResidenceSlug ? 'Mise à jour...' : 'Ajout...';
    
    try {
        const formData = new FormData(document.getElementById('residence-form'));
        const method = currentResidenceSlug ? 'PUT' : 'POST';
        const url = currentResidenceSlug ? `/admin/residences/${currentResidenceSlug}` : '/admin/residences';
        
        // Pour PUT, ajouter _method
        if (currentResidenceSlug) {
            formData.append('_method', 'PUT');
        }
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Si c'est une création ou modification réussie, uploader les images en attente
            if (pendingImages.length > 0) {
                // Pour une nouvelle résidence, récupérer le slug depuis la réponse
                const slugToUse = data.residence?.slug || currentResidenceSlug;
                if (slugToUse) {
                    currentResidenceSlug = slugToUse; // Mettre à jour pour les nouvelles résidences
                    await uploadPendingImages();
                }
            }
            
            showToast(data.message, 'success');
            closeModal();
            loadResidences(); // Recharger la liste
        } else {
            if (data.errors) {
                showErrors(data.errors);
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la sauvegarde', 'error');
    } finally {
        // Masquer le loading
        submitBtn.disabled = false;
        loadingSpinner.classList.add('hidden');
        submitText.textContent = currentResidenceSlug ? 'Mettre à jour' : 'Ajouter';
    }
}

async function deleteResidence(residenceSlug) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette résidence ? Cette action est irréversible.')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/residences/${residenceSlug}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            document.querySelector(`[data-residence-slug="${residenceSlug}"]`).remove();
            
            // Recharger si plus de résidences dans le tableau
            const remainingRows = document.querySelectorAll('.residence-row').length;
            if (remainingRows === 0) {
                loadResidences();
            }
        } else {
            showToast(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression', 'error');
    }
}

async function toggleStatus(residenceSlug) {
    try {
        const response = await fetch(`/admin/residences/${residenceSlug}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            
            // Mettre à jour le statut dans le tableau
            const statusBtn = document.querySelector(`[data-slug="${residenceSlug}"].toggle-status-btn`);
            const statusSpan = statusBtn.querySelector('span');
            
            if (data.status) {
                statusSpan.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                statusSpan.textContent = 'Active';
            } else {
                statusSpan.className = 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                statusSpan.textContent = 'Inactive';
            }
            
            statusBtn.setAttribute('data-status', data.status);
        } else {
            showToast(data.message || 'Erreur lors du changement de statut', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors du changement de statut', 'error');
    }
}

async function loadResidences() {
    const searchValue = document.getElementById('search-input').value;
    const statusValue = document.getElementById('status-filter').value;
    
    const params = new URLSearchParams();
    if (searchValue) params.append('search', searchValue);
    if (statusValue) params.append('status', statusValue);
    params.append('ajax', '1');
    
    try {
        const response = await fetch(`/admin/residences?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.html) {
            document.querySelector('tbody').innerHTML = data.html;
            
            // Réattacher les event listeners
            attachEventListeners();
        }
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        showToast('Erreur lors du chargement des données', 'error');
    }
}

function attachEventListeners() {
    // Boutons d'édition
    document.querySelectorAll('.btn-edit-residence').forEach(btn => {
        btn.onclick = function() {
            const residenceSlug = this.getAttribute('data-slug');
            openModal('Modifier la résidence', residenceSlug);
        };
    });
    
    // Boutons de suppression
    document.querySelectorAll('.btn-delete-residence').forEach(btn => {
        btn.onclick = function() {
            const residenceSlug = this.getAttribute('data-slug');
            deleteResidence(residenceSlug);
        };
    });
    
    // Boutons de toggle status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.onclick = function() {
            const residenceSlug = this.getAttribute('data-slug');
            toggleStatus(residenceSlug);
        };
    });
}

// Créer un élément d'aperçu d'image
function createImagePreviewElement(image) {
    const div = document.createElement('div');
    div.className = 'relative group border rounded-lg overflow-hidden';
    div.setAttribute('data-image-id', image.id);
    
    div.innerHTML = `
        <img src="${image.url}" alt="Image" class="w-full h-24 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <div class="flex gap-2">
                ${!image.isPending && !image.is_primary ? `
                    <button onclick="setPrimaryImage('${image.id}')" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded text-xs" title="Définir comme principale">
                        <i class="fas fa-star"></i>
                    </button>
                ` : ''}
                <button onclick="deleteImage('${image.id}', ${image.isPending})" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded text-xs" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        ${image.is_primary ? '<div class="absolute top-1 left-1 bg-yellow-500 text-white px-2 py-1 text-xs rounded">Principale</div>' : ''}
        ${image.isPending ? '<div class="absolute top-1 right-1 bg-orange-500 text-white px-2 py-1 text-xs rounded">En attente</div>' : ''}
    `;
    
    return div;
}

// Supprimer une image
async function deleteImage(imageId, isPending = false) {
    if (isPending) {
        // Supprimer des images en attente
        pendingImages = pendingImages.filter(img => img.id !== imageId);
        document.querySelector(`[data-image-id="${imageId}"]`).remove();
        
        if (pendingImages.length === 0) {
            document.getElementById('new-images-preview').style.display = 'none';
        }
    } else {
        // Supprimer une image existante
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) return;
        
        try {
            const response = await fetch(`/admin/residences/${currentResidenceSlug}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            if (data.success) {
                document.querySelector(`[data-image-id="${imageId}"]`).remove();
                residenceImages = residenceImages.filter(img => img.id !== imageId);
                showToast('Image supprimée avec succès', 'success');
                
                if (residenceImages.length === 0) {
                    document.getElementById('images-gallery').style.display = 'none';
                }
            } else {
                showToast('Erreur lors de la suppression', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Erreur lors de la suppression', 'error');
        }
    }
}

// Définir une image comme principale
async function setPrimaryImage(imageId) {
    try {
        const response = await fetch(`/admin/residences/${currentResidenceSlug}/images/${imageId}/primary`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Recharger les images pour refléter les changements
            await loadResidenceImages(currentResidenceSlug);
            showToast('Image principale mise à jour', 'success');
        } else {
            showToast('Erreur lors de la mise à jour', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la mise à jour', 'error');
    }
}

// Uploader les images en attente
async function uploadPendingImages() {
    if (pendingImages.length === 0) return true;
    
    const formData = new FormData();
    pendingImages.forEach((imageData, index) => {
        formData.append(`images[${index}]`, imageData.file);
    });
    
    try {
        const response = await fetch(`/admin/residences/${currentResidenceSlug}/images`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Vider les images en attente
            pendingImages = [];
            document.getElementById('new-images-preview').innerHTML = '';
            document.getElementById('new-images-preview').style.display = 'none';
            
            // Recharger les images de la résidence
            await loadResidenceImages(currentResidenceSlug);
            
            showToast(data.message, 'success');
            return true;
        } else {
            showToast('Erreur lors de l\'upload des images', 'error');
            return false;
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de l\'upload des images', 'error');
        return false;
    }
}

// Charger les images d'une résidence
async function loadResidenceImages(residenceSlug) {
    if (!residenceSlug) return;
    
    try {
        const response = await fetch(`/admin/residences/${residenceSlug}/edit`);
        const data = await response.json();
        
        if (data.residence && data.residence.images) {
            residenceImages = data.residence.images;
            const gallery = document.getElementById('images-gallery');
            gallery.innerHTML = '';
            
            if (residenceImages.length > 0) {
                gallery.style.display = 'grid';
                
                residenceImages.forEach(image => {
                    const imageElement = createImagePreviewElement({
                        id: image.id,
                        url: `/storage/${image.image_path}`,
                        is_primary: image.is_primary,
                        isPending: false
                    });
                    gallery.appendChild(imageElement);
                });
            } else {
                gallery.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement des images:', error);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Bouton ajouter
    document.getElementById('btn-add-residence').onclick = function() {
        openModal();
    };
    
    // Formulaire
    document.getElementById('residence-form').onsubmit = submitForm;
    
    // Recherche
    document.getElementById('search-input').oninput = function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadResidences, 500);
    };
    
    // Filtre statut
    document.getElementById('status-filter').onchange = loadResidences;
    
    // Prévisualisation image principale
    document.getElementById('image').onchange = function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('image-placeholder');
                placeholder.innerHTML = `
                    <img src="${e.target.result}" alt="Aperçu" class="w-full h-24 object-cover rounded">
                    <p class="text-sm text-gray-500 mt-2">Nouvelle image sélectionnée</p>
                `;
            };
            reader.readAsDataURL(file);
        }
    };

    // Upload d'images multiples
    document.getElementById('additional-images').onchange = function(event) {
        const files = event.target.files;
        const preview = document.getElementById('new-images-preview');
        
        if (files.length > 0) {
            preview.style.display = 'grid';
        }
        
        // Limiter à 10 images au total
        const maxImages = 10;
        const currentCount = residenceImages.length + pendingImages.length;
        const remainingSlots = maxImages - currentCount;
        
        if (files.length > remainingSlots) {
            showToast(`Vous ne pouvez ajouter que ${remainingSlots} image(s) supplémentaire(s)`, 'warning');
            return;
        }
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageId = 'pending-' + Date.now() + '-' + index;
                    pendingImages.push({ id: imageId, file: file, url: e.target.result });
                    
                    const imageElement = createImagePreviewElement({
                        id: imageId,
                        url: e.target.result,
                        is_primary: false,
                        isPending: true
                    });
                    
                    preview.appendChild(imageElement);
                };
                reader.readAsDataURL(file);
            }
        });
    };
    
    // Fermer modal avec Escape
    document.onkeydown = function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    };
    
    // Attacher les event listeners initiaux
    attachEventListeners();
    
    // Ajouter les styles CSS pour la galerie d'images
    const imageGalleryStyles = document.createElement('style');
    imageGalleryStyles.textContent = `
        .image-gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }
        
        .image-gallery-item:hover {
            transform: scale(1.02);
        }
        
        .image-gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .image-gallery-item:hover .image-gallery-overlay {
            opacity: 1;
        }
        
        .image-gallery-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 6px;
            padding: 8px;
            margin: 0 4px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .image-gallery-btn:hover {
            background: white;
        }
        
        .primary-badge {
            position: absolute;
            top: 4px;
            left: 4px;
            background: #f59e0b;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .pending-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #f97316;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
    `;
    document.head.appendChild(imageGalleryStyles);
    
    // Charger les résidences au chargement de la page
    console.log('DOM Content Loaded - Starting initialization');
    loadResidences();
    
    // Attacher les event listeners pour les éléments existants
    attachEventListeners();
    console.log('Initialization complete');
});

// Fonctions pour la gestion des équipements
async function loadAmenities() {
    try {
        // Try the AJAX endpoint first
        let response = await fetch('/admin/amenities/ajax', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        // If AJAX endpoint fails, try the regular endpoint
        if (!response.ok || !response.headers.get('content-type')?.includes('application/json')) {
            console.log('AJAX endpoint failed, trying regular endpoint...');
            response = await fetch('/admin/amenities', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON - you may need to log in as administrator');
        }
        
        const data = await response.json();
        
        if (data.success) {
            const container = document.getElementById('amenities-container');
            container.innerHTML = '';
            
            data.amenities.forEach(amenity => {
                const amenityElement = document.createElement('div');
                amenityElement.className = 'flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50';
                amenityElement.innerHTML = `
                    <input type="checkbox" 
                           id="amenity-${amenity.id}" 
                           name="amenities[]" 
                           value="${amenity.id}" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="amenity-${amenity.id}" class="flex items-center space-x-2 cursor-pointer">
                        <i class="${amenity.icon || 'fas fa-check'} text-blue-600"></i>
                        <span class="text-sm text-gray-700">${amenity.name}</span>
                    </label>
                `;
                container.appendChild(amenityElement);
            });
        } else {
            throw new Error(data.message || 'Erreur lors du chargement des équipements');
        }
    } catch (error) {
        console.error('Erreur lors du chargement des équipements:', error);
        showToast('Erreur lors du chargement des équipements: ' + error.message, 'error');
        
        // Afficher un message d'erreur dans le conteneur
        const container = document.getElementById('amenities-container');
        container.innerHTML = `
            <div class="col-span-full text-center py-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl mb-2"></i>
                <p class="text-red-600 text-sm">Impossible de charger les équipements</p>
                <p class="text-gray-500 text-xs mt-1">Vérifiez que vous êtes connecté en tant qu'administrateur</p>
                <button onclick="loadAmenities()" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                    Réessayer
                </button>
            </div>
        `;
    }
}

function updateAmenitiesSelection(selectedAmenityIds) {
    // Décocher toutes les cases
    document.querySelectorAll('input[name="amenities[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Cocher les équipements sélectionnés
    selectedAmenityIds.forEach(amenityId => {
        const checkbox = document.getElementById(`amenity-${amenityId}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
}
</script>
@endpush
