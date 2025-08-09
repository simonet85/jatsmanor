@extends('layouts.app')

@section('title', $title ?? 'Erreur - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br {{ $gradient ?? 'from-gray-50 to-gray-100' }} flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 {{ $iconBg ?? 'bg-gray-600' }} rounded-full flex items-center justify-center shadow-lg">
                <i class="{{ $icon ?? 'fas fa-exclamation-triangle' }} text-white text-5xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <div class="space-y-4">
            <h1 class="text-9xl font-bold {{ $numberColor ?? 'text-gray-600' }}">{{ $errorCode ?? '???' }}</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                {{ $heading ?? 'Une erreur est survenue' }}
            </h2>
            <p class="text-lg text-gray-600 max-w-sm mx-auto">
                {{ $message ?? 'Nous rencontrons un problème technique.' }}
            </p>
        </div>

        <!-- Content Section -->
        @if(isset($content))
            <div class="bg-white rounded-lg shadow-md p-6">
                {!! $content !!}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($showBackButton ?? true)
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringColor ?? 'focus:ring-gray-500' }} transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </button>
            @endif
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white {{ $buttonBg ?? 'bg-gray-600 hover:bg-gray-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringColor ?? 'focus:ring-gray-500' }} transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>
                Accueil
            </a>
        </div>

        <!-- Help Text -->
        @if(isset($helpText))
        <div class="text-sm text-gray-500">
            {!! $helpText !!}
        </div>
        @endif
    </div>
</div>

<!-- Background Animation -->
@if(isset($backgroundElements))
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    {!! $backgroundElements !!}
</div>
@endif

@endsection
