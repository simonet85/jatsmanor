@extends('layouts.app')

@section('title', trans('messages.errors.404.title') . ' - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-home text-white text-5xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <div class="space-y-4">
            <h1 class="text-9xl font-bold text-blue-600">404</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                {{ trans('messages.errors.404.heading') }}
            </h2>
            <p class="text-lg text-gray-600 max-w-sm mx-auto">
                {{ trans('messages.errors.404.message') }}
            </p>
        </div>

        <!-- Search Suggestions -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                {{ trans('messages.errors.404.suggestions.title') }}
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">
                <!-- Home Link -->
                <a href="{{ route('home') }}" 
                   class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                    <i class="fas fa-home text-blue-600 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.404.suggestions.home') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.404.suggestions.home_desc') }}</div>
                    </div>
                </a>

                <!-- Residences Link -->
                <a href="{{ route('residences') }}" 
                   class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                    <i class="fas fa-bed text-blue-600 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.404.suggestions.residences') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.404.suggestions.residences_desc') }}</div>
                    </div>
                </a>

                <!-- Gallery Link -->
                <a href="{{ route('galerie') }}" 
                   class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                    <i class="fas fa-images text-blue-600 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.404.suggestions.gallery') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.404.suggestions.gallery_desc') }}</div>
                    </div>
                </a>

                <!-- Contact Link -->
                <a href="{{ route('contact') }}" 
                   class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                    <i class="fas fa-envelope text-blue-600 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="font-medium text-gray-800">{{ trans('messages.errors.404.suggestions.contact') }}</div>
                        <div class="text-sm text-gray-500">{{ trans('messages.errors.404.suggestions.contact_desc') }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ trans('messages.errors.404.go_back') }}
            </button>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>
                {{ trans('messages.errors.404.go_home') }}
            </a>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-500">
            {{ trans('messages.errors.404.help_text') }}
            <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800 underline">
                {{ trans('messages.errors.404.contact_us') }}
            </a>
        </div>
    </div>
</div>

<!-- Floating Animation -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-20 left-10 w-4 h-4 bg-blue-200 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
    <div class="absolute top-40 right-20 w-6 h-6 bg-indigo-200 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-40 left-20 w-3 h-3 bg-blue-300 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-20 right-10 w-5 h-5 bg-indigo-300 rounded-full animate-bounce" style="animation-delay: 3s;"></div>
</div>

@endsection

@section('styles')
<style>
    /* Custom animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Stagger the floating animation */
    .animate-float:nth-child(2) { animation-delay: 0.5s; }
    .animate-float:nth-child(3) { animation-delay: 1s; }
    .animate-float:nth-child(4) { animation-delay: 1.5s; }
</style>
@endsection
