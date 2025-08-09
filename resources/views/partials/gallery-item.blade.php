<!-- Gallery Item -->
<div class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="relative overflow-hidden">
        <img
            src="{{ str_starts_with($image, 'storage/') ? asset($image) : asset('storage/' . $image) }}"
            alt="{{ $title }}"
            class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
            onclick="openModal('{{ str_starts_with($image, 'storage/') ? asset($image) : asset('storage/' . $image) }}', '{{ $title }}', '{{ $description }}')"
            onerror="console.log('Erreur de chargement image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center" style="display: none;">
            <i class="fas fa-images text-blue-400 text-4xl"></i>
        </div>
        @if(isset($category))
            <span class="absolute top-2 left-2 bg-blue-800 text-white text-xs px-2 py-1 rounded">
                {{ $category }}
            </span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-semibold mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-600">{{ function_exists('getResidenceDescription') && isset($residence) ? getResidenceDescription($residence) : $description }}</p>
        @if(isset($features) && is_array($features))
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach($features as $feature)
                    <span class="bg-gray-100 text-xs px-2 py-1 rounded">{{ $feature }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>
