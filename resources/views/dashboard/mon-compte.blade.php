@extends('dashboard.layout')

@section('title', 'Mon compte')

@section('content')
<!-- Header avec breadcrumb -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Mon compte</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Mon compte</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Actions rapides -->
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <button onclick="toggleTheme()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-moon mr-2"></i>
            <span id="theme-text">Mode sombre</span>
        </button>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Stats rapides utilisateur -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-blue-100 truncate">Réservations totales</dt>
                    <dd class="text-2xl font-bold">{{ Auth::user()->bookings()->count() ?? 0 }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-emerald-100 truncate">Réservations actives</dt>
                    <dd class="text-2xl font-bold">{{ Auth::user()->bookings()->where('status', 'confirmed')->count() ?? 0 }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-star text-2xl"></i>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-purple-100 truncate">Membre depuis</dt>
                    <dd class="text-lg font-bold">{{ Auth::user()->created_at->format('M Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Container principal avec onglets -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Navigation par onglets -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button onclick="switchTab('profile')" id="tab-profile" class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-user mr-2"></i>
                Informations personnelles
            </button>
            <button onclick="switchTab('security')" id="tab-security" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                Sécurité
            </button>
            <button onclick="switchTab('preferences')" id="tab-preferences" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-cog mr-2"></i>
                Préférences
            </button>
        </nav>
    </div>

    <!-- Contenu des onglets -->
    <div class="p-6">
        <!-- Onglet Profil -->
        <div id="content-profile" class="tab-content">
            <!-- Section Avatar et info de base -->
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    @if(Auth::user()->avatar)
                        <img id="avatar-preview" src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                             alt="Photo de profil" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-blue-200 shadow-lg transition-all duration-300 hover:shadow-xl">
                    @else
                        <div id="avatar-preview" class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-blue-200 shadow-lg transition-all duration-300 hover:shadow-xl">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" onchange="previewAndSubmitAvatar(this)">
                    </form>
                    <button onclick="document.getElementById('avatar-input').click()" 
                            class="absolute bottom-2 right-2 bg-blue-600 text-white rounded-full p-3 hover:bg-blue-700 transform hover:scale-110 transition-all duration-200 shadow-lg">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                <div class="mt-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ Auth::user()->email }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                        <i class="fas fa-circle text-green-400 mr-1 text-xs"></i>
                        Compte actif
                    </span>
                </div>
            </div>

            <!-- Formulaire de mise à jour du profil -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>
                    Modifier mes informations
                </h3>
                
                <form action="{{ route('profile.update.dashboard') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1 text-gray-400"></i>
                                Prénom
                            </label>
                            <input type="text" name="first_name"
                                   value="{{ explode(' ', Auth::user()->name)[0] ?? '' }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                   required>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1 text-gray-400"></i>
                                Nom
                            </label>
                            <input type="text" name="last_name"
                                   value="{{ explode(' ', Auth::user()->name, 2)[1] ?? '' }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-1 text-gray-400"></i>
                            Email
                        </label>
                        <input type="email" name="email"
                               value="{{ Auth::user()->email }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               required>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone mr-1 text-gray-400"></i>
                            Téléphone
                        </label>
                        <input type="tel" name="phone"
                               value="{{ Auth::user()->phone ?? '' }}" 
                               placeholder="+225 XX XX XX XX"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                            Adresse
                        </label>
                        <textarea name="address" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                  rows="3" 
                                  placeholder="Votre adresse complète">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Sauvegarder le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Onglet Sécurité -->
        <div id="content-security" class="tab-content hidden">
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-lock mr-2 text-red-600"></i>
                    Changer le mot de passe
                </h3>
                
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-key mr-1 text-gray-400"></i>
                            Mot de passe actuel
                        </label>
                        <input type="password" name="current_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200" 
                               required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-lock mr-1 text-gray-400"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="password" id="new-password"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200" 
                                   required>
                            <div class="mt-2">
                                <div id="password-strength" class="text-xs text-gray-500"></div>
                                <div id="password-progress" class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div id="password-bar" class="bg-red-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-check-circle mr-1 text-gray-400"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" name="password_confirmation" id="password-confirm"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200" 
                                   required>
                            <div id="password-match" class="text-xs mt-1"></div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Conseils de sécurité</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Utilisez au moins 8 caractères</li>
                                        <li>Mélangez majuscules, minuscules, chiffres et symboles</li>
                                        <li>Évitez les mots courants</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Onglet Préférences -->
        <div id="content-preferences" class="tab-content hidden">
            <div class="space-y-6">
                <!-- Notifications -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-bell mr-2 text-green-600"></i>
                        Préférences de notifications
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">Notifications par email</h4>
                                <p class="text-sm text-gray-500">Recevoir des notifications par email pour les réservations</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">Promotions et offres</h4>
                                <p class="text-sm text-gray-500">Recevoir des offres spéciales et promotions</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Langue et région -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-globe mr-2 text-purple-600"></i>
                        Langue et région
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Langue</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                                <option value="fr" selected>Français</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuseau horaire</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                                <option value="Africa/Abidjan" selected>Abidjan (GMT+0)</option>
                                <option value="Europe/Paris">Paris (GMT+1)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-6 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                        Zone dangereuse
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-red-900">Supprimer mon compte</h4>
                            <p class="text-sm text-red-700 mt-1">Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>
                            <button onclick="confirmDeleteAccount()" class="mt-3 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer mon compte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ============ GESTION DES ONGLETS ============
function switchTab(tabName) {
    // Masquer tous les contenus d'onglets
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Désactiver tous les boutons d'onglets
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Afficher le contenu de l'onglet sélectionné avec animation
    const targetContent = document.getElementById(`content-${tabName}`);
    const targetButton = document.getElementById(`tab-${tabName}`);
    
    if (targetContent && targetButton) {
        targetContent.classList.remove('hidden');
        targetContent.style.opacity = '0';
        targetContent.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            targetContent.style.transition = 'all 0.3s ease-out';
            targetContent.style.opacity = '1';
            targetContent.style.transform = 'translateY(0)';
        }, 10);
        
        targetButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        targetButton.classList.remove('border-transparent', 'text-gray-500');
    }
}

// ============ SYSTÈME DE TOAST AMÉLIORÉ ============
function showToast(message, type = 'success', duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const toastId = 'toast-' + Date.now();
    toast.id = toastId;
    
    const config = {
        success: {
            icon: 'fas fa-check-circle',
            bgColor: 'bg-green-500',
            borderColor: 'border-green-400',
            textColor: 'text-white'
        },
        error: {
            icon: 'fas fa-times-circle',
            bgColor: 'bg-red-500',
            borderColor: 'border-red-400',
            textColor: 'text-white'
        },
        info: {
            icon: 'fas fa-info-circle',
            bgColor: 'bg-blue-500',
            borderColor: 'border-blue-400',
            textColor: 'text-white'
        },
        loading: {
            icon: 'fas fa-spinner fa-spin',
            bgColor: 'bg-blue-500',
            borderColor: 'border-blue-400',
            textColor: 'text-white'
        }
    };
    
    const settings = config[type] || config.success;
    
    toast.className = `transform transition-all duration-300 ease-in-out ${settings.bgColor} ${settings.textColor} px-6 py-4 rounded-lg shadow-lg border-l-4 ${settings.borderColor} flex items-center gap-3 min-w-80 translate-x-full opacity-0`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3 flex-1">
            <i class="${settings.icon} text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
        <button onclick="removeToast('${toastId}')" class="text-white hover:text-gray-200 ml-2">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    if (type !== 'loading' && duration > 0) {
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    }
    
    return toastId;
}

function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// ============ GESTION AVATAR AMÉLIORÉE ============
function previewAndSubmitAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validation côté client
        if (!file.type.startsWith('image/')) {
            showToast('Veuillez sélectionner un fichier image valide', 'error');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) { // 2MB
            showToast('L\'image ne doit pas dépasser 2MB', 'error');
            return;
        }
        
        const loadingToastId = showToast('Upload en cours...', 'loading', 0);
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                img.alt = 'Photo de profil';
                img.className = 'w-32 h-32 rounded-full object-cover border-4 border-blue-200 shadow-lg transition-all duration-300 hover:shadow-xl';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(file);
        
        const form = document.getElementById('avatar-form');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            removeToast(loadingToastId);
            
            if (response.ok) {
                showToast('Photo de profil mise à jour avec succès !', 'success');
                setTimeout(() => {
                    updateNavAvatars(e.target.result);
                }, 500);
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Erreur lors de l\'upload');
                });
            }
        })
        .catch(error => {
            removeToast(loadingToastId);
            showToast('Erreur: ' + error.message, 'error');
            console.error('Erreur upload:', error);
        });
    }
}

