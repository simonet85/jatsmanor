@extends('dashboard.layout')

@section('title', 'Gestion des Réservations')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard - Gestion des Réservations</h1>
        <p class="text-gray-600">Gérez toutes les réservations de vos résidences</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Réservations</p>
                    <p class="text-2xl font-semibold text-gray-900" id="stat-total">{{ $stats['total_bookings'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Attente</p>
                    <p class="text-2xl font-semibold text-gray-900" id="stat-pending">{{ $stats['pending_bookings'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confirmées</p>
                    <p class="text-2xl font-semibold text-gray-900" id="stat-confirmed">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-euro-sign text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Chiffre d'Affaires</p>
                    <p class="text-lg font-semibold text-gray-900" id="stat-revenue">{{ format_fcfa($stats['total_revenue'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Actions -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Recherche -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="search-input"
                               placeholder="Rechercher par client, résidence, ID..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtres -->
                    <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmées</option>
                        <option value="cancelled">Annulées</option>
                        <option value="completed">Terminées</option>
                    </select>

                    <select id="period-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Toutes les périodes</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="year">Cette année</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" id="show-deleted" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Afficher supprimées
                    </label>
                    <button id="export-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        Exporter
                    </button>
                    <button id="refresh-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invités</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                @include('dashboard.partials.bookings-table', ['bookings' => $bookings])
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

<!-- Modal de détails -->
<div id="booking-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Détails de la Réservation</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="booking-details-content">
            <!-- Contenu chargé dynamiquement -->
        </div>
    </div>
</div>

<!-- Dropdown pour changer le statut -->
<div id="status-dropdown" class="hidden absolute bg-white border border-gray-200 rounded-md shadow-lg z-10 py-1 min-w-48">
    <button class="status-option w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-status="pending">
        <i class="fas fa-clock text-yellow-500 mr-2"></i>En attente
    </button>
    <button class="status-option w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-status="confirmed">
        <i class="fas fa-check-circle text-green-500 mr-2"></i>Confirmée
    </button>
    <button class="status-option w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-status="cancelled">
        <i class="fas fa-times-circle text-red-500 mr-2"></i>Annulée
    </button>
    <button class="status-option w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-status="completed">
        <i class="fas fa-flag-checkered text-blue-500 mr-2"></i>Terminée
    </button>
</div>
<!-- Notifications Toast -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script>
// Configuration globale
window.bookingActions = {
    currentBookingId: null,
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

// Système de notifications
function showToast(message, type = 'success', duration = 5000) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const toastId = 'toast-' + Date.now();
    
    const config = {
        success: { icon: 'fas fa-check-circle', bg: 'bg-green-500', text: 'text-white' },
        error: { icon: 'fas fa-times-circle', bg: 'bg-red-500', text: 'text-white' },
        info: { icon: 'fas fa-info-circle', bg: 'bg-blue-500', text: 'text-white' }
    };
    
    const settings = config[type] || config.success;
    
    toast.id = toastId;
    toast.className = `transform transition-all duration-300 ease-in-out ${settings.bg} ${settings.text} px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-80 translate-x-full opacity-0`;
    toast.innerHTML = `
        <i class="${settings.icon}"></i>
        <span>${message}</span>
        <button onclick="removeToast('${toastId}')" class="ml-auto hover:opacity-75">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    if (duration > 0) {
        setTimeout(() => removeToast(toastId), duration);
    }
}

function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }
}

// Gestion des dropdowns de statut
document.addEventListener('click', function(e) {
    // Dropdown de statut
    if (e.target.closest('.status-dropdown-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.status-dropdown-btn');
        const dropdown = document.getElementById('status-dropdown');
        const bookingId = btn.dataset.id;
        const currentStatus = btn.dataset.currentStatus;
        
        // Positionner le dropdown
        const rect = btn.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
        dropdown.style.left = rect.left + 'px';
        dropdown.classList.remove('hidden');
        
        // Stocker l'ID de la réservation
        window.bookingActions.currentBookingId = bookingId;
        
        // Désactiver l'option actuelle
        dropdown.querySelectorAll('.status-option').forEach(option => {
            option.disabled = option.dataset.status === currentStatus;
            option.classList.toggle('opacity-50', option.dataset.status === currentStatus);
        });
    }
    // Fermer le dropdown si on clique ailleurs
    else if (!e.target.closest('#status-dropdown')) {
        document.getElementById('status-dropdown').classList.add('hidden');
    }
});

// Gestion des options de statut
document.addEventListener('click', function(e) {
    if (e.target.closest('.status-option')) {
        const option = e.target.closest('.status-option');
        const newStatus = option.dataset.status;
        const bookingId = window.bookingActions.currentBookingId;
        
        if (!bookingId) return;
        
        // Mettre à jour le statut
        updateBookingStatus(bookingId, newStatus);
        document.getElementById('status-dropdown').classList.add('hidden');
    }
});

// Fonction pour mettre à jour le statut
async function updateBookingStatus(bookingId, newStatus) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.bookingActions.csrfToken
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (response.ok) {
            const data = await response.json();
            showToast(data.message || 'Statut mis à jour avec succès', 'success');
            
            // Actualiser le tableau
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error('Erreur lors de la mise à jour');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la mise à jour du statut', 'error');
    }
}

// Gestion des actions des boutons
document.addEventListener('click', function(e) {
    const target = e.target.closest('button');
    if (!target) return;
    
    const bookingId = target.dataset.id;
    if (!bookingId) return;
    
    // Voir les détails
    if (target.classList.contains('btn-view-booking')) {
        viewBookingDetails(bookingId);
    }
    // Supprimer
    else if (target.classList.contains('btn-delete-booking')) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
            deleteBooking(bookingId);
        }
    }
    // Restaurer
    else if (target.classList.contains('btn-restore-booking')) {
        if (confirm('Voulez-vous restaurer cette réservation ?')) {
            restoreBooking(bookingId);
        }
    }
    // Supprimer définitivement
    else if (target.classList.contains('btn-force-delete-booking')) {
        if (confirm('Êtes-vous sûr de vouloir supprimer définitivement cette réservation ? Cette action est irréversible.')) {
            forceDeleteBooking(bookingId);
        }
    }
});

// Voir les détails d'une réservation
async function viewBookingDetails(bookingId) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}`);
        if (response.ok) {
            const data = await response.json();
            showBookingModal(data.booking);
        } else {
            throw new Error('Impossible de charger les détails');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors du chargement des détails', 'error');
    }
}

// Supprimer une réservation
async function deleteBooking(bookingId) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.bookingActions.csrfToken
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showToast(data.message || 'Réservation supprimée', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Erreur lors de la suppression');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression', 'error');
    }
}

// Restaurer une réservation
async function restoreBooking(bookingId) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}/restore`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': window.bookingActions.csrfToken
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showToast(data.message || 'Réservation restaurée', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Erreur lors de la restauration');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la restauration', 'error');
    }
}

// Supprimer définitivement une réservation
async function forceDeleteBooking(bookingId) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}/force-delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.bookingActions.csrfToken
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showToast(data.message || 'Réservation supprimée définitivement', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Erreur lors de la suppression définitive');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression définitive', 'error');
    }
}

// Afficher la modal des détails
function showBookingModal(booking) {
    const modal = document.getElementById('booking-modal');
    const content = document.getElementById('booking-details-content');
    
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Client</h4>
                    <p class="text-sm text-gray-700">${booking.user.name}</p>
                    <p class="text-sm text-gray-500">${booking.user.email}</p>
                    ${booking.phone ? `<p class="text-sm text-gray-500">${booking.phone}</p>` : ''}
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Résidence</h4>
                    <p class="text-sm text-gray-700">${booking.residence.name}</p>
                    <p class="text-sm text-gray-500">${booking.residence.location}</p>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Dates</h4>
                    <p class="text-sm text-gray-700">Du ${new Date(booking.check_in).toLocaleDateString('fr-FR')}</p>
                    <p class="text-sm text-gray-700">Au ${new Date(booking.check_out).toLocaleDateString('fr-FR')}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Invités</h4>
                    <p class="text-sm text-gray-700">${booking.guests} personne${booking.guests > 1 ? 's' : ''}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Montant</h4>
                    <p class="text-sm font-semibold text-gray-900">${new Intl.NumberFormat('fr-FR').format(booking.total_amount)} FCFA</p>
                </div>
            </div>
            ${booking.special_requests ? `
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Demandes spéciales</h4>
                    <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">${booking.special_requests}</p>
                </div>
            ` : ''}
        </div>
    `;
    
    modal.classList.remove('hidden');
}

// Fermer la modal
document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('booking-modal').classList.add('hidden');
});

// Fermer la modal en cliquant à l'extérieur
document.getElementById('booking-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>

@endsection
