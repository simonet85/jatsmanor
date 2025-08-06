@extends('dashboard.layout')

@section('title', 'Détails de la Réservation #' . ($booking->booking_reference ?? $booking->id))

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Réservation #{{ $booking->booking_reference ?? $booking->id }}
                </h1>
                <p class="text-gray-600 mt-2">
                    Créée le {{ $booking->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
        
        <!-- Status badges -->
        <div class="flex gap-2">
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                @else bg-red-100 text-red-800
                @endif">
                Statut: {{ ucfirst($booking->status) }}
            </span>
            
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($booking->payment_status == 'pending') bg-orange-100 text-orange-800
                @elseif($booking->payment_status == 'paid') bg-green-100 text-green-800
                @elseif($booking->payment_status == 'failed') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                Paiement: {{ ucfirst($booking->payment_status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user mr-2"></i>Informations Client
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <p class="text-gray-900">{{ $booking->first_name }} {{ $booking->last_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $booking->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <p class="text-gray-900">{{ $booking->phone ?? 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entreprise</label>
                        <p class="text-gray-900">{{ $booking->company ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                
                @if($booking->user)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Client enregistré: <span class="font-medium">{{ $booking->user->name }}</span>
                    </p>
                </div>
                @endif
            </div>

            <!-- Booking Details -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-calendar mr-2"></i>Détails de la Réservation
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de nuits</label>
                        <p class="text-gray-900">{{ $booking->total_nights }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'invités</label>
                        <p class="text-gray-900">{{ $booking->guests }}</p>
                    </div>
                </div>
                
                @if($booking->special_requests)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Demandes spéciales</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>

            <!-- Residence Information -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-home mr-2"></i>Résidence
                </h2>
                
                <div class="flex gap-4">
                    @if($booking->residence->images->first())
                    <img src="{{ asset('storage/' . $booking->residence->images->first()->image_path) }}" 
                         alt="{{ $booking->residence->name }}" 
                         class="w-24 h-24 object-cover rounded-lg">
                    @endif
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $booking->residence->name }}</h3>
                        <p class="text-gray-600">{{ $booking->residence->location }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $booking->residence->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Management -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-credit-card mr-2"></i>Gestion du Paiement
                </h3>
                
                <form id="payment-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut du paiement</label>
                        <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="failed" {{ $booking->payment_status == 'failed' ? 'selected' : '' }}>Échoué</option>
                            <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                        Mettre à jour le paiement
                    </button>
                </form>
            </div>

            <!-- Status Management -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-tasks mr-2"></i>Statut de la Réservation
                </h3>
                
                <form id="status-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                        Mettre à jour le statut
                    </button>
                </form>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-calculator mr-2"></i>Résumé Financier
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix par nuit:</span>
                        <span class="font-medium">{{ number_format($booking->price_per_night, 0, ',', ' ') }} FCFA</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ $booking->total_nights }} nuit(s):</span>
                        <span class="font-medium">{{ number_format($booking->price_per_night * $booking->total_nights, 0, ',', ' ') }} FCFA</span>
                    </div>
                    
                    @if($booking->cleaning_fee)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Frais de ménage:</span>
                        <span class="font-medium">{{ number_format($booking->cleaning_fee, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                    
                    @if($booking->security_deposit)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Caution:</span>
                        <span class="font-medium">{{ number_format($booking->security_deposit, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                    
                    @if($booking->tax_amount)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Taxes:</span>
                        <span class="font-medium">{{ number_format($booking->tax_amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                    
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">{{ number_format($booking->total_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
                
                <div class="space-y-2">
                    <button class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 transition-colors" 
                            onclick="sendConfirmationEmail()">
                        <i class="fas fa-envelope mr-2"></i>Envoyer confirmation
                    </button>
                    
                    <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-colors" 
                            onclick="generateInvoice()">
                        <i class="fas fa-file-invoice mr-2"></i>Générer facture
                    </button>
                    
                    <button class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors" 
                            onclick="cancelBooking()">
                        <i class="fas fa-times mr-2"></i>Annuler réservation
                    </button>
                </div>
            </div>
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
    
    // Fermer avec Escape
    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.remove();
        }
    });
    
    // Fermer en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Fonction pour créer un modal de confirmation
function showConfirm(message, onConfirm, onCancel = null) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <i class="fas fa-question-circle text-yellow-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Confirmation</h3>
            </div>
            <p class="text-gray-700 mb-6">${message}</p>
            <div class="flex gap-3">
                <button onclick="cancelConfirm()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Annuler
                </button>
                <button onclick="confirmAction()" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Confirmer
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Fonctions locales pour gérer les actions
    window.confirmAction = function() {
        modal.remove();
        if (onConfirm) onConfirm();
        delete window.confirmAction;
        delete window.cancelConfirm;
    };
    
    window.cancelConfirm = function() {
        modal.remove();
        if (onCancel) onCancel();
        delete window.confirmAction;
        delete window.cancelConfirm;
    };
    
    // Fermer avec Escape
    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.cancelConfirm();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Payment form submission
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            payment_status: formData.get('payment_status'),
            _token: '{{ csrf_token() }}'
        };
        
        fetch('{{ route("admin.bookings.update-payment", $booking) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Paiement mis à jour avec succès!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Erreur: ' + (data.message || 'Mise à jour échouée'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erreur lors de la mise à jour', 'error');
        });
    });
    
    // Status form submission
    document.getElementById('status-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            status: formData.get('status'),
            _token: '{{ csrf_token() }}'
        };
        
        fetch('{{ route("admin.bookings.update-status", $booking) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Statut mis à jour avec succès!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Erreur: ' + (data.message || 'Mise à jour échouée'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erreur lors de la mise à jour', 'error');
        });
    });
});

function sendConfirmationEmail() {
    // Afficher le modal de confirmation d'envoi d'email
    const modal = document.createElement('div');
    modal.id = 'email-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Envoyer confirmation</h3>
                <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="email-form">
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
    `;
    
    document.body.appendChild(modal);
    
    // Gérer l'envoi du formulaire
    document.getElementById('email-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
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
            console.log('Send confirmation response:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('Email de confirmation envoyé avec succès', 'success');
                closeEmailModal();
            } else {
                showAlert('Erreur lors de l\'envoi: ' + (data.message || 'Erreur inconnue'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erreur lors de l\'envoi de l\'email', 'error');
        })
        .finally(() => {
            // Masquer le loading
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        });
    });
}

function generateInvoice() {
    // Afficher un modal de chargement
    showAlert('Génération de la facture en cours...', 'info');
    
    fetch('{{ route("booking.generate-invoice", $booking) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Generate invoice response:', response.status, response.statusText);
        if (response.ok) {
            return response.blob();
        }
        
        // Essayer de lire le message d'erreur si possible
        return response.text().then(text => {
            console.error('Server response:', text);
            throw new Error(`HTTP ${response.status}: ${response.statusText} - ${text.substring(0, 100)}`);
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
        
        // Fermer le modal de chargement et afficher le succès
        document.querySelector('.fixed')?.remove();
        showAlert('Facture générée et téléchargée avec succès', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        // Fermer le modal de chargement et afficher l'erreur
        document.querySelector('.fixed')?.remove();
        showAlert('Erreur lors de la génération de la facture', 'error');
    });
}

function closeEmailModal() {
    const modal = document.getElementById('email-modal');
    if (modal) {
        modal.remove();
    }
}

function cancelBooking() {
    showConfirm('Êtes-vous sûr de vouloir annuler cette réservation ?', function() {
        const data = {
            status: 'cancelled',
            _token: '{{ csrf_token() }}'
        };
        
        fetch('{{ route("admin.bookings.update-status", $booking) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Réservation annulée avec succès!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Erreur: ' + (data.message || 'Annulation échouée'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erreur lors de l\'annulation', 'error');
        });
    });
}
</script>
@endpush
@endsection
