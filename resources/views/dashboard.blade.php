@extends('layouts.dashboard')

@section('title', 'Tableau de Bord')
@section('subtitle', 'Vue d\'ensemble de votre plateforme')

@section('content')
<div class="p-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl p-6">
            <h2 class="text-2xl font-bold mb-2">
                Bienvenue, {{ auth()->user()->name }} ! 👋
            </h2>
            <p class="text-blue-100">
                Voici un aperçu de l'activité récente de votre plateforme.
            </p>
        </div>
    </div>

    <!-- Contact Messages Notifications Widget -->
    <div class="mb-8">
        @include('partials.contact-notifications-widget')
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('dashboard.residences') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Résidences</h3>
                    <p class="text-sm text-gray-600">Gérer les propriétés</p>
                </div>
            </div>
        </a>

        <a href="{{ route('dashboard.reservations') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Réservations</h3>
                    <p class="text-sm text-gray-600">Suivre les bookings</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.contact.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-full relative">
                    <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                    @if(isset($notifications) && $notifications['contact_messages']['pending'] > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $notifications['contact_messages']['pending'] }}
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Messages</h3>
                    <p class="text-sm text-gray-600">Contact clients</p>
                </div>
            </div>
        </a>

        <a href="{{ route('dashboard.utilisateurs') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Utilisateurs</h3>
                    <p class="text-sm text-gray-600">Gestion des comptes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i>
            Activité Récente
        </h3>
        
        <div class="space-y-4">
            @if(isset($notifications))
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Messages de Contact</p>
                        <p class="text-xs text-gray-600">
                            {{ $notifications['contact_messages']['today'] }} reçu(s) aujourd'hui, 
                            {{ $notifications['contact_messages']['pending'] }} en attente
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $notifications['last_updated'] }}
                    </div>
                </div>
            @endif
            
            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-gray-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Système de Notifications</p>
                    <p class="text-xs text-gray-600">
                        Notifications en temps réel activées • Auto-refresh toutes les 2 minutes
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
