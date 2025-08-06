<section class="bg-blue-50 py-12">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold mb-4">{{ $testimonialsTitle ?? trans('messages.home.testimonials_title') }}</h2>
        <div class="grid md:grid-cols-3 gap-6 mt-6">
            @foreach($avis ?? [] as $avisClient)
            <div class="bg-white shadow p-4 rounded">
                <p class="text-sm italic">"{{ $avisClient->commentaire }}"</p>
                <div class="mt-2 text-sm font-semibold text-blue-800">
                    {{ $avisClient->nom }}, {{ $avisClient->ville }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
