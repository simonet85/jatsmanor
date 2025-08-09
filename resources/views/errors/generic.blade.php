@extends('layouts.app')

@section('title', 'Erreur ' . $exception->getStatusCode() . ' - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 bg-slate-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-exclamation-circle text-white text-5xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <div class="space-y-4">
            <h1 class="text-9xl font-bold text-slate-600">{{ $exception->getStatusCode() }}</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                {{ trans('messages.errors.generic.heading') }}
            </h2>
            <p class="text-lg text-gray-600 max-w-sm mx-auto">
                {{ $exception->getMessage() ?: trans('messages.errors.generic.message') }}
            </p>
        </div>

        <!-- Information Box -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-slate-500 mr-2"></i>
                {{ trans('messages.errors.generic.what_can_do') }}
            </h3>
            
            <div class="grid grid-cols-1 gap-3">
                <!-- Retry -->
                <div class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-slate-300 hover:bg-slate-50 transition-all duration-200">
                    <i class="fas fa-redo text-slate-600 mr-3"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.generic.retry_action') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.generic.retry_desc') }}</div>
                    </div>
                </div>

                <!-- Home -->
                <div class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-slate-300 hover:bg-slate-50 transition-all duration-200">
                    <i class="fas fa-home text-slate-600 mr-3"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.generic.home_action') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.generic.home_desc') }}</div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-slate-300 hover:bg-slate-50 transition-all duration-200">
                    <i class="fas fa-envelope text-slate-600 mr-3"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.generic.contact_action') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.generic.contact_desc') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors duration-200">
                <i class="fas fa-redo mr-2"></i>
                {{ trans('messages.errors.generic.retry') }}
            </button>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>
                {{ trans('messages.errors.generic.go_home') }}
            </a>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-500">
            {{ trans('messages.errors.generic.help_text') }}
            <a href="{{ route('contact') }}" class="text-slate-600 hover:text-slate-800 underline">
                {{ trans('messages.errors.generic.contact_support') }}
            </a>
        </div>

        <!-- Technical Details (Development Only) -->
        @if(config('app.debug'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-left">
            <h4 class="text-sm font-semibold text-red-800 mb-2">
                <i class="fas fa-bug mr-1"></i>
                Détails techniques (mode debug)
            </h4>
            <div class="text-xs text-red-700 space-y-1">
                <p><strong>Code:</strong> {{ $exception->getStatusCode() }}</p>
                <p><strong>Message:</strong> {{ $exception->getMessage() }}</p>
                <p><strong>Fichier:</strong> {{ $exception->getFile() ?? 'N/A' }}</p>
                <p><strong>Ligne:</strong> {{ $exception->getLine() ?? 'N/A' }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Background Animation -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-20 left-10 w-4 h-4 bg-slate-200 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
    <div class="absolute top-40 right-20 w-6 h-6 bg-gray-200 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-40 left-20 w-3 h-3 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-20 right-10 w-5 h-5 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 3s;"></div>
</div>

@endsection
