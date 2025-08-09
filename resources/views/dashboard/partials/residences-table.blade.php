<tbody class="bg-white divide-y divide-gray-200 rounded-lg shadow-sm" id="residences-table-body">
    @forelse($residences as $residence)
    <tr data-residence-slug="{{ $residence->slug }}" class="residence-row hover:bg-blue-50 transition duration-150">
    <td class="px-6 py-4 whitespace-nowrap flex items-center gap-4">
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
                    <div class="text-base font-semibold text-gray-900">
                        @if(app()->getLocale() === 'en' && !empty($residence->name_en))
                            {{ $residence->name_en }}
                        @else
                            {{ $residence->name }}
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 italic">
                        @if(app()->getLocale() === 'en')
                            @if(!empty($residence->short_description_en))
                                {{ Str::limit($residence->short_description_en, 50) }}
                            @elseif(!empty($residence->description_en))
                                {{ Str::limit($residence->description_en, 50) }}
                            @else
                                {{ Str::limit($residence->description ?? '', 50) }}
                            @endif
                        @else
                            @if(!empty($residence->short_description))
                                {{ Str::limit($residence->short_description, 50) }}
                            @else
                                {{ Str::limit($residence->description ?? '', 50) }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
            {{ $residence->location }}
        </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 font-bold">
            {{ format_fcfa($residence->price_per_night) }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <button class="toggle-status-btn bg-gray-100 hover:bg-blue-100 transition px-3 py-1 rounded-full flex items-center gap-2 shadow-sm" data-slug="{{ $residence->slug }}" data-status="{{ $residence->is_active }}">
                @if($residence->is_active)
                    <span class="inline-flex items-center text-xs font-semibold text-green-700">
                        <i class="fas fa-check-circle mr-1"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center text-xs font-semibold text-red-700">
                        <i class="fas fa-times-circle mr-1"></i> Inactive
                    </span>
                @endif
            </button>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center gap-2">
                <button class="btn-edit-residence bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow-sm flex items-center gap-1 transition" data-slug="{{ $residence->slug }}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete-residence bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow-sm flex items-center gap-1 transition" data-slug="{{ $residence->slug }}" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
                <a href="{{ route('residence.details', $residence) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow-sm flex items-center gap-1 transition" title="View">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-gray-50 rounded-lg">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-home text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No residences found</h3>
                <p class="text-base">Start by adding your first residence.</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