function updateNavAvatars(imageSrc) {
    const topnavAvatar = document.querySelector('nav img[alt="Profil"]');
    if (topnavAvatar) {
        topnavAvatar.src = imageSrc;
    }
    
    const sidebarAvatar = document.querySelector('aside img[alt="Profil"]');
    if (sidebarAvatar) {
        sidebarAvatar.src = imageSrc;
    }
}

// ============ VALIDATION MOT DE PASSE ============
function checkPasswordStrength(password) {
    let strength = 0;
    let requirements = [];
    
    if (password.length >= 8) {
        strength += 25;
        requirements.push('Longueur suffisante');
    }
    if (/[a-z]/.test(password)) {
        strength += 25;
        requirements.push('Minuscules');
    }
    if (/[A-Z]/.test(password)) {
        strength += 25;
        requirements.push('Majuscules');
    }
    if (/[0-9]/.test(password)) {
        strength += 25;
        requirements.push('Chiffres');
    }
    if (/[^A-Za-z0-9]/.test(password)) {
        strength += 25;
        requirements.push('Caractères spéciaux');
    }
    
    return { strength: Math.min(strength, 100), requirements };
}

function updatePasswordStrength() {
    const passwordInput = document.getElementById('new-password');
    const strengthText = document.getElementById('password-strength');
    const progressBar = document.getElementById('password-bar');
    
    if (!passwordInput || !strengthText || !progressBar) return;
    
    const password = passwordInput.value;
    const { strength, requirements } = checkPasswordStrength(password);
    
    progressBar.style.width = strength + '%';
    
    if (strength < 25) {
        progressBar.className = 'bg-red-500 h-2 rounded-full transition-all duration-300';
        strengthText.textContent = 'Faible';
        strengthText.className = 'text-xs text-red-500';
    } else if (strength < 50) {
        progressBar.className = 'bg-orange-500 h-2 rounded-full transition-all duration-300';
        strengthText.textContent = 'Moyen';
        strengthText.className = 'text-xs text-orange-500';
    } else if (strength < 75) {
        progressBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
        strengthText.textContent = 'Bon';
        strengthText.className = 'text-xs text-yellow-500';
    } else {
        progressBar.className = 'bg-green-500 h-2 rounded-full transition-all duration-300';
        strengthText.textContent = 'Excellent';
        strengthText.className = 'text-xs text-green-500';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('new-password');
    const confirmPassword = document.getElementById('password-confirm');
    const matchText = document.getElementById('password-match');
    
    if (!password || !confirmPassword || !matchText) return;
    
    if (confirmPassword.value === '') {
        matchText.textContent = '';
        return;
    }
    
    if (password.value === confirmPassword.value) {
        matchText.textContent = '✓ Les mots de passe correspondent';
        matchText.className = 'text-xs mt-1 text-green-500';
    } else {
        matchText.textContent = '✗ Les mots de passe ne correspondent pas';
        matchText.className = 'text-xs mt-1 text-red-500';
    }
}

// ============ THÈME SOMBRE/CLAIR ============
function toggleTheme() {
    const body = document.body;
    const themeText = document.getElementById('theme-text');
    
    if (body.classList.contains('dark')) {
        body.classList.remove('dark');
        themeText.innerHTML = '<i class="fas fa-moon mr-2"></i>Mode sombre';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark');
        themeText.innerHTML = '<i class="fas fa-sun mr-2"></i>Mode clair';
        localStorage.setItem('theme', 'dark');
    }
}

// ============ CONFIRMATION SUPPRESSION COMPTE ============
function confirmDeleteAccount() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) {
        if (confirm('Dernière confirmation : toutes vos données seront définitivement perdues.')) {
            showToast('Fonction en cours de développement', 'info');
        }
    }
}

