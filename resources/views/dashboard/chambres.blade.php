@extends('dashboard.layout')

@section('title', 'Gérer les chambres')

@section('content')
<h1 class="text-2xl md:text-3xl font-bold mb-8">Gérer les chambres</h1>

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    <div class="flex items-center gap-4">
        <input type="text" placeholder="Rechercher une chambre..." 
               class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option>Toutes les résidences</option>
            @foreach($residences ?? [] as $residence)
            <option value="{{ $residence->id }}">{{ $residence->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium" 
            id="btn-add-room">
        <i class="fas fa-plus mr-2"></i>Ajouter une chambre
    </button>
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacité</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modificateur prix</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($rooms ?? [] as $room)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($room->description, 50) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $room->residence->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ ucfirst($room->type) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $room->max_occupancy }} {{ $room->max_occupancy > 1 ? 'personnes' : 'personne' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @if($room->price_modifier > 0)
                        <span class="text-green-600">+{{ number_format($room->price_modifier, 0, ',', '.') }} FCFA</span>
                    @elseif($room->price_modifier < 0)
                        <span class="text-red-600">{{ number_format($room->price_modifier, 0, ',', '.') }} FCFA</span>
                    @else
                        <span class="text-gray-500">Aucun</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <button class="text-blue-600 hover:text-blue-900 btn-edit-room" data-id="{{ $room->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 btn-delete-room" data-id="{{ $room->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Aucune chambre trouvée
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Ajouter -->
<div id="modal-add-room" class="fixed inset-0 bg-black bg-opacity-40 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Ajouter une chambre</h3>
        <form>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la chambre</label>
                    <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Résidence</label>
                    <select class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner une résidence</option>
                        @foreach($residences ?? [] as $residence)
                        <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner un type</option>
                        <option value="standard">Standard</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="suite">Suite</option>
                        <option value="penthouse">Penthouse</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacité maximale</label>
                    <input type="number" min="1" max="10" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modificateur de prix (FCFA)</label>
                    <input type="number" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-add-room')" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    // Ajouter
    document.getElementById('btn-add-room').onclick = function() {
        openModal('modal-add-room');
    };

    // Editer
    document.querySelectorAll('.btn-edit-room').forEach(btn => {
        btn.onclick = function() {
            alert('Fonctionnalité d\'édition à implémenter');
        };
    });

    // Supprimer
    document.querySelectorAll('.btn-delete-room').forEach(btn => {
        btn.onclick = function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?')) {
                alert('Fonctionnalité de suppression à implémenter');
            }
        };
    });
</script>
@endpush
