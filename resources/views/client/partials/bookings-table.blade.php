<tbody class="bg-white divide-y divide-gray-200" id="client-bookings-table-body">
    @forelse($bookings as $booking)
    @php
        $rowClass = '';
        if ($booking->status === 'confirmed') {
            $rowClass = 'bg-green-50';
        } elseif ($booking->status === 'cancelled') {
            $rowClass = 'bg-red-50';
        } elseif ($booking->status === 'completed') {
            $rowClass = 'bg-blue-50';
        } elseif ($booking->status === 'pending') {
            $rowClass = 'bg-yellow-50';
        }
    @endphp
    <tr class="booking-row {{ $rowClass }} hover:bg-gray-100 transition-colors duration-200">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">#{{ $booking->id }}</div>
            <div class="text-xs text-gray-500 mt-1">
                {{ $booking->booking_reference }}
            </div>
            <div class="text-xs text-gray-400 mt-1">
                Créée le {{ format_local_date($booking->created_at) }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $booking->residence->name }}</div>
            <div class="text-sm text-gray-500">{{ $booking->residence->location }}</div>
            @if($booking->residence->description)
                <div class="text-xs text-gray-400 mt-1 truncate max-w-xs">{{ Str::limit($booking->residence->description, 50) }}</div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                <div class="flex items-center">
                    <i class="fas fa-calendar text-green-500 mr-1"></i>
                    {{ format_local_date($booking->check_in) }}
                </div>
                <div class="flex items-center mt-1">
                    <i class="fas fa-calendar text-red-500 mr-1"></i>
                    {{ format_local_date($booking->check_out) }}
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-1">
                {{ $booking->total_nights }} nuit{{ $booking->total_nights > 1 ? 's' : '' }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <div class="flex items-center">
                <i class="fas fa-users text-blue-500 mr-2"></i>
                <div>
                    <div class="font-medium">{{ $booking->guests }} {{ $booking->guests > 1 ? 'invités' : 'invité' }}</div>
                    <div class="text-xs text-gray-500">Max: {{ $booking->residence->max_guests }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            @php
                $displayAmount = $booking->total_amount > 0 
                    ? $booking->total_amount 
                    : ($booking->residence->price_per_night * $booking->total_nights);
                
                $paymentStatusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'paid' => 'bg-green-100 text-green-800',
                    'failed' => 'bg-red-100 text-red-800',
                    'refunded' => 'bg-gray-100 text-gray-800'
                ];
                
                $paymentStatusLabels = [
                    'pending' => 'En attente',
                    'paid' => 'Payé',
                    'failed' => 'Échec',
                    'refunded' => 'Remboursé'
                ];
            @endphp
            <div class="font-semibold text-gray-900">
                {{ format_fcfa($displayAmount) }}
            </div>
            @if($booking->total_amount == 0)
                <div class="text-xs text-gray-500">
                    ({{ format_fcfa($booking->residence->price_per_night) }} × {{ $booking->total_nights }})
                </div>
            @endif
            <div class="mt-1">
                <span class="px-2 py-1 text-xs leading-4 font-medium rounded-full {{ $paymentStatusColors[$booking->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}
                </span>
            </div>
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
                $statusIcons = [
                    'pending' => 'fas fa-clock',
                    'confirmed' => 'fas fa-check-circle',
                    'cancelled' => 'fas fa-times-circle',
                    'completed' => 'fas fa-flag-checkered'
                ];
            @endphp
            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                <i class="{{ $statusIcons[$booking->status] ?? 'fas fa-question' }}"></i>
                {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center gap-2">
                <button onclick="viewBooking({{ $booking->id }})" 
                        class="text-blue-600 hover:text-blue-900 transition-colors"
                        title="Voir les détails">
                    <i class="fas fa-eye text-lg"></i>
                </button>
                
                @if($booking->status !== 'cancelled' && $booking->status !== 'completed' && $booking->check_in->isFuture())
                    <button onclick="cancelBooking({{ $booking->id }})" 
                            class="text-red-600 hover:text-red-900 transition-colors"
                            title="Annuler la réservation">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                @elseif($booking->status === 'cancelled')
                    <span class="text-gray-400" title="Réservation annulée">
                        <i class="fas fa-ban text-lg"></i>
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="text-green-500" title="Séjour terminé">
                        <i class="fas fa-check-circle text-lg"></i>
                    </span>
                @else
                    <span class="text-gray-400" title="Impossible d'annuler">
                        <i class="fas fa-lock text-lg"></i>
                    </span>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
            <div class="text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune réservation trouvée</h3>
                <p class="text-sm text-gray-500">Vos réservations apparaîtront ici une fois effectuées.</p>
                <div class="mt-4">
                    <a href="{{ route('residences') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Découvrir nos résidences
                    </a>
                </div>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
