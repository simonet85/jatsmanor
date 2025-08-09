<!-- Contact Information -->
<div class="bg-white rounded-lg shadow p-6 flex flex-col justify-center">
    <h3 class="font-semibold text-lg mb-4">{{ $title ?? trans('messages.contact_page.contact_info_title') }}</h3>
    
    <ul class="space-y-4 text-gray-700 text-sm">
        <li>
            <i class="fas fa-location-dot text-blue-700 mr-2"></i>
            {{ $address ?? trans('messages.contact.address') . ', ' . trans('messages.contact.city') }}
        </li>
        <li>
            <i class="fas fa-phone text-blue-700 mr-2"></i>
            {{ $phone ?? '+225 07 07 07 07' }}
        </li>
        <li>
            <i class="fas fa-envelope text-blue-700 mr-2"></i>
            {{ $email ?? 'contact@jatsmanor.ci' }}
        </li>
        <li>
            <i class="fas fa-clock text-blue-700 mr-2"></i>
            {{ $hours ?? trans('messages.contact_page.service_24_7') }}
        </li>
    </ul>
    
    <div class="mt-6">
        <h4 class="font-semibold mb-2">{{ $socialTitle ?? trans('messages.contact_page.follow_us') }}</h4>
        <div class="flex gap-4 text-xl">
            @if(isset($socialLinks))
                @foreach($socialLinks as $platform => $url)
                    <a href="{{ $url }}" class="hover:text-yellow-400" target="_blank" rel="noopener">
                        <i class="fab fa-{{ $platform }}"></i>
                    </a>
                @endforeach
            @else
                <a href="#" class="hover:text-yellow-400">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="hover:text-yellow-400">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="hover:text-yellow-400">
                    <i class="fab fa-twitter"></i>
                </a>
            @endif
        </div>
    </div>
    
    @if(isset($additionalInfo))
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold mb-2 text-blue-800">Information importante</h4>
            <p class="text-sm text-blue-700">{{ $additionalInfo }}</p>
        </div>
    @endif
</div>
