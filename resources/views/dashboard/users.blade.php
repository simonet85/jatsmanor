@extends('dashboard.layout')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header avec statistiques -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
                <p class="text-gray-600">Gérez les utilisateurs et leurs permissions</p>
            </div>
            <button id="btn-add-user" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Nouvel Utilisateur
            </button>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6" id="user-stats">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">Total</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-check text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600">Actifs</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['active_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-times text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-600">Inactifs</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['inactive_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-shield text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-600">Administrateurs</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['administrators'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600">Clients</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['clients'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="search-input" placeholder="Rechercher par nom, email ou téléphone..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ request('search') }}">
            </div>
            
            <div class="flex gap-2">
                <select id="role-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                </select>

                <button id="btn-reset-filters" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table des utilisateurs -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Inscrit le
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                @include('dashboard.partials.users-table', ['users' => $users])
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200" id="pagination-container">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal pour ajouter/éditer utilisateur -->
<div id="user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modal-title" class="text-xl font-bold text-gray-900">Nouvel Utilisateur</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="user-form">
            @csrf
            <div class="space-y-4">
                <input type="hidden" id="user-id" name="user_id">
                <input type="hidden" id="form-method" name="_method" value="POST">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="text-red-500 text-sm mt-1 hidden" id="name-error"></div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="text-red-500 text-sm mt-1 hidden" id="email-error"></div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Téléphone
                    </label>
                    <input type="tel" id="phone" name="phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="text-red-500 text-sm mt-1 hidden" id="phone-error"></div>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Rôle <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="role-error"></div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <span id="password-label">Mot de passe</span> <span class="text-red-500" id="password-required">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="text-red-500 text-sm mt-1 hidden" id="password-error"></div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <span id="password-confirmation-label">Confirmer le mot de passe</span> <span class="text-red-500" id="password-confirmation-required">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="text-red-500 text-sm mt-1 hidden" id="password_confirmation-error"></div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Compte actif
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    <span id="submit-text">Créer</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour voir les détails d'un utilisateur -->
<div id="user-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Détails de l'utilisateur</h3>
            <button id="close-details-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div id="user-details-content">
            <!-- Le contenu sera chargé dynamiquement -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userModal = document.getElementById('user-modal');
    const userDetailsModal = document.getElementById('user-details-modal');
    const userForm = document.getElementById('user-form');
    
    // Gestion des modals
    const openModal = (isEdit = false, userData = null) => {
        const modalTitle = document.getElementById('modal-title');
        const submitText = document.getElementById('submit-text');
        const passwordLabel = document.getElementById('password-label');
        const passwordRequired = document.getElementById('password-required');
        const passwordConfirmationLabel = document.getElementById('password-confirmation-label');
        const passwordConfirmationRequired = document.getElementById('password-confirmation-required');
        const formMethod = document.getElementById('form-method');
        
        if (isEdit) {
            modalTitle.textContent = 'Modifier l\'utilisateur';
            submitText.textContent = 'Mettre à jour';
            passwordLabel.textContent = 'Nouveau mot de passe';
            passwordRequired.style.display = 'none';
            passwordConfirmationLabel.textContent = 'Confirmer le nouveau mot de passe';
            passwordConfirmationRequired.style.display = 'none';
            formMethod.value = 'PUT';
            
            // Remplir le formulaire avec les données existantes
            if (userData) {
                document.getElementById('user-id').value = userData.id;
                document.getElementById('name').value = userData.name;
                document.getElementById('email').value = userData.email;
                document.getElementById('phone').value = userData.phone || '';
                document.getElementById('role').value = userData.roles[0]?.name || '';
                document.getElementById('is_active').checked = userData.is_active;
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;
            }
        } else {
            modalTitle.textContent = 'Nouvel utilisateur';
            submitText.textContent = 'Créer';
            passwordLabel.textContent = 'Mot de passe';
            passwordRequired.style.display = 'inline';
            passwordConfirmationLabel.textContent = 'Confirmer le mot de passe';
            passwordConfirmationRequired.style.display = 'inline';
            formMethod.value = 'POST';
            
            // Réinitialiser le formulaire
            userForm.reset();
            document.getElementById('user-id').value = '';
            document.getElementById('is_active').checked = true;
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
        }
        
        // Effacer les erreurs
        document.querySelectorAll('.text-red-500').forEach(el => {
            if (el.id.endsWith('-error')) {
                el.classList.add('hidden');
                el.textContent = '';
            }
        });
        
        userModal.classList.remove('hidden');
    };
    
    const closeModal = () => {
        userModal.classList.add('hidden');
        userDetailsModal.classList.add('hidden');
    };
    
    // Event listeners pour les modals
    document.getElementById('btn-add-user').addEventListener('click', () => openModal());
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('cancel-btn').addEventListener('click', closeModal);
    document.getElementById('close-details-modal').addEventListener('click', closeModal);
    
    // Fermer les modals en cliquant à l'extérieur
    [userModal, userDetailsModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    });
    
    // Gestion du formulaire utilisateur
    userForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const userId = document.getElementById('user-id').value;
        const method = document.getElementById('form-method').value;
        
        let url = '{{ route("admin.users.store") }}';
        if (method === 'PUT' && userId) {
            url = `{{ route("admin.users.index") }}/${userId}`;
        }
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                loadUsers(); // Recharger la liste des utilisateurs
            } else {
                if (data.errors) {
                    // Afficher les erreurs de validation
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    showToast(data.message || 'Une erreur est survenue', 'error');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        }
    });
    
    // Fonctions de gestion des utilisateurs
    window.editUser = function(userId) {
        fetch(`{{ route("admin.users.index") }}/${userId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                openModal(true, data.user);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement des données', 'error');
        });
    };
    
    window.viewUser = function(userId) {
        fetch(`{{ route("admin.users.index") }}/${userId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('user-details-content').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom</label>
                                <p class="mt-1 text-sm text-gray-900">${data.user.name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">${data.user.email}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <p class="mt-1 text-sm text-gray-900">${data.user.phone || 'Non renseigné'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                <p class="mt-1 text-sm text-gray-900">${data.user.roles.map(role => role.name).join(', ')}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Statut</label>
                                <p class="mt-1 text-sm ${data.user.is_active ? 'text-green-600' : 'text-red-600'}">${data.user.is_active ? 'Actif' : 'Inactif'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Inscrit le</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(data.user.created_at).toLocaleDateString('fr-FR')}</p>
                            </div>
                        </div>
                        
                        ${data.recent_bookings && data.recent_bookings.length > 0 ? `
                            <div class="mt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Réservations récentes</h4>
                                <div class="space-y-2">
                                    ${data.recent_bookings.map(booking => `
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="font-medium">${booking.residence.name}</p>
                                                <p class="text-sm text-gray-600">${new Date(booking.check_in).toLocaleDateString('fr-FR')} - ${new Date(booking.check_out).toLocaleDateString('fr-FR')}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full ${
                                                booking.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                booking.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                                'bg-blue-100 text-blue-800'
                                            }">${booking.status}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : '<p class="text-gray-500 mt-4">Aucune réservation trouvée</p>'}
                    </div>
                `;
                userDetailsModal.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors du chargement des détails', 'error');
        });
    };
    
    window.toggleUserStatus = function(userId) {
        if (confirm('Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?')) {
            fetch(`{{ route("admin.users.index") }}/${userId}/toggle-status`, {
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
                    loadUsers();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors du changement de statut', 'error');
            });
        }
    };
    
    window.deleteUser = function(userId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
            fetch(`{{ route("admin.users.index") }}/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    loadUsers();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de la suppression', 'error');
            });
        }
    };
    
    // Fonction pour recharger la liste des utilisateurs
    function loadUsers() {
        const searchInput = document.getElementById('search-input');
        const roleFilter = document.getElementById('role-filter');
        const statusFilter = document.getElementById('status-filter');
        
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (roleFilter.value) params.append('role', roleFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);
        
        fetch(`{{ route("admin.users.index") }}?${params.toString()}`, {
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
                    document.querySelector('#user-stats .bg-blue-50 .text-2xl').textContent = data.stats.total_users;
                    document.querySelector('#user-stats .bg-green-50 .text-2xl').textContent = data.stats.active_users;
                    document.querySelector('#user-stats .bg-red-50 .text-2xl').textContent = data.stats.inactive_users;
                    document.querySelector('#user-stats .bg-purple-50 .text-2xl').textContent = data.stats.administrators;
                    document.querySelector('#user-stats .bg-yellow-50 .text-2xl').textContent = data.stats.clients;
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
            loadUsers();
        }, 300);
    });
    
    document.getElementById('role-filter').addEventListener('change', loadUsers);
    document.getElementById('status-filter').addEventListener('change', loadUsers);
    
    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('search-input').value = '';
        document.getElementById('role-filter').value = '';
        document.getElementById('status-filter').value = '';
        loadUsers();
    });
    
    // Fonction toast (réutiliser celle existante du booking management)
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
