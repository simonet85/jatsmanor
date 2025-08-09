@extends('layouts.app')

@section('title', 'Résidences - Jatsmanor')

@section('content')

    <section class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">
            {{ $pageTitle ?? trans('messages.residences.title') }}
        </h2>

        @if(isset($pageDescription))
            <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
                {{ function_exists('getResidenceDescription') ? getResidenceDescription($pageDescription) : trans('messages.residences.description') }}
            </p>
        @endif

        <!-- Filtres -->
        @include('partials.room-filters')

        <!-- Résultats -->
        @if(isset($resultsCount))
            <div class="mb-6 text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                {{ trans_choice('messages.residences.found', $resultsCount, ['count' => $resultsCount]) }}
                @if(request()->hasAny(['type', 'price_max', 'amenity']))
                    {{ trans('messages.residences.with_criteria') }}
                @endif
            </div>
        @endif

        <!-- Résidences -->
        @include('partials.rooms-grid')

        <!-- Pagination -->
        @include('partials.pagination')
    </section>
@endsection
