@extends('layouts.app')

@section('title', trans('messages.errors.403.title') . ' - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 bg-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-lock text-white text-5xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <div class="space-y-4">
            <h1 class="text-9xl font-bold text-yellow-600">403</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                {{ trans('messages.errors.403.heading') }}
            </h2>
            <p class="text-lg text-gray-600 max-w-sm mx-auto">
                {{ trans('messages.errors.403.message') }}
            </p>
        </div>

        <!-- Information Box -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-shield-alt text-yellow-500 mr-2"></i>
                {{ trans('messages.errors.403.why_blocked') }}
            </h3>
            
            <div class="text-left space-y-3 text-gray-600">
                <p class="flex items-start">
                    <i class="fas fa-user-slash text-yellow-400 text-sm mt-1 mr-3"></i>
                    {{ trans('messages.errors.403.reason_1') }}
                </p>
                <p class="flex items-start">
                    <i class="fas fa-key text-yellow-400 text-sm mt-1 mr-3"></i>
                    {{ trans('messages.errors.403.reason_2') }}
                </p>
                <p class="flex items-start">
                    <i class="fas fa-ban text-yellow-400 text-sm mt-1 mr-3"></i>
                    {{ trans('messages.errors.403.reason_3') }}
                </p>
            </div>

            @guest
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ trans('messages.errors.403.guest_tip') }}
                </p>
            </div>
            @endguest
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ trans('messages.errors.403.login') }}
            </a>
            @endguest

            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ trans('messages.errors.403.go_back') }}
            </button>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>
                {{ trans('messages.errors.403.go_home') }}
            </a>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-500">
            {{ trans('messages.errors.403.help_text') }}
            <a href="{{ route('contact') }}" class="text-yellow-600 hover:text-yellow-800 underline">
                {{ trans('messages.errors.403.contact_admin') }}
            </a>
        </div>
    </div>
</div>

<!-- Security-themed Background Elements -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-20 left-10 opacity-20">
        <i class="fas fa-shield-alt text-yellow-300 text-2xl animate-pulse"></i>
    </div>
    <div class="absolute top-40 right-20 opacity-20">
        <i class="fas fa-lock text-yellow-400 text-3xl animate-pulse" style="animation-delay: 1s;"></i>
    </div>
    <div class="absolute bottom-40 left-20 opacity-20">
        <i class="fas fa-key text-yellow-300 text-xl animate-pulse" style="animation-delay: 2s;"></i>
    </div>
    <div class="absolute bottom-20 right-10 opacity-20">
        <i class="fas fa-user-shield text-yellow-400 text-2xl animate-pulse" style="animation-delay: 3s;"></i>
    </div>
</div>

@endsection
