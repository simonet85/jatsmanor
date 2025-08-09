@extends('dashboard.layout')

@section('title', 'Gérer les équipements')
@section('subtitle', 'Ajouter, modifier et supprimer les équipements disponibles')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Gérer les équipements</h1>
        <button id="addAmenityBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Ajouter un équipement
        </button>
    </div>

    <!-- Amenities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($amenities as $amenity)
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="{{ $amenity->icon ?? 'fas fa-check' }} text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $amenity->name }}</h3>
                        @if($amenity->description)
                            <p class="text-sm text-gray-600">{{ $amenity->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="editAmenity({{ $amenity->id }}, '{{ $amenity->name }}', '{{ $amenity->icon ?? 'fas fa-check' }}', '{{ $amenity->description ?? '' }}')" 
                            class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteAmenity({{ $amenity->id }}, '{{ $amenity->name }}')" 
                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <!-- Usage count -->
            <div class="text-sm text-gray-500">
                <i class="fas fa-building mr-1"></i>
                Utilisé par {{ $amenity->residences()->count() }} résidence(s)
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-wifi text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun équipement</h3>
            <p class="text-gray-500">Commencez par ajouter des équipements pour vos résidences.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Amenity Modal -->
<div id="amenityModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="modalTitle" class="text-xl font-semibold">Ajouter un équipement</h3>
                    <button onclick="closeAmenityModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="amenityForm">
                    <input type="hidden" id="amenityId" name="id">
                    
                    <div class="mb-4">
                        <label for="amenityName" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de l'équipement *
                        </label>
                        <input type="text" id="amenityName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="amenityIcon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icône FontAwesome
                        </label>
                        <input type="text" id="amenityIcon" name="icon" placeholder="fas fa-wifi"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Ex: fas fa-wifi, fas fa-parking, fas fa-tv</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="amenityDescription" class="block text-sm font-medium text-gray-700 mb-2">
                            Description (optionnel)
                        </label>
                        <textarea id="amenityDescription" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" id="submitBtn" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                        <button type="button" onclick="closeAmenityModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isEditing = false;

// Show modal for adding new amenity
document.getElementById('addAmenityBtn').addEventListener('click', function() {
    isEditing = false;
    document.getElementById('modalTitle').textContent = 'Ajouter un équipement';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i>Enregistrer';
    document.getElementById('amenityForm').reset();
    document.getElementById('amenityId').value = '';
    document.getElementById('amenityModal').classList.remove('hidden');
});

// Edit amenity
function editAmenity(id, name, icon, description) {
    isEditing = true;
    document.getElementById('modalTitle').textContent = 'Modifier l\'équipement';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save mr-2"></i>Mettre à jour';
    document.getElementById('amenityId').value = id;
    document.getElementById('amenityName').value = name;
    document.getElementById('amenityIcon').value = icon;
    document.getElementById('amenityDescription').value = description;
    document.getElementById('amenityModal').classList.remove('hidden');
}

// Close modal
function closeAmenityModal() {
    document.getElementById('amenityModal').classList.add('hidden');
    document.getElementById('amenityForm').reset();
}

// Handle form submission
document.getElementById('amenityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        icon: formData.get('icon') || 'fas fa-check',
        description: formData.get('description')
    };
    
    const amenityId = formData.get('id');
    const url = amenityId ? `/admin/amenities/${amenityId}` : '/admin/amenities';
    const method = amenityId ? 'PUT' : 'POST';
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeAmenityModal();
            // Reload page to show updated data
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById('amenity' + field.charAt(0).toUpperCase() + field.slice(1));
                    if (input) {
                        input.classList.add('border-red-500');
                        input.classList.add('focus:ring-red-500');
                        input.classList.add('focus:border-red-500');
                    }
                });
            }
        }
    })
    .catch(error => {
        showToast('Erreur lors de l\'enregistrement', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Delete amenity
function deleteAmenity(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'équipement "${name}" ?`)) {
        fetch(`/admin/amenities/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors de la suppression', 'error');
        });
    }
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium toast-enter ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('toast-enter');
        toast.classList.add('toast-exit');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
</script>
@endpush
