@extends('dashboard.layout')

@section('title', 'Paramètres du Site')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Paramètres du Site</h1>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informations générales
            </h2>
            <form id="general-form" class="space-y-4" method="POST">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du site</label>
                    <input type="text" name="site_name" value="{{ setting('site_name', 'Jatsmanor') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="site_description" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ setting('site_description', 'Résidences meublées premium à Abidjan') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                        <span class="btn-text">Sauvegarder</span>
                        <span class="btn-loading hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de contact -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-address-book mr-2 text-green-600"></i>Informations de contact
            </h2>
            <form id="contact-form" class="space-y-4" method="POST">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                    <input type="email" name="contact_email" value="{{ setting('contact_email', 'contact@jatsmanor.ci') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" name="contact_phone" value="{{ setting('contact_phone', '+225 07 07 07 07') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <input type="text" name="contact_address" value="{{ setting('contact_address', '123 Cocody Résidentiel, Abidjan') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                        <span class="btn-text">Sauvegarder</span>
                        <span class="btn-loading hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Paramètres de réservation -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>Paramètres de réservation
            </h2>
            <form id="booking-form" class="space-y-4" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durée min. (nuits)</label>
                        <input type="number" name="min_stay_duration" min="1" value="{{ setting('min_stay_duration', 1) }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durée max. (nuits)</label>
                        <input type="number" name="max_stay_duration" min="1" value="{{ setting('max_stay_duration', 30) }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Délai d'annulation (heures)</label>
                    <input type="number" name="cancellation_hours" min="0" value="{{ setting('cancellation_hours', 24) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frais de nettoyage (FCFA)</label>
                        <input type="number" name="cleaning_fee" min="0" value="{{ setting('cleaning_fee', 15000) }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Caution (FCFA)</label>
                        <input type="number" name="security_deposit" min="0" value="{{ setting('security_deposit', 50000) }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="auto_confirm_bookings" id="auto-confirm" 
                           {{ setting('auto_confirm_bookings', 0) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="auto-confirm" class="ml-2 block text-sm text-gray-900">
                        Confirmation automatique des réservations
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md font-medium">
                        <span class="btn-text">Sauvegarder</span>
                        <span class="btn-loading hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Paramètres de langue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-globe mr-2 text-indigo-600"></i>Paramètres de langue
            </h2>
            <form id="language-form" class="space-y-4" method="POST">
                @csrf
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Sélecteur de langue</h3>
                        <p class="text-sm text-gray-500">Afficher le sélecteur de langue dans la navigation</p>
                    </div>
                    <div class="relative">
                        <input type="checkbox" name="show_language_selector" id="show-language-selector" 
                               {{ setting('show_language_selector', 1) ? 'checked' : '' }}
                               class="sr-only">
                        <label for="show-language-selector" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <div class="toggle-bg block bg-gray-200 w-14 h-8 rounded-full"></div>
                                <div class="toggle-dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"></div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Langue par défaut</label>
                    <select name="default_language" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="fr" {{ setting('default_language', 'fr') === 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ setting('default_language', 'fr') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                        <span class="btn-text">Sauvegarder</span>
                        <span class="btn-loading hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Paramètres frontend -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-palette mr-2 text-orange-600"></i>Paramètres frontend
            </h2>
            <form id="frontend-form" class="space-y-4" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre principal</label>
                    <input type="text" name="hero_title" value="{{ setting('hero_title', 'Séjournez dans nos résidences meublées de luxe') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titre</label>
                    <input type="text" name="hero_subtitle" value="{{ setting('hero_subtitle', 'Confort, sécurité et flexibilité à Abidjan') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image hero</label>
                    @if(setting('hero_image'))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . setting('hero_image')) }}" alt="Hero image" class="w-32 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="hero_image" accept="image/*" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF. Max 2MB.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description footer</label>
                    <textarea name="footer_description" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ setting('footer_description', 'Résidences meublées à Abidjan - confort, sécurité et flexibilité à portée de clic.') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-md font-medium">
                        <span class="btn-text">Sauvegarder</span>
                        <span class="btn-loading hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonction pour créer un modal d'alerte
function showAlert(message, type = 'info') {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    
    const iconClass = type === 'success' ? 'fas fa-check-circle text-green-500' : 
                     type === 'error' ? 'fas fa-exclamation-circle text-red-500' : 
                     'fas fa-info-circle text-blue-500';
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <i class="${iconClass} text-2xl mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">
                    ${type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : 'Information'}
                </h3>
            </div>
            <p class="text-gray-700 mb-6">${message}</p>
            <div class="text-right">
                <button onclick="this.closest('.fixed').remove()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    OK
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Fonction pour gérer les formulaires
function handleFormSubmission(formId, url, successMessage) {
    document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const btnText = form.querySelector('.btn-text');
        const btnLoading = form.querySelector('.btn-loading');
        
        // Special handling for language form checkbox
        if (formId === 'language-form') {
            const checkbox = form.querySelector('#show-language-selector');
            if (checkbox) {
                // Remove the checkbox from FormData and add it with proper value
                formData.delete('show_language_selector');
                if (checkbox.checked) {
                    formData.append('show_language_selector', '1');
                }
            }
        }
        
        // Afficher le loading
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(successMessage, 'success');
                
                // Special handling for language form - refresh page to update language selector visibility
                if (formId === 'language-form') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                showAlert(data.message || 'Erreur lors de la sauvegarde', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erreur lors de la sauvegarde', 'error');
        })
        .finally(() => {
            // Masquer le loading
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Configurer les gestionnaires de formulaires
    handleFormSubmission('general-form', '{{ route("admin.settings.update-general") }}', 'Paramètres généraux mis à jour avec succès');
    handleFormSubmission('contact-form', '{{ route("admin.settings.update-contact") }}', 'Paramètres de contact mis à jour avec succès');
    handleFormSubmission('booking-form', '{{ route("admin.settings.update-booking") }}', 'Paramètres de réservation mis à jour avec succès');
    handleFormSubmission('language-form', '{{ route("admin.settings.update-language") }}', 'Paramètres de langue mis à jour avec succès');
    handleFormSubmission('frontend-form', '{{ route("admin.settings.update-frontend") }}', 'Paramètres frontend mis à jour avec succès');
    
    // Gestionnaire pour le toggle switch
    const toggleCheckbox = document.getElementById('show-language-selector');
    const toggleBg = document.querySelector('.toggle-bg');
    const toggleDot = document.querySelector('.toggle-dot');
    
    function updateToggle() {
        if (toggleCheckbox.checked) {
            toggleBg.classList.remove('bg-gray-200');
            toggleBg.classList.add('bg-indigo-600');
            toggleDot.classList.add('transform', 'translate-x-6');
        } else {
            toggleBg.classList.remove('bg-indigo-600');
            toggleBg.classList.add('bg-gray-200');
            toggleDot.classList.remove('transform', 'translate-x-6');
        }
    }
    
    // Initialiser l'état du toggle
    updateToggle();
    
    // Écouter les changements
    toggleCheckbox.addEventListener('change', updateToggle);
});
</script>
@endpush
@endsection
