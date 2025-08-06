@extends('dashboard.layout')

@section('title', 'Mes Réservations')

@section('content')
<div class="space-y-6">
    <!-- Header avec statistiques -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Réservations</h1>
                <p class="text-gray-600">Consultez et gérez vos réservations</p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6" id="booking-stats">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">Total</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600">En attente</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['pending_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600">Confirmées</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['confirmed_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-flag-checkered text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">Terminées</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['completed_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-600">Annulées</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['cancelled_bookings'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="search-input" placeholder="Rechercher par nom de résidence ou lieu..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ request('search') }}">
            </div>
            
            <div class="flex gap-2">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminées</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                </select>

                <button id="btn-reset-filters" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table des réservations -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Réservation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Résidence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dates & Durée
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Invités
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                @include('client.partials.bookings-table', ['bookings' => $bookings])
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200" id="pagination-container">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une réservation -->
<div id="booking-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Détails de la réservation</h3>
            <button id="close-details-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div id="booking-details-content">
            <!-- Le contenu sera chargé dynamiquement -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingDetailsModal = document.getElementById('booking-details-modal');
    
    // Gestion des modals
    const closeModal = () => {
        bookingDetailsModal.classList.add('hidden');
    };
    
    // Event listeners pour les modals
    document.getElementById('close-details-modal').addEventListener('click', closeModal);
    
    // Fermer les modals en cliquant à l'extérieur
    bookingDetailsModal.addEventListener('click', (e) => {
        if (e.target === bookingDetailsModal) closeModal();
    });
    
    // Fonction pour voir les détails d'une réservation
    window.viewBooking = function(bookingId) {
        fetch(`{{ route("client.bookings.index") }}/${bookingId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const booking = data.booking;
                document.getElementById('booking-details-content').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Informations de la réservation</h4>
                                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Référence:</span>
                                        <span class="font-medium">${booking.booking_reference}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Statut:</span>
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            booking.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                            booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            booking.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                            'bg-blue-100 text-blue-800'
                                        }">${booking.status}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date de réservation:</span>
                                        <span class="font-medium">${new Date(booking.created_at).toLocaleDateString('fr-FR')}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Détails du séjour</h4>
                                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Arrivée:</span>
                                        <span class="font-medium">${new Date(booking.check_in).toLocaleDateString('fr-FR')}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Départ:</span>
                                        <span class="font-medium">${new Date(booking.check_out).toLocaleDateString('fr-FR')}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Durée:</span>
                                        <span class="font-medium">${booking.total_nights} nuit${booking.total_nights > 1 ? 's' : ''}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Invités:</span>
                                        <span class="font-medium">${booking.guests} personne${booking.guests > 1 ? 's' : ''}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Résidence</h4>
                                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                    <div class="font-medium text-lg">${booking.residence.name}</div>
                                    <div class="text-gray-600">${booking.residence.location}</div>
                                    <div class="text-sm text-gray-500">${booking.residence.description || ''}</div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Informations financières</h4>
                                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Prix par nuit:</span>
                                        <span class="font-medium">${booking.residence.price_per_night.toLocaleString()} FCFA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nombre de nuits:</span>
                                        <span class="font-medium">${booking.total_nights}</span>
                                    </div>
                                    <div class="flex justify-between font-semibold text-lg border-t pt-2">
                                        <span>Total:</span>
                                        <span>${(booking.total_amount || (booking.residence.price_per_night * booking.total_nights)).toLocaleString()} FCFA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Statut du paiement:</span>
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            booking.payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                            booking.payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            booking.payment_status === 'failed' ? 'bg-red-100 text-red-800' :
                                            'bg-gray-100 text-gray-800'
                                        }">${booking.payment_status}</span>
                                    </div>
                                </div>
                            </div>
                            
                            ${booking.status !== 'cancelled' && booking.status !== 'completed' && new Date(booking.check_in) > new Date() ? `
                                <div class="pt-4">
                                    <button onclick="cancelBooking(${booking.id})" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-times mr-2"></i>
                                        Annuler la réservation
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                bookingDetailsModal.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement des détails', 'error');
        });
    };
    
    // Fonction pour annuler une réservation
    window.cancelBooking = function(bookingId) {
        if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
            fetch(`{{ route("client.bookings.index") }}/${bookingId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal();
                    loadBookings();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de l\'annulation', 'error');
            });
        }
    };
    
    // Fonction pour recharger la liste des réservations
    function loadBookings() {
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (statusFilter.value) params.append('status', statusFilter.value);
        
        fetch(`{{ route("client.bookings.index") }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('tbody').innerHTML = data.html;
                document.getElementById('pagination-container').innerHTML = data.pagination;
                
                // Mettre à jour les statistiques
                if (data.stats) {
                    document.querySelector('#booking-stats .bg-blue-50 .text-2xl').textContent = data.stats.total_bookings;
                    document.querySelector('#booking-stats .bg-yellow-50 .text-2xl').textContent = data.stats.pending_bookings;
                    document.querySelector('#booking-stats .bg-green-50 .text-2xl').textContent = data.stats.confirmed_bookings;
                    document.querySelector('#booking-stats .bg-blue-50:last-child .text-2xl').textContent = data.stats.completed_bookings;
                    document.querySelector('#booking-stats .bg-red-50 .text-2xl').textContent = data.stats.cancelled_bookings;
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement', 'error');
        });
    }
    
    // Event listeners pour les filtres
    let searchTimeout;
    document.getElementById('search-input').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadBookings();
        }, 300);
    });
    
    document.getElementById('status-filter').addEventListener('change', loadBookings);
    
    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('search-input').value = '';
        document.getElementById('status-filter').value = '';
        loadBookings();
    });
    
    // Fonction toast
    function showToast(message, type = 'info') {
        // Créer ou récupérer le conteneur de toast
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(toastContainer);
        }

        // Créer le toast
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300`;
        toast.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        toastContainer.appendChild(toast);

        // Animation d'entrée
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);

        // Auto-suppression
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }
});
</script>
@endpush
