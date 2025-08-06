@extends('layouts.app')

@section('title', setting('site_name', 'Jatsmanor') . ' - ' . setting('site_description', trans('messages.home.hero_subtitle')))

@section('content')
<!-- Hero Section -->
@include('partials.hero')

<!-- Formulaire de recherche -->
@include('partials.search-blade')

<!-- Chambres & Suites -->
<section class="py-16 px-4 max-w-7xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-6">
        {{ trans('messages.home.residences_suites_title') }}
    </h2>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($chambres ?? [] as $chambre)
            @include('partials.room-card', ['room' => $chambre])
        @endforeach
    </div>
    
    <div class="text-center mt-12">
        <a href="{{ route('residences') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200">
            {{ trans('messages.home.view_all_residences') }}
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Section Caractéristiques -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">
            {{ trans('messages.home.features_title') }}
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Confort -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bed text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">{{ trans('messages.home.feature_comfort') }}</h3>
                <p class="text-gray-600">{{ trans('messages.home.feature_comfort_desc') }}</p>
            </div>
            
            <!-- Sécurité -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">{{ trans('messages.home.feature_security') }}</h3>
                <p class="text-gray-600">{{ trans('messages.home.feature_security_desc') }}</p>
            </div>
            
            <!-- Flexibilité -->
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">{{ trans('messages.home.feature_flexibility') }}</h3>
                <p class="text-gray-600">{{ trans('messages.home.feature_flexibility_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Avis clients -->
@include('partials.testimonials')
@endsection