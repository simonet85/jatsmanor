<!-- Room Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-8 flex flex-wrap gap-4 items-center justify-between">
    <form method="GET" action="{{ route('residences') }}" class="flex flex-wrap gap-4 items-center w-full">
        <div class="flex flex-wrap gap-4 items-center flex-1">
            <div>
                <label class="block text-xs font-semibold mb-1">Type de chambre</label>
                <select name="type" class="border p-2 rounded w-40">
                    <option value="">Toutes</option>
                    <option value="single" {{ request('type') == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="double" {{ request('type') == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="suite" {{ request('type') == 'suite' ? 'selected' : '' }}>Suite</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold mb-1">Prix max</label>
                <select name="price_max" class="border p-2 rounded w-32">
                    <option value="">Indifférent</option>
                    <option value="15000" {{ request('price_max') == '15000' ? 'selected' : '' }}>15 000 XOF</option>
                    <option value="25000" {{ request('price_max') == '25000' ? 'selected' : '' }}>25 000 XOF</option>
                    <option value="40000" {{ request('price_max') == '40000' ? 'selected' : '' }}>40 000 XOF</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold mb-1">Équipements</label>
                <select name="amenity" class="border p-2 rounded w-40">
                    <option value="">Indifférent</option>
                    <option value="wifi" {{ request('amenity') == 'wifi' ? 'selected' : '' }}>Wi-Fi</option>
                    <option value="ac" {{ request('amenity') == 'ac' ? 'selected' : '' }}>Climatisation</option>
                    <option value="parking" {{ request('amenity') == 'parking' ? 'selected' : '' }}>Parking</option>
                    <option value="netflix" {{ request('amenity') == 'netflix' ? 'selected' : '' }}>Netflix</option>
                </select>
            </div>
            
            @if(isset($additionalFilters))
                @foreach($additionalFilters as $filter)
                    <div>
                        <label class="block text-xs font-semibold mb-1">{{ $filter['label'] }}</label>
                        <select name="{{ $filter['name'] }}" class="border p-2 rounded w-40">
                            <option value="">{{ $filter['placeholder'] ?? 'Tous' }}</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            @endif
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            
            @if(request()->hasAny(['type', 'price_max', 'amenity']))
                <a href="{{ route('residences') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            @endif
        </div>
    </form>
</div>
