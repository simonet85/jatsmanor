@extends('layouts.app')

@section('title', 'Réservation ' . $booking->booking_reference . ' - Jatsmanor')

@section('styles')
<style>
    /* Ensure booking page content doesn't interfere with navigation */
    .booking-content {
        position: relative;
        z-index: 1;
        margin-top: 0;
        padding-top: 0;
    }
    
    /* Ensure navigation stays at top */
    nav {
        position: sticky !important;
        top: 0 !important;
        z-index: 9999 !important;
        width: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="booking-content max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">Détails de la réservation</h1>
            <p class="text-gray-600">Référence: <span class="font-mono">{{ $booking->booking_reference }}</span></p>
        </div>
        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
            @if($booking->status === 'confirmed') bg-green-100 text-green-800
            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif">
            {{ translate_status($booking->status) }}
        </span>
    </div>

    <!-- Informations de la résidence -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Résidence</h2>
        <div class="flex gap-4">
            @if($booking->residence->images->first())
                <img src="{{ asset('storage/' . $booking->residence->images->first()->image_path) }}" 
                     alt="{{ $booking->residence->name }}" 
                     class="w-32 h-24 object-cover rounded-lg" />
            @else
                <div class="w-32 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-gray-400"></i>
                </div>
            @endif
            <div>
                <h3 class="font-bold text-lg">{{ $booking->residence->name }}</h3>
                <p class="text-gray-600">{{ $booking->residence->location }}</p>
                <p class="text-gray-600">{{ $booking->residence->surface }}m² • {{ $booking->residence->max_guests }} {{ translate_term('persons') }} max</p>
            </div>
        </div>
    </div>

    <!-- Détails du séjour -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Détails du séjour</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-3">Dates</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ translate_term('check_in') }}:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ translate_term('check_out') }}:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ translate_term('duration') }}:</span>
                        <span class="font-medium">
                            {{ $booking->total_nights }} {{ $booking->total_nights > 1 ? translate_term('nights') : translate_term('night') }}
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-3">Invités</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nombre d'{{ translate_term('guests') }}:</span>
                        <span class="font-medium">{{ $booking->guests }} {{ $booking->guests > 1 ? translate_term('persons') : translate_term('person') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Informations personnelles</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nom:</span>
                    <span class="font-medium">{{ $booking->first_name }} {{ $booking->last_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $booking->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Téléphone:</span>
                    <span class="font-medium">{{ $booking->phone }}</span>
                </div>
                @if($booking->company)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Entreprise:</span>
                        <span class="font-medium">{{ $booking->company }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        @if($booking->special_requests)
            <div class="mt-4">
                <h3 class="font-semibold mb-2">Demandes spéciales</h3>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->special_requests }}</p>
            </div>
        @endif
    </div>

    <!-- Récapitulatif financier -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Récapitulatif financier</h2>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span>{{ number_format($booking->price_per_night, 0, ',', '.') }} FCFA × {{ $booking->total_nights }} {{ $booking->total_nights > 1 ? 'nuits' : 'nuit' }}</span>
                <span>{{ number_format($booking->price_per_night * $booking->total_nights, 0, ',', '.') }} FCFA</span>
            </div>
            <div class="border-t pt-3 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>{{ number_format($booking->total_amount, 0, ',', '.') }} FCFA</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('booking.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux réservations
        </a>

        <!-- Actions rapides -->
        @auth
            <button type="button" onclick="sendConfirmation()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-envelope mr-2"></i>
                Envoyer confirmation
            </button>

            <button type="button" onclick="generateInvoice()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-file-invoice mr-2"></i>
                Générer facture
            </button>
        @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i>
                Connectez-vous pour envoyer des confirmations et générer des factures
            </div>
        @endauth

        @if($booking->status === 'pending' || $booking->status === 'confirmed')
            @php
                $checkinDate = \Carbon\Carbon::parse($booking->check_in);
                $canCancel = $checkinDate->diffInHours(now()) >= 48;
            @endphp
            
            @if($canCancel)
                <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold"
                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                        <i class="fas fa-times mr-2"></i>
                        Annuler la réservation
                    </button>
                </form>
            @else
                <div class="text-gray-500">
                    <i class="fas fa-info-circle mr-2"></i>
                    L'annulation n'est plus possible (moins de 48h avant l'arrivée)
                </div>
            @endif
        @endif
    </div>

    <!-- Informations pratiques -->
    <div class="bg-blue-50 rounded-lg p-6 mt-8">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-blue-600"></i>
            Informations pratiques
        </h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="font-medium">Check-in</p>
                <p class="text-gray-600">À partir de 14h00</p>
            </div>
            <div>
                <p class="font-medium">Check-out</p>
                <p class="text-gray-600">Avant 11h00</p>
            </div>
            <div>
                <p class="font-medium">Contact</p>
                <p class="text-gray-600">+225 07 07 07 07</p>
            </div>
            <div>
                <p class="font-medium">Email</p>
                <p class="text-gray-600">contact@jatsmanor.ci</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour confirmation d'envoi d'email -->
<div id="email-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Envoyer confirmation</h3>
            <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="email-form">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email du client</label>
                <input type="email" name="email" value="{{ $booking->email }}" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Message personnalisé (optionnel)</label>
                <textarea name="message" rows="3" 
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Ajouter un message personnalisé..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEmailModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <span class="email-btn-text">Envoyer</span>
                    <span class="email-btn-loading hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Envoi...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Container pour les notifications toast -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@push('scripts')
<script>
// Fonction pour afficher les toasts
function showToast(message, type = 'info', duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    
    const config = {
        success: { 
            bg: 'bg-green-500 border-l-4 border-green-400', 
            icon: 'fas fa-check-circle',
            title: 'Succès'
        },
        error: { 
            bg: 'bg-red-500 border-l-4 border-red-400', 
            icon: 'fas fa-exclamation-circle',
            title: 'Erreur'
        },
        warning: { 
            bg: 'bg-yellow-500 border-l-4 border-yellow-400', 
            icon: 'fas fa-exclamation-triangle',
            title: 'Attention'
        },
        info: { 
            bg: 'bg-blue-500 border-l-4 border-blue-400', 
            icon: 'fas fa-info-circle',
            title: 'Information'
        }
    };
    
    const current = config[type] || config.info;
    
    toast.id = toastId;
    toast.className = `${current.bg} text-white rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0 mb-3 min-w-80 max-w-md`;
    toast.innerHTML = `
        <div class="flex items-start p-4">
            <div class="flex-shrink-0">
                <i class="${current.icon} text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">${current.title}</p>
                <p class="text-sm opacity-90 mt-1">${message}</p>
            </div>
            <button onclick="removeToast('${toastId}')" class="ml-3 text-white hover:text-gray-200 transition-colors flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    }
}

function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }
}

// Envoyer confirmation par email
function sendConfirmation() {
    // Vérifier d'abord si nous sommes connectés
    @guest
        showToast('Vous devez être connecté pour envoyer une confirmation', 'error');
        return;
    @endguest
    
    document.getElementById('email-modal').classList.remove('hidden');
    document.getElementById('email-modal').classList.add('flex');
}

function closeEmailModal() {
    document.getElementById('email-modal').classList.add('hidden');
    document.getElementById('email-modal').classList.remove('flex');
}

// Gérer l'envoi du formulaire email
document.getElementById('email-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const btnText = document.querySelector('.email-btn-text');
    const btnLoading = document.querySelector('.email-btn-loading');
    
    // Afficher le loading
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    
    fetch('{{ route("booking.send-confirmation", $booking) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast('Email de confirmation envoyé avec succès', 'success');
            closeEmailModal();
        } else {
            showToast(data.message || 'Erreur lors de l\'envoi', 'error');
        }
    })
    .catch(error => {
        console.error('Detailed error:', error);
        showToast('Erreur lors de l\'envoi de l\'email: ' + error.message, 'error');
    })
    .finally(() => {
        // Masquer le loading
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    });
});

// Générer facture
function generateInvoice() {
    // Vérifier d'abord si nous sommes connectés
    @guest
        showToast('Vous devez être connecté pour générer une facture', 'error');
        return;
    @endguest
    
    showToast('Génération de la facture en cours...', 'info', 0);
    
    fetch('{{ route("booking.generate-invoice", $booking) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('PDF Response status:', response.status);
        console.log('PDF Response headers:', response.headers);
        
        if (response.ok) {
            return response.blob();
        }
        
        // Essayer de lire le message d'erreur JSON si possible
        return response.text().then(text => {
            try {
                const errorData = JSON.parse(text);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            } catch (parseError) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        });
    })
    .then(blob => {
        // Créer un lien de téléchargement
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `facture-{{ $booking->booking_reference }}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Supprimer le toast de loading et afficher succès
        const loadingToasts = document.querySelectorAll('.toast-notification');
        loadingToasts.forEach(toast => {
            if (toast.textContent.includes('Génération')) {
                removeToast(toast.id);
            }
        });
        
        showToast('Facture générée et téléchargée avec succès', 'success');
    })
    .catch(error => {
        console.error('PDF generation error:', error);
        
        // Supprimer le toast de loading
        const loadingToasts = document.querySelectorAll('[id^="toast-"]');
        loadingToasts.forEach(toast => {
            if (toast.textContent.includes('Génération')) {
                removeToast(toast.id);
            }
        });
        
        showToast('Erreur lors de la génération de la facture: ' + error.message, 'error');
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('email-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmailModal();
    }
});
</script>
@endpush
