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
                <div class="ml-4 min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">{{ $residence->name }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($residence->description, 50) }}</div>
                    
                    <!-- Translation status -->
                    <div class="flex items-center gap-2 mt-1">
                        @if($residence->name_en)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Traduit EN
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Non traduit
                            </span>
                        @endif
                        
                        <!-- Show English preview if available -->
                        @if($residence->name_en)
                            <div class="group relative">
                                <i class="fas fa-eye text-gray-400 cursor-help"></i>
                                <div class="hidden group-hover:block absolute z-10 w-64 p-2 bg-black text-white text-xs rounded shadow-lg bottom-full left-0 mb-1">
                                    <div><strong>EN Name:</strong> {{ $residence->name_en }}</div>
                                    @if($residence->description_en)
                                        <div class="mt-1"><strong>EN Desc:</strong> {{ Str::limit($residence->description_en, 80) }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
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
                <!-- Translate button -->
                @if(!$residence->name_en || !$residence->description_en)
                    <button class="text-purple-600 hover:text-purple-900 btn-translate-residence" 
                            data-slug="{{ $residence->slug }}" 
                            title="Traduire en anglais">
                        <i class="fas fa-language text-lg"></i>
                    </button>
                @endif
                
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

<!-- Translation statistics -->
@if($residences->count() > 0)
<tfoot class="bg-gray-50">
    <tr>
        <td colspan="5" class="px-6 py-3 text-sm text-gray-600">
            @php
                $translated = $residences->filter(function($r) { return !empty($r->name_en); })->count();
                $total = $residences->count();
                $percentage = $total > 0 ? round(($translated / $total) * 100, 1) : 0;
            @endphp
            <div class="flex items-center justify-between">
                <span>
                    Traductions: {{ $translated }}/{{ $total }} résidences ({{ $percentage }}%)
                </span>
                <div class="flex items-center gap-4">
                    <button id="translate-all-btn" class="text-purple-600 hover:text-purple-900 text-sm font-medium">
                        <i class="fas fa-language mr-1"></i>
                        Traduire toutes les résidences
                    </button>
                    <button id="check-translations-btn" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        <i class="fas fa-sync mr-1"></i>
                        Vérifier les traductions
                    </button>
                </div>
            </div>
        </td>
    </tr>
</tfoot>
@endif
