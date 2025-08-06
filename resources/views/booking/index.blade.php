@extends('layouts.app')

@section('title', 'Mes réservations - Jatsmanor')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mes réservations</h1>

    @if($bookings->count() > 0)
        <div class="space-y-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $booking->residence->name }}</h3>
                            <p class="text-gray-600">{{ $booking->residence->location }}</p>
                            <p class="text-sm text-gray-500">
                                Référence: <span class="font-mono">{{ $booking->booking_reference }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($booking->status)
                                    @case('confirmed') Confirmé @break
                                    @case('pending') En attente @break
                                    @case('cancelled') Annulé @break
                                    @case('completed') Terminé @break
                                    @default {{ ucfirst($booking->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Arrivée</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Départ</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Invités</p>
                            <p class="font-semibold">{{ $booking->guests }} {{ $booking->guests > 1 ? 'personnes' : 'personne' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="font-semibold text-lg">{{ number_format($booking->total_amount, 0, ',', '.') }} FCFA</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('booking.show', $booking) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            <i class="fas fa-eye mr-1"></i> Voir détails
                        </a>

                        @if($booking->status === 'pending' || $booking->status === 'confirmed')
                            @php
                                $checkinDate = \Carbon\Carbon::parse($booking->check_in);
                                $canCancel = $checkinDate->diffInHours(now()) >= 48;
                            @endphp
                            
                            @if($canCancel)
                                <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        <i class="fas fa-times mr-1"></i> Annuler
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Annulation impossible (moins de 48h)
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="bg-gray-100 rounded-lg p-8 max-w-md mx-auto">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Aucune réservation</h3>
                <p class="text-gray-500 text-sm mb-4">
                    Vous n'avez encore effectué aucune réservation.
                </p>
                <a href="{{ route('residences') }}" 
                   class="inline-block bg-blue-800 hover:bg-blue-900 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>
                    Découvrir nos résidences
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
