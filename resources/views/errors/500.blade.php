@extends('layouts.app')

@section('title', trans('messages.errors.500.title') . ' - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo/Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-exclamation-triangle text-white text-5xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <div class="space-y-4">
            <h1 class="text-9xl font-bold text-red-600">500</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                {{ trans('messages.errors.500.heading') }}
            </h2>
            <p class="text-lg text-gray-600 max-w-sm mx-auto">
                {{ trans('messages.errors.500.message') }}
            </p>
        </div>

        <!-- Information Box -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                {{ trans('messages.errors.500.what_happened') }}
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-3 text-left text-gray-600">
                <p class="flex items-start">
                    <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                    {{ trans('messages.errors.500.reason_1') }}
                </p>
                <p class="flex items-start">
                    <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                    {{ trans('messages.errors.500.reason_2') }}
                </p>
                <p class="flex items-start">
                    <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                    {{ trans('messages.errors.500.reason_3') }}
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-lightbulb mr-2"></i>
                    {{ trans('messages.errors.500.tip') }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                <i class="fas fa-redo mr-2"></i>
                {{ trans('messages.errors.500.retry') }}
            </button>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>
                {{ trans('messages.errors.500.go_home') }}
            </a>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-500">
            {{ trans('messages.errors.500.help_text') }}
            <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-800 underline">
                {{ trans('messages.errors.500.report_issue') }}
            </a>
        </div>
    </div>
</div>

<!-- Animated Background Elements -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="absolute top-20 left-10 w-4 h-4 bg-red-200 rounded-full animate-pulse" style="animation-delay: 0s;"></div>
    <div class="absolute top-40 right-20 w-6 h-6 bg-orange-200 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-40 left-20 w-3 h-3 bg-red-300 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-20 right-10 w-5 h-5 bg-orange-300 rounded-full animate-pulse" style="animation-delay: 3s;"></div>
</div>

@endsection
