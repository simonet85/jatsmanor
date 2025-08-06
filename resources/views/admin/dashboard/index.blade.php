@extends('dashboard.layout')

@section('title', 'Administration - Tableau de bord')
@section('subtitle', 'Vue d\'ensemble administrative')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Administration</h1>
        <div class="text-sm text-gray-500">
            <i class="fas fa-clock mr-1"></i>
            Dernière mise à jour : {{ now()->format('H:i') }}
        </div>
    </div>

    <!-- Contact Messages Notifications Widget -->
    <div class="mb-8">
        @include('partials.contact-notifications-widget')
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mx-auto mb-4">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_residences'] }}</div>
            <div class="text-gray-600 text-sm font-medium">Résidences</div>
            <div class="text-xs text-green-500 mt-1">
                <i class="fas fa-arrow-up"></i> +2.5%
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mx-auto mb-4">
                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['total_bookings'] }}</div>
            <div class="text-gray-600 text-sm font-medium">Réservations</div>
            <div class="text-xs text-green-500 mt-1">
                <i class="fas fa-arrow-up"></i> +8.1%
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg mx-auto mb-4">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $stats['active_bookings'] }}</div>
            <div class="text-gray-600 text-sm font-medium">Actives</div>
            <div class="text-xs text-red-500 mt-1">
                <i class="fas fa-arrow-down"></i> -1.2%
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mx-auto mb-4">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['total_users'] }}</div>
            <div class="text-gray-600 text-sm font-medium">Utilisateurs</div>
            <div class="text-xs text-green-500 mt-1">
                <i class="fas fa-arrow-up"></i> +5.3%
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a href="{{ route('admin.residences.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all hover:border-blue-300 group">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-blue-600">Résidences</h3>
                    <p class="text-sm text-gray-600">Gérer les propriétés</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all hover:border-green-300 group">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-full group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-green-600">Réservations</h3>
                    <p class="text-sm text-gray-600">Suivre les bookings</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.contact.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all hover:border-yellow-300 group">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-full group-hover:bg-yellow-200 transition-colors">
                    <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-yellow-600">Messages</h3>
                    <p class="text-sm text-gray-600">Contact clients</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all hover:border-purple-300 group">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-full group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-cog text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-purple-600">Paramètres</h3>
                    <p class="text-sm text-gray-600">Configuration</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Tableau Réservations récentes -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Réservations récentes</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir toutes →
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ $booking->residence->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->residence->location }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->check_in->format('d/m/Y') }} - {{ $booking->check_out->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($booking->total_amount, 0, ',', '.') }} FCFA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-blue-100 text-blue-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'confirmed' => 'Confirmée',
                                    'cancelled' => 'Annulée',
                                    'completed' => 'Terminée'
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                <p>Aucune réservation récente</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh des statistiques toutes les 5 minutes
setInterval(function() {
    fetch('/admin/quick-stats')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les statistiques en temps réel si nécessaire
            console.log('Stats updated:', data);
        })
        .catch(error => console.error('Error updating stats:', error));
}, 300000); // 5 minutes
</script>
@endpush
