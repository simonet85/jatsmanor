@extends('dashboard.layout')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header with Statistics -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Réservations</h1>
            <p class="text-gray-600 mt-2">Gérez les réservations et les paiements</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="stats-container">
            <div class="bg-blue-100 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-600" id="stat-total">{{ $stats['total'] }}</div>
                <div class="text-sm text-blue-800">Total</div>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-yellow-600" id="stat-pending">{{ $stats['pending'] }}</div>
                <div class="text-sm text-yellow-800">En attente</div>
            </div>
            <div class="bg-green-100 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-green-600" id="stat-confirmed">{{ $stats['confirmed'] }}</div>
                <div class="text-sm text-green-800">Confirmées</div>
            </div>
            <div class="bg-red-100 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-red-600" id="stat-payment-pending">{{ $stats['payment_pending'] }}</div>
                <div class="text-sm text-red-800">Paiements en attente</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="Référence, nom, email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Paiement</label>
                <select name="payment_status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all" {{ $paymentStatus == 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="pending" {{ $paymentStatus == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="paid" {{ $paymentStatus == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="failed" {{ $paymentStatus == 'failed' ? 'selected' : '' }}>Échoué</option>
                    <option value="refunded" {{ $paymentStatus == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                </select>
            </div>
            
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            
            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                <i class="fas fa-times mr-2"></i>Reset
            </a>
            
            <button type="button" onclick="refreshBookingsTable()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Rafraîchir
            </button>
            
            <button type="button" onclick="toggleKeyboardHelp()" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors" title="Raccourcis clavier (F1)">
                <i class="fas fa-keyboard mr-2"></i>Aide
            </button>
            
            <button type="button" onclick="exportBookings()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors" title="Exporter les données filtrées">
                <i class="fas fa-download mr-2"></i>Exporter
            </button>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6" id="bulk-actions" style="display: none;">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">Actions groupées:</span>
            
            <select id="bulk-action" class="px-3 py-1 border border-gray-300 rounded text-sm">
                <option value="">Choisir une action</option>
                <option value="update_payment_status">Mettre à jour le paiement</option>
                <option value="update_status">Mettre à jour le statut</option>
                <option value="delete">Supprimer</option>
            </select>
            
            <div id="payment-options" style="display: none;" class="flex gap-2">
                <select id="bulk-payment-status" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="">Statut paiement</option>
                    <option value="pending">En attente</option>
                    <option value="paid">Payé</option>
                    <option value="failed">Échoué</option>
                    <option value="refunded">Remboursé</option>
                </select>
            </div>
            
            <div id="status-options" style="display: none;">
                <select id="bulk-status" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="">Statut</option>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="completed">Terminé</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </div>
            
            <button id="apply-bulk-action" class="px-4 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                Appliquer
            </button>
            
            <button id="cancel-bulk" class="px-4 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-600">
                Annuler
            </button>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Réservation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Résidence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dates
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paiement
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="booking-checkbox rounded border-gray-300" value="{{ $booking->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_reference ?? '#' . $booking->id }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $booking->first_name }} {{ $booking->last_name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->residence->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->guests }} invité(s)</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                au {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $booking->total_nights }} nuit(s)</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($booking->total_amount, 0, ',', ' ') }} FCFA</div>
                            <div class="text-xs text-gray-500">{{ number_format($booking->price_per_night, 0, ',', ' ') }} FCFA/nuit</div>
                        </td>
                        <td class="px-6 py-4">
                            <select class="status-select text-sm border border-gray-300 rounded px-2 py-1" 
                                    data-booking-id="{{ $booking->id }}" data-type="status">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }} class="text-yellow-600">En attente</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }} class="text-green-600">Confirmé</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }} class="text-blue-600">Terminé</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }} class="text-red-600">Annulé</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <select class="payment-status-select text-sm border border-gray-300 rounded px-2 py-1 w-full" 
                                        data-booking-id="{{ $booking->id }}" data-type="payment_status">
                                    <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="failed" {{ $booking->payment_status == 'failed' ? 'selected' : '' }}>Échoué</option>
                                    <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                                </select>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="text-red-600 hover:text-red-800 text-sm delete-booking" 
                                        data-booking-id="{{ $booking->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <div>Aucune réservation trouvée</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@push('scripts')