// ============ INITIALISATION ============
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le thème
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark');
        document.getElementById('theme-text').innerHTML = '<i class="fas fa-sun mr-2"></i>Mode clair';
    }
    
    // Event listeners pour validation mot de passe
    const passwordInput = document.getElementById('new-password');
    const confirmInput = document.getElementById('password-confirm');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', updatePasswordStrength);
        passwordInput.addEventListener('input', checkPasswordMatch);
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
    
    // Afficher les messages de session
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast('{{ $error }}', 'error');
        @endforeach
    @endif
    
    // Animation d'entrée pour les cards
    const cards = document.querySelectorAll('.bg-white, .bg-gray-50');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<style>
.tab-button.active {
    border-color: #3b82f6 !important;
    color: #3b82f6 !important;
}

/* Animations personnalisées */
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

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Mode sombre */
body.dark {
    background-color: #1f2937;
    color: #f9fafb;
}

body.dark .bg-white {
    background-color: #374151 !important;
    color: #f9fafb;
}

body.dark .bg-gray-50 {
    background-color: #4b5563 !important;
    color: #f9fafb;
}

body.dark .text-gray-900 {
    color: #f9fafb !important;
}

body.dark .text-gray-700 {
    color: #d1d5db !important;
}

body.dark .border-gray-300 {
    border-color: #6b7280 !important;
}

/* Transitions fluides */
* {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}
</style>
@endsection
