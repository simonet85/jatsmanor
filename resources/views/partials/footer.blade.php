<footer class="bg-blue-900 text-white py-8">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-6">
        <div>
            <h4 class="font-semibold mb-2">{{ setting('site_name', 'Jatsmanor') }}</h4>
            <p class="text-sm">
                {{ trans('messages.footer.description') }}
            </p>
        </div>
        <div>
            <h4 class="font-semibold mb-2">{{ trans('messages.footer.navigation') }}</h4>
            <ul class="text-sm space-y-1">
                <li><a href="{{ route('home') }}" class="hover:underline">{{ trans('messages.nav.home') }}</a></li>
                <li><a href="{{ route('residences') }}" class="hover:underline">{{ trans('messages.nav.residences') }}</a></li>
                <li><a href="{{ route('galerie') }}" class="hover:underline">{{ trans('messages.nav.gallery') }}</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">{{ trans('messages.nav.contact') }}</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-2">{{ trans('messages.footer.contact') }}</h4>
            <p class="text-sm">
                <i class="fas fa-location-dot mr-1"></i> {{ setting('contact_address', 'Cocody, Abidjan') }}<br />
                <i class="fas fa-phone mr-1"></i> {{ setting('contact_phone', '+225 07 07 07 07') }}<br />
                <i class="fas fa-envelope mr-1"></i> {{ setting('contact_email', 'contact@jatsmanor.ci') }}
            </p>
        </div>
    </div>
    <div class="text-center text-xs mt-6 text-gray-300">
        © {{ date('Y') }} {{ setting('site_name', 'Jatsmanor') }}. {{ trans('messages.footer.rights') }}
    </div>
</footer>