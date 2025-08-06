<section class="relative bg-cover bg-center h-[70vh] flex items-center justify-center" 
         style="background-image: url('{{ $heroImage ?? (setting('hero_image') ? asset('storage/' . setting('hero_image')) : asset('images/hero.jpeg')) }}')">
    <div class="bg-black bg-opacity-60 absolute inset-0"></div>
    <div class="relative z-10 text-center text-white px-4">
        <h2 class="text-3xl md:text-5xl font-bold mb-4">
            {{ trans('messages.home.hero_title') }}
        </h2>
        <p class="text-lg md:text-xl mb-6">
            {{ trans('messages.home.hero_subtitle') }}
        </p>
        @if($showButtons ?? true)
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ $primaryButtonUrl ?? route('residences') }}" class="bg-yellow-500 hover:bg-yellow-600 px-6 py-2 rounded text-white font-semibold transition-colors duration-200">
                {{ $primaryButtonText ?? trans('messages.home.hero_cta') }}
            </a>
            <a href="{{ $secondaryButtonUrl ?? route('galerie') }}" class="bg-white hover:bg-gray-200 px-6 py-2 rounded text-blue-900 font-semibold transition-colors duration-200">
                {{ $secondaryButtonText ?? trans('messages.home.gallery_button') }}
            </a>
        </div>
        @endif
    </div>
</section>