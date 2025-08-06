<tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
    @forelse($users as $user)
    <tr class="hover:bg-gray-50 transition-colors duration-200">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500">ID: #{{ $user->id }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                <div class="flex items-center">
                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                    {{ $user->email }}
                </div>
                @if($user->phone)
                    <div class="flex items-center mt-1">
                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                        {{ $user->phone }}
                    </div>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($user->roles->isNotEmpty())
                @foreach($user->roles as $role)
                    @php
                        $roleColors = [
                            'Administrator' => 'bg-red-100 text-red-800',
                            'Client' => 'bg-blue-100 text-blue-800',
                        ];
                        $roleIcons = [
                            'Administrator' => 'fas fa-user-shield',
                            'Client' => 'fas fa-user',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800' }}">
                        <i class="{{ $roleIcons[$role->name] ?? 'fas fa-user' }} mr-1"></i>
                        {{ $role->name }}
                    </span>
                @endforeach
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-question mr-1"></i>
                    Aucun rôle
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($user->is_active)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                    Actif
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                    Inactif
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            <div class="flex items-center">
                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                {{ format_local_date($user->created_at) }}
            </div>
            <div class="text-xs text-gray-400 mt-1">
                {{ $user->created_at->diffForHumans() }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center space-x-2">
                <button onclick="viewUser({{ $user->id }})" 
                        class="text-blue-600 hover:text-blue-900 transition-colors"
                        title="Voir les détails">
                    <i class="fas fa-eye text-lg"></i>
                </button>
                
                <button onclick="editUser({{ $user->id }})" 
                        class="text-green-600 hover:text-green-900 transition-colors"
                        title="Modifier">
                    <i class="fas fa-edit text-lg"></i>
                </button>
                
                @if($user->id !== auth()->id())
                    <button onclick="toggleUserStatus({{ $user->id }})" 
                            class="{{ $user->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }} transition-colors"
                            title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                        <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }} text-lg"></i>
                    </button>
                    
                    @if(!$user->bookings()->exists())
                        <button onclick="deleteUser({{ $user->id }})" 
                                class="text-red-600 hover:text-red-900 transition-colors"
                                title="Supprimer">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    @else
                        <span class="text-gray-400" title="Impossible de supprimer - Utilisateur avec réservations">
                            <i class="fas fa-trash text-lg"></i>
                        </span>
                    @endif
                @else
                    <span class="text-gray-400" title="Vous ne pouvez pas modifier votre propre compte ici">
                        <i class="fas fa-lock text-lg"></i>
                    </span>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
            <div class="text-center">
                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                <p class="text-sm text-gray-500">Les utilisateurs apparaîtront ici une fois créés.</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