<script>
// Enhanced Toast System with icons and better styling
function showToast(message, type = 'success', duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now() + Math.random();
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

// Show loading indicator
function showLoading(target = null) {
    if (target) {
        target.style.position = 'relative';
        const loader = document.createElement('div');
        loader.className = 'loading-overlay absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10';
        loader.innerHTML = `
            <div class="flex items-center space-x-2 text-blue-600">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-sm font-medium">Chargement...</span>
            </div>
        `;
        target.appendChild(loader);
    }
}

// Hide loading indicator
function hideLoading(target = null) {
    if (target) {
        const loader = target.querySelector('.loading-overlay');
        if (loader) {
            loader.remove();
        }
    }
}

// Refresh stats without page reload
function refreshStats() {
    const statsContainer = document.getElementById('stats-container');
    if (statsContainer) {
        // Show loading on stats
        statsContainer.style.opacity = '0.6';
        
        // Get current filters
        const urlParams = new URLSearchParams(window.location.search);
        const queryString = urlParams.toString();
        
        fetch(`{{ route('admin.bookings.stats') }}?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update stat values
                document.getElementById('stat-total').textContent = data.stats.total;
                document.getElementById('stat-pending').textContent = data.stats.pending;
                document.getElementById('stat-confirmed').textContent = data.stats.confirmed;
                document.getElementById('stat-payment-pending').textContent = data.stats.payment_pending;
            }
            statsContainer.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
            statsContainer.style.opacity = '1';
        });
    }
}

// Refresh bookings table without page reload
function refreshBookingsTable() {
    const tableContainer = document.querySelector('.overflow-x-auto');
    if (tableContainer) {
        showLoading(tableContainer);
        
        // Get current page URL with filters
        const urlParams = new URLSearchParams(window.location.search);
        
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response and extract the table body
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('tbody');
            const tableBody = document.querySelector('tbody');
            
            if (newTableBody && tableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
                // Re-bind event listeners
                bindTableEventListeners();
            }
            
            hideLoading(tableContainer);
            // Also refresh stats
            refreshStats();
        })
        .catch(error => {
            console.error('Error refreshing table:', error);
            hideLoading(tableContainer);
            showToast('Erreur lors du rafraîchissement', 'error');
        });
    }
}

// Bind event listeners to table elements
function bindTableEventListeners() {
    // Re-bind all table event listeners
    document.querySelectorAll('.status-select, .payment-status-select').forEach(select => {
        select.addEventListener('change', handleStatusChange);
    });
    
    document.querySelectorAll('.delete-booking').forEach(button => {
        button.addEventListener('click', handleDeleteBooking);
    });
}

// Handle status change (extracted for reuse)
function handleStatusChange() {
    const bookingId = this.dataset.bookingId;
    const type = this.dataset.type;
    const value = this.value;
    const originalValue = this.defaultValue;
    
    // Disable the select and show loading
    this.disabled = true;
    this.style.opacity = '0.7';
    
    let url, data;
    
    if (type === 'status') {
        url = `{{ url('admin/bookings') }}/${bookingId}/status`;
        data = { status: value };
    } else {
        url = `{{ url('admin/bookings') }}/${bookingId}/payment`;
        data = {
            payment_status: value
        };
    }
    
    console.log('Updating:', type, 'for booking', bookingId, 'to', value);
    console.log('URL:', url);
    
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
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
            // Show success feedback
            this.classList.add('border-green-500');
            showToast(data.message || 'Mise à jour réussie', 'success');
            setTimeout(() => {
                this.classList.remove('border-green-500');
            }, 2000);
            // Update the default value
            this.defaultValue = value;
        } else {
            showToast('Erreur: ' + (data.message || 'Mise à jour échouée'), 'error');
            // Revert the select
            this.value = originalValue;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erreur lors de la mise à jour: ' + error.message, 'error');
        this.value = originalValue;
    })
    .finally(() => {
        // Re-enable the select
        this.disabled = false;
        this.style.opacity = '1';
    });
}

// Handle delete booking
function handleDeleteBooking(event) {
    const button = event.target.closest('.delete-booking');
    const bookingId = button.dataset.bookingId;
    
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
        return;
    }
    
    // Show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`{{ route('admin.bookings.bulk') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: 'delete',
            booking_ids: [bookingId],
            _token: '{{ csrf_token() }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Réservation supprimée', 'success');
            refreshBookingsTable();
        } else {
            showToast('Erreur: ' + (data.message || 'Suppression échouée'), 'error');
            // Restore button
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-trash"></i>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erreur lors de la suppression', 'error');
        // Restore button
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-trash"></i>';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
    const bulkActionsDiv = document.getElementById('bulk-actions');
    
    selectAllCheckbox.addEventListener('change', function() {
        bookingCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
    
    bookingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
    
    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.booking-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActionsDiv.style.display = 'block';
        } else {
            bulkActionsDiv.style.display = 'none';
        }
    }
    
    // Bulk action type change
    document.getElementById('bulk-action').addEventListener('change', function() {
        const paymentOptions = document.getElementById('payment-options');
        const statusOptions = document.getElementById('status-options');
        
        // Hide all options first
        paymentOptions.style.display = 'none';
        statusOptions.style.display = 'none';
        
        // Show relevant options
        if (this.value === 'update_payment_status') {
            paymentOptions.style.display = 'flex';
        } else if (this.value === 'update_status') {
            statusOptions.style.display = 'block';
        }
    });
    
    // Cancel bulk actions
    document.getElementById('cancel-bulk').addEventListener('click', function() {
        document.querySelectorAll('.booking-checkbox').forEach(cb => cb.checked = false);
        selectAllCheckbox.checked = false;
        bulkActionsDiv.style.display = 'none';
    });
    
    // Apply bulk action
    document.getElementById('apply-bulk-action').addEventListener('click', function() {
        const action = document.getElementById('bulk-action').value;
        if (!action) {
            showToast('Veuillez sélectionner une action', 'warning');
            return;
        }
        
        const checkedBoxes = document.querySelectorAll('.booking-checkbox:checked');
        if (checkedBoxes.length === 0) {
            showToast('Veuillez sélectionner au moins une réservation', 'warning');
            return;
        }
        
        const bookingIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        let data = {
            action: action,
            booking_ids: bookingIds,
            _token: '{{ csrf_token() }}'
        };
        
        // Add specific data based on action
        if (action === 'update_payment_status') {
            const paymentStatus = document.getElementById('bulk-payment-status').value;
            if (paymentStatus) data.payment_status = paymentStatus;
        } else if (action === 'update_status') {
            const status = document.getElementById('bulk-status').value;
            if (!status) {
                showToast('Veuillez sélectionner un statut', 'warning');
                return;
            }
            data.status = status;
        }
        
        if (action === 'delete' && !confirm('Êtes-vous sûr de vouloir supprimer ces réservations ?')) {
            return;
        }
        
        // Send request
        fetch('{{ route("admin.bookings.bulk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                refreshBookingsTable();
            } else {
                showToast('Erreur: ' + (data.message || 'Action échouée'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erreur lors de l\'exécution de l\'action', 'error');
        });
    });
    
    // Individual status updates
    bindTableEventListeners();
    
    // Delete individual booking
    document.querySelectorAll('.delete-booking').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
                return;
            }
            
            const bookingId = this.dataset.bookingId;
            
            fetch(`{{ route('admin.bookings.bulk') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: 'delete',
                    booking_ids: [bookingId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('tr').remove();
                } else {
                    alert('Erreur: ' + (data.message || 'Suppression échouée'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la suppression');
            });
        });
    });
    
    // Real-time search
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500); // Wait 500ms after user stops typing
        });
    }
    
    // Filter change handlers
    document.querySelectorAll('select[name="status"], select[name="payment_status"]').forEach(select => {
        select.addEventListener('change', function() {
            performSearch();
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + R = Refresh table
        if ((e.ctrlKey || e.metaKey) && e.key === 'r' && !e.target.matches('input, textarea, select')) {
            e.preventDefault();
            refreshBookingsTable();
            showToast('Table rafraîchie', 'info', 2000);
        }
        
        // Ctrl/Cmd + F = Focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f' && !e.target.matches('input, textarea, select')) {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Ctrl/Cmd + A = Select all checkboxes
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !e.target.matches('input, textarea, select')) {
            e.preventDefault();
            const selectAllCheckbox = document.getElementById('select-all');
            if (selectAllCheckbox) {
                selectAllCheckbox.click();
            }
        }
        
        // Escape = Clear search
        if (e.key === 'Escape' && e.target.matches('input[name="search"]')) {
            e.target.value = '';
            performSearch();
        }
        
        // Enter in search = Perform search immediately
        if (e.key === 'Enter' && e.target.matches('input[name="search"]')) {
            e.preventDefault();
            performSearch();
        }
    });
    
    // Show keyboard shortcuts help
    let helpVisible = false;
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F1' || (e.key === '?' && e.shiftKey)) {
            e.preventDefault();
            toggleKeyboardHelp();
        }
    });
});

// Perform search with current filters
function performSearch() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    fetch(`{{ route('admin.bookings.index') }}?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.querySelector('tbody');
        
        if (newTableBody) {
            const currentTableBody = document.querySelector('tbody');
            currentTableBody.innerHTML = newTableBody.innerHTML;
            bindTableEventListeners();
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        showToast('Erreur lors de la recherche', 'error');
    });
}

// Keyboard shortcuts help
function toggleKeyboardHelp() {
    let helpModal = document.getElementById('keyboard-help-modal');
    
    if (!helpModal) {
        // Create help modal
        helpModal = document.createElement('div');
        helpModal.id = 'keyboard-help-modal';
        helpModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        helpModal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Raccourcis clavier</h3>
                    <button onclick="toggleKeyboardHelp()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rafraîchir la table</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl + R</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rechercher</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl + F</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tout sélectionner</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl + A</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Vider la recherche</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Échap</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rechercher maintenant</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Entrée</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cette aide</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">F1 ou ?</kbd>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <button onclick="toggleKeyboardHelp()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Fermer
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(helpModal);
        
        // Close on backdrop click
        helpModal.addEventListener('click', function(e) {
            if (e.target === helpModal) {
                toggleKeyboardHelp();
            }
        });
    }
    
    if (helpModal.style.display === 'none' || !helpModal.style.display) {
        helpModal.style.display = 'flex';
    } else {
        helpModal.style.display = 'none';
    }
}

// Export bookings data
function exportBookings() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // Show loading toast
    showToast('Génération de l\'export en cours...', 'info', 0);
    
    // Get current filters
    const params = new URLSearchParams(formData);
    params.append('export', 'csv');
    
    // Create CSV data from current table
    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tr');
    
    let csvContent = '';
    
    // Headers
    const headers = ['ID', 'Client', 'Email', 'Téléphone', 'Résidence', 'Check-in', 'Check-out', 'Statut', 'Statut Paiement', 'Total', 'Date Création'];
    csvContent += headers.join(',') + '\n';
    
    // Data rows (skip header row)
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.querySelectorAll('td');
        
        if (cells.length > 0) {
            const rowData = [];
            
            // Skip checkbox cell
            for (let j = 1; j < cells.length - 1; j++) {
                let cellText = cells[j].textContent.trim();
                
                // Handle select elements
                const select = cells[j].querySelector('select');
                if (select) {
                    cellText = select.options[select.selectedIndex].text;
                }
                
                // Escape commas and quotes
                if (cellText.includes(',') || cellText.includes('"')) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                
                rowData.push(cellText);
            }
            
            csvContent += rowData.join(',') + '\n';
        }
    }
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `reservations_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Remove loading toast and show success
        const loadingToasts = document.querySelectorAll('.toast-notification');
        loadingToasts.forEach(toast => {
            if (toast.textContent.includes('Génération')) {
                removeToast(toast.id);
            }
        });
        
        showToast('Export réussi! Le fichier a été téléchargé.', 'success');
    } else {
        showToast('Export non supporté par votre navigateur', 'error');
    }
}

</script>
@endpush
@endsection
