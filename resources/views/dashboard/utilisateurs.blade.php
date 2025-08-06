@extends('dashboard.layout')

@section('title', 'Utilisateurs')

@section('content')
<h1 class="text-2xl md:text-3xl font-bold mb-8">Gestion des utilisateurs</h1>

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    <div class="flex items-center gap-4">
        <input type="text" placeholder="Rechercher un utilisateur..." 
               class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option>Tous les rôles</option>
            <option>Administrateur</option>
            <option>Client</option>
        </select>
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium" 
            id="btn-add-user">
        <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
    </button>
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users ?? [] as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($user->avatar)
                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">
                                @if($user->email === 'admin@jatsmanor.com')
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Admin</span>
                                @else
                                    Client
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $user->email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $user->phone ?? 'Non renseigné' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->email_verified_at)
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Vérifié
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Non vérifié
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <button class="text-blue-600 hover:text-blue-900 btn-view-user" data-id="{{ $user->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-yellow-900 btn-edit-user" data-id="{{ $user->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($user->email !== 'admin@jatsmanor.com')
                        <button class="text-red-600 hover:text-red-900 btn-delete-user" data-id="{{ $user->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Aucun utilisateur trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    // Voir les détails
    document.querySelectorAll('.btn-view-user').forEach(btn => {
        btn.onclick = function() {
            const userId = this.dataset.id;
            alert('Voir détails de l\'utilisateur #' + userId);
        };
    });

    // Editer
    document.querySelectorAll('.btn-edit-user').forEach(btn => {
        btn.onclick = function() {
            const userId = this.dataset.id;
            alert('Editer utilisateur #' + userId);
        };
    });

    // Supprimer
    document.querySelectorAll('.btn-delete-user').forEach(btn => {
        btn.onclick = function() {
            const userId = this.dataset.id;
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                alert('Utilisateur #' + userId + ' supprimé');
            }
        };
    });
</script>
@endpush
