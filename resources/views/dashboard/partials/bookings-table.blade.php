<tbody class="bg-white divide-y divide-gray-200" id="bookings-table-body">
    @forelse($bookings as $booking)
    @php
        $rowClass = '';
        if ($booking->trashed()) {
            $rowClass = 'bg-red-50';
        } elseif ($booking->status === 'confirmed') {
            $rowClass = 'bg-green-50';
        } elseif ($booking->status === 'cancelled') {
            $rowClass = 'bg-red-50';
        } elseif ($booking->status === 'completed') {
            $rowClass = 'bg-blue-50';
        }
    @endphp
    <tr data-booking-id="{{ $booking->id }}" class="booking-row {{ $rowClass }} hover:bg-gray-100 transition-colors duration-200">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            #{{ $booking->id }}
            @if($booking->trashed())
                <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Supprimée</span>
            @endif
            <div class="text-xs text-gray-500 mt-1">
                {{ $booking->booking_reference }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                        {{ substr($booking->user->name, 0, 2) }}
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                    @if($booking->phone || $booking->user->phone)
                        <div class="text-xs text-gray-400">
                            <i class="fas fa-phone mr-1"></i>
                            {{ $booking->phone ?: $booking->user->phone }}
                        </div>
                    @endif
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $booking->residence->name }}</div>
            <div class="text-sm text-gray-500">{{ $booking->residence->location }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                <i class="fas fa-calendar text-green-500 mr-1"></i>
                {{ format_local_datetime($booking->check_in) }}
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar text-red-500 mr-1"></i>
                {{ format_local_datetime($booking->check_out) }}
            </div>
            <div class="text-xs text-gray-400 mt-1">
                {{ $booking->total_nights }} nuit{{ $booking->total_nights > 1 ? 's' : '' }}
                • Créée le {{ format_local_date($booking->created_at) }}
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
                    ({{ format_fcfa($booking->residence->price_per_night) }} × {{ $booking->total_nights }} nuit{{ $booking->total_nights > 1 ? 's' : '' }})
                </div>
            @endif
            <div class="mt-1">
                <span class="px-2 py-1 text-xs leading-4 font-medium rounded-full {{ $paymentStatusColors[$booking->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}
                </span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if(!$booking->trashed())
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
                <button class="status-dropdown-btn flex items-center gap-1 px-3 py-1 text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}" 
                        data-id="{{ $booking->id }}" data-current-status="{{ $booking->status }}">
                    <i class="{{ $statusIcons[$booking->status] ?? 'fas fa-question' }}"></i>
                    {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                    <i class="fas fa-chevron-down ml-1"></i>
                </button>
            @else
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-trash mr-1"></i>
                    Supprimée
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center gap-2">
                <button class="text-blue-600 hover:text-blue-900 btn-view-booking" 
                        data-id="{{ $booking->id }}" 
                        title="Voir les détails">
                    <i class="fas fa-eye text-lg"></i>
                </button>
                
                @if($booking->trashed())
                    <button class="text-green-600 hover:text-green-900 btn-restore-booking" 
                            data-id="{{ $booking->id }}" 
                            title="Restaurer">
                        <i class="fas fa-undo text-lg"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900 btn-force-delete-booking" 
                            data-id="{{ $booking->id }}" 
                            title="Supprimer définitivement">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                @else
                    <button class="text-red-600 hover:text-red-900 btn-delete-booking" 
                            data-id="{{ $booking->id }}" 
                            title="Supprimer">
                        <i class="fas fa-trash text-lg"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
            <div class="text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune réservation trouvée</h3>
                <p class="text-sm text-gray-500">Les réservations apparaîtront ici une fois créées.</p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
