@extends('dashboard.layout')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Réservations</h1>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Référence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates & Durée</th>
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
@endsection

@push('scripts')
<script>
let currentBookingId = null;
let statusDropdown = null;

// Toast notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 mb-4 transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.getElementById('toast-container').appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Fonction de recherche et filtrage
function loadBookings() {
    const formData = new FormData();
    formData.append('search', document.getElementById('search-input').value);
    formData.append('status', document.getElementById('status-filter').value);
    formData.append('show_deleted', document.getElementById('show-deleted').checked);
    
    fetch('{{ route("admin.bookings.index") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('#bookings-table-body').innerHTML = data.html;
            // Mettre à jour les statistiques
            if (data.stats) {
                document.getElementById('stat-total').textContent = data.stats.total_bookings;
                document.getElementById('stat-pending').textContent = data.stats.pending_bookings;
                document.getElementById('stat-confirmed').textContent = data.stats.confirmed_bookings;
                document.getElementById('stat-revenue').textContent = new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'XOF',
                    minimumFractionDigits: 0
                }).format(data.stats.total_revenue) + ' FCFA';
            }
            bindEventHandlers();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du chargement des données', 'error');
    });
}

// Event handlers
function bindEventHandlers() {
    // Voir les détails
    document.querySelectorAll('.btn-view-booking').forEach(btn => {
        btn.onclick = function() {
            const bookingId = this.dataset.id;
            viewBookingDetails(bookingId);
        };
    });

    // Supprimer (soft delete)
    document.querySelectorAll('.btn-delete-booking').forEach(btn => {
        btn.onclick = function() {
            const bookingId = this.dataset.id;
            if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
                deleteBooking(bookingId);
            }
        };
    });

    // Restaurer
    document.querySelectorAll('.btn-restore-booking').forEach(btn => {
        btn.onclick = function() {
            const bookingId = this.dataset.id;
            if (confirm('Êtes-vous sûr de vouloir restaurer cette réservation ?')) {
                restoreBooking(bookingId);
            }
        };
    });

    // Supprimer définitivement
    document.querySelectorAll('.btn-force-delete-booking').forEach(btn => {
        btn.onclick = function() {
            const bookingId = this.dataset.id;
            if (confirm('ATTENTION: Cette action est irréversible. Supprimer définitivement cette réservation ?')) {
                forceDeleteBooking(bookingId);
            }
        };
    });

    // Dropdown statut
    document.querySelectorAll('.status-dropdown-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.stopPropagation();
            currentBookingId = this.dataset.id;
            showStatusDropdown(this);
        };
    });
}

// Actions CRUD
function viewBookingDetails(bookingId) {
    fetch(`{{ url('admin/bookings') }}/${bookingId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showBookingModal(data.booking);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du chargement des détails', 'error');
    });
}

function updateBookingStatus(bookingId, status) {
    const url = `{{ url('admin/bookings') }}/${bookingId}/status`;
    console.log('Updating status for booking:', bookingId, 'to:', status);
    console.log('URL:', url);
    
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast(data.message || 'Statut mis à jour avec succès');
            loadBookings();
        } else {
            showToast(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la mise à jour: ' + error.message, 'error');
    });
}

function deleteBooking(bookingId) {
    fetch(`{{ url('admin/bookings') }}/${bookingId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            loadBookings();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression', 'error');
    });
}

function restoreBooking(bookingId) {
    fetch(`{{ url('admin/bookings') }}/${bookingId}/restore`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            loadBookings();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la restauration', 'error');
    });
}

function forceDeleteBooking(bookingId) {
    fetch(`{{ url('admin/bookings') }}/${bookingId}/force-delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            loadBookings();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression définitive', 'error');
    });
}

// Modal et dropdown
function showBookingModal(booking) {
    const modalContent = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900">Client</h4>
                    <p class="text-gray-600">${booking.user.name}</p>
                    <p class="text-gray-600">${booking.user.email}</p>
                    ${booking.phone || booking.user.phone ? `<p class="text-gray-600"><i class="fas fa-phone mr-1"></i>${booking.phone || booking.user.phone}</p>` : ''}
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Résidence</h4>
                    <p class="text-gray-600">${booking.residence.name}</p>
                    <p class="text-gray-600">${booking.residence.location}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900">Check-in</h4>
                    <p class="text-gray-600">${new Date(booking.check_in).toLocaleDateString('fr-FR')} ${new Date(booking.check_in).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Check-out</h4>
                    <p class="text-gray-600">${new Date(booking.check_out).toLocaleDateString('fr-FR')} ${new Date(booking.check_out).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Invités</h4>
                    <p class="text-gray-600">${booking.guests}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900">Durée</h4>
                    <p class="text-gray-600">${booking.total_nights || 1} nuit(s)</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Prix par nuit</h4>
                    <p class="text-gray-600">${(booking.price_per_night || booking.total_amount).toLocaleString('fr-FR')} FCFA</p>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">Montant Total</h4>
                <p class="text-2xl font-bold text-green-600">${booking.total_amount.toLocaleString('fr-FR')} FCFA</p>
            </div>
            ${booking.special_requests ? `
            <div>
                <h4 class="font-semibold text-gray-900">Demandes spéciales</h4>
                <p class="text-gray-600">${booking.special_requests}</p>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('booking-details-content').innerHTML = modalContent;
    document.getElementById('booking-modal').classList.remove('hidden');
}

function showStatusDropdown(button) {
    const dropdown = document.getElementById('status-dropdown');
    const rect = button.getBoundingClientRect();
    
    dropdown.style.position = 'fixed';
    dropdown.style.top = (rect.bottom + 5) + 'px';
    dropdown.style.left = rect.left + 'px';
    dropdown.classList.remove('hidden');
    
    statusDropdown = dropdown;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    bindEventHandlers();
    
    // Filtres
    document.getElementById('search-input').addEventListener('input', debounce(loadBookings, 500));
    document.getElementById('status-filter').addEventListener('change', loadBookings);
    document.getElementById('show-deleted').addEventListener('change', loadBookings);
    
    // Boutons
    document.getElementById('refresh-btn').addEventListener('click', loadBookings);
    document.getElementById('export-btn').addEventListener('click', function() {
        showToast('Fonctionnalité d\'export en cours de développement', 'info');
    });
    
    // Modal
    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('booking-modal').classList.add('hidden');
    });
    
    // Dropdown statut
    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function() {
            const status = this.dataset.status;
            updateBookingStatus(currentBookingId, status);
            document.getElementById('status-dropdown').classList.add('hidden');
        });
    });
    
    // Fermer dropdown en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (statusDropdown && !statusDropdown.contains(e.target)) {
            statusDropdown.classList.add('hidden');
            statusDropdown = null;
        }
    });
});

// Utilitaires
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush
