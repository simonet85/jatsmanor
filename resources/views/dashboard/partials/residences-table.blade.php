<tbody class="bg-white divide-y divide-gray-200" id="residences-table-body">
    @forelse($residences as $residence)
    <tr data-residence-slug="{{ $residence->slug }}" class="residence-row">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12">
                    @if($residence->image)
                        <img class="h-12 w-12 rounded-lg object-cover" 
                             src="{{ asset($residence->image) }}?v={{ time() }}" 
                             alt="{{ $residence->name }}"
                             loading="lazy"
                             onerror="console.log('Erreur de chargement:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-home text-gray-400"></i>
                        </div>
                    @else
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-home text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $residence->name }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($residence->description, 50) }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $residence->location }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ format_fcfa($residence->price_per_night) }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <button class="toggle-status-btn" data-slug="{{ $residence->slug }}" data-status="{{ $residence->is_active }}">
                @if($residence->is_active)
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                @else
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Inactive
                    </span>
                @endif
            </button>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center gap-3">
                <button class="text-blue-600 hover:text-blue-900 btn-edit-residence" 
                        data-slug="{{ $residence->slug }}" 
                        title="Modifier">
                    <i class="fas fa-edit text-lg"></i>
                </button>
                <button class="text-red-600 hover:text-red-900 btn-delete-residence" 
                        data-slug="{{ $residence->slug }}" 
                        title="Supprimer">
                    <i class="fas fa-trash text-lg"></i>
                </button>
                <a href="{{ route('residence.details', $residence) }}" 
                   class="text-green-600 hover:text-green-900" 
                   title="Voir">
                    <i class="fas fa-eye text-lg"></i>
                </a>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
            <div class="text-center">
                <i class="fas fa-home text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune résidence trouvée</h3>
                <p class="text-sm text-gray-500">Commencez par ajouter votre première résidence.</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
