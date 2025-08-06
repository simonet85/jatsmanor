@extends('dashboard.layout')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue d\'ensemble administrative')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold mb-10 text-center md:text-left">Tableau de bord</h1>

    <!-- Contact Messages Notifications Widget -->
    <div class="mb-8">
        @include('partials.contact-notifications-widget')
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-10">
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_residences'] }}</div>
        <div class="text-gray-600 text-sm">Résidences</div>
        <div class="text-xs text-green-500 mt-1">
            <i class="fas fa-arrow-up"></i> +2.5%
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['total_bookings'] }}</div>
        <div class="text-gray-600 text-sm">Réservations</div>
        <div class="text-xs text-green-500 mt-1">
            <i class="fas fa-arrow-up"></i> +8.1%
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $stats['active_bookings'] }}</div>
        <div class="text-gray-600 text-sm">Actives</div>
        <div class="text-xs text-red-500 mt-1">
            <i class="fas fa-arrow-down"></i> -1.2%
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['total_users'] }}</div>
        <div class="text-gray-600 text-sm">Utilisateurs</div>
        <div class="text-xs text-green-500 mt-1">
            <i class="fas fa-arrow-up"></i> +5.3%
        </div>
    </div>
</div>

<!-- Tableau Réservations récentes -->
<div class="bg-white shadow rounded-xl overflow-x-auto">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold">Réservations récentes</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
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
                <tr>
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
                        <div class="text-sm text-gray-900">{{ $booking->residence->name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->residence->location }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $booking->check_in->format('d/m/Y') }} - {{ $booking->check_out->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucune réservation récente
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($recentBookings->count() > 0)
    <div class="px-6 py-3 border-t border-gray-200">
        <a href="{{ route('dashboard.reservations') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Voir toutes les réservations →
        </a>
    </div>
    @endif
</div>
</div>
@endsection
