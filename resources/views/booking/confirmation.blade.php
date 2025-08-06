@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header de confirmation -->
    <div class="text-center mb-8">
        <div class="bg-green-100 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-green-600 mb-2">Réservation confirmée !</h1>
        <p class="text-gray-600">Votre réservation a été enregistrée et est en attente de confirmation.</p>
    </div>

    <!-- Détails de la réservation -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Détails de votre réservation</h2>
            <div class="flex flex-col items-end gap-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $booking->booking_reference }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                    @switch($booking->status)
                        @case('confirmed')
                            <i class="fas fa-check-circle mr-1"></i>Confirmée
                            @break
                        @case('pending')
                            <i class="fas fa-clock mr-1"></i>En attente
                            @break
                        @case('cancelled')
                            <i class="fas fa-times-circle mr-1"></i>Annulée
                            @break
                        @case('completed')
                            <i class="fas fa-flag-checkered mr-1"></i>Terminée
                            @break
                        @default
                            <i class="fas fa-info-circle mr-1"></i>{{ ucfirst($booking->status) }}
                    @endswitch
                </span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Informations de la résidence -->
            <div>
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-home text-blue-600"></i>
                    Résidence
                </h3>
                
                <div class="flex gap-4 mb-4">
                    @if($booking->residence->image)
                        <img src="{{ asset($booking->residence->image) }}?v={{ time() }}" 
                             alt="{{ $booking->residence->name }}" 
                             class="w-24 h-20 object-cover rounded-lg"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                        <div class="w-24 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center" style="display: none;">
                            <i class="fas fa-home text-blue-400"></i>
                        </div>
                    @elseif($booking->residence->images->first())
                        <img src="{{ asset('storage/' . $booking->residence->images->first()->image_path) }}?v={{ time() }}" 
                             alt="{{ $booking->residence->name }}" 
                             class="w-24 h-20 object-cover rounded-lg"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                        <div class="w-24 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center" style="display: none;">
                            <i class="fas fa-home text-blue-400"></i>
                        </div>
                    @else
                        <div class="w-24 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-blue-400"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-lg">{{ $booking->residence->name }}</h4>
                        <p class="text-gray-600 text-sm">{{ $booking->residence->location }}</p>
                        <p class="text-gray-600 text-sm">{{ $booking->residence->surface }}m² • {{ $booking->residence->max_guests }} personnes max</p>
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div>
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i>
                    Vos informations
                </h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Nom :</span> {{ $booking->first_name }} {{ $booking->last_name }}</p>
                    <p><span class="font-medium">Email :</span> {{ $booking->email }}</p>
                    <p><span class="font-medium">Téléphone :</span> {{ $booking->phone }}</p>
                    @if($booking->company)
                        <p><span class="font-medium">Entreprise :</span> {{ $booking->company }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Détails du séjour -->
        <div class="border-t pt-6 mt-6">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-blue-600"></i>
                Détails du séjour
            </h3>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Date d'arrivée</p>
                    <p class="font-bold text-lg">{{ $booking->check_in->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->check_in->locale('fr')->isoFormat('dddd') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date de départ</p>
                    <p class="font-bold text-lg">{{ $booking->check_out->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->check_out->locale('fr')->isoFormat('dddd') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Invités</p>
                    <p class="font-bold text-lg">{{ $booking->guests }} {{ $booking->guests > 1 ? 'personnes' : 'personne' }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $booking->total_nights ?? $booking->check_in->diffInDays($booking->check_out) }} 
                        {{ ($booking->total_nights ?? $booking->check_in->diffInDays($booking->check_out)) > 1 ? 'nuits' : 'nuit' }}
                    </p>
                </div>
            </div>
        </div>

        @if($booking->special_requests)
        <!-- Demandes spéciales -->
        <div class="border-t pt-6 mt-6">
            <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                <i class="fas fa-comment text-blue-600"></i>
                Demandes spéciales
            </h3>
            <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->special_requests }}</p>
        </div>
        @endif

        <!-- Récapitulatif financier -->
        <div class="border-t pt-6 mt-6">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-600"></i>
                Récapitulatif financier
            </h3>
            
            @php
                $nights = $booking->total_nights ?? $booking->check_in->diffInDays($booking->check_out);
                $pricePerNight = $booking->price_per_night ?? $booking->residence->price_per_night;
                $subtotal = $nights * $pricePerNight;
                $cleaningFee = $booking->cleaning_fee ?? 0;
                $taxAmount = $booking->tax_amount ?? 0;
                $totalAmount = $booking->total_amount ?? ($subtotal + $cleaningFee + $taxAmount);
            @endphp

            <div class="bg-gray-50 p-4 rounded space-y-2">
                <div class="flex justify-between">
                    <span>{{ number_format($pricePerNight, 0, ',', '.') }} FCFA × {{ $nights }} {{ $nights > 1 ? 'nuits' : 'nuit' }}</span>
                    <span>{{ number_format($subtotal, 0, ',', '.') }} FCFA</span>
                </div>
                @if($cleaningFee > 0)
                <div class="flex justify-between">
                    <span>Frais de nettoyage</span>
                    <span>{{ number_format($cleaningFee, 0, ',', '.') }} FCFA</span>
                </div>
                @endif
                @if($taxAmount > 0)
                <div class="flex justify-between">
                    <span>Taxes</span>
                    <span>{{ number_format($taxAmount, 0, ',', '.') }} FCFA</span>
                </div>
                @endif
                <div class="border-t pt-2 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>{{ number_format($totalAmount, 0, ',', '.') }} FCFA</span>
                </div>
            </div>

            <!-- Statut de paiement -->
            <div class="mt-4 p-3 rounded-lg {{ $booking->payment_status === 'paid' ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                <div class="flex items-center gap-2">
                    @if($booking->payment_status === 'paid')
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-green-800 font-medium">Paiement confirmé</span>
                    @elseif($booking->payment_status === 'pending')
                        <i class="fas fa-clock text-yellow-600"></i>
                        <span class="text-yellow-800 font-medium">Paiement en attente</span>
                    @else
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        <span class="text-orange-800 font-medium">Paiement requis</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Prochaines étapes -->
    <div class="bg-blue-50 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-blue-600"></i>
            Prochaines étapes
        </h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">1</div>
                <p>Vous recevrez un email de confirmation dans les 5 minutes</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">2</div>
                <p>Notre équipe va valider votre réservation sous 24h</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">3</div>
                <p>Vous recevrez les détails d'accès 48h avant votre arrivée</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('booking.show', $booking) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold text-center">
            <i class="fas fa-eye mr-2"></i>
            Voir ma réservation
        </a>
        <a href="{{ route('residences') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold text-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux résidences
        </a>
        <a href="{{ route('booking.index') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold text-center">
            <i class="fas fa-list mr-2"></i>
            Mes réservations
        </a>
    </div>

    <!-- Contact d'urgence -->
    <div class="text-center mt-8 p-4 bg-yellow-50 rounded-lg">
        <p class="text-sm text-gray-700">
            <i class="fas fa-phone-alt text-yellow-600 mr-2"></i>
            <strong>Besoin d'aide ?</strong> Contactez-nous au 
            <a href="tel:+22507070707" class="text-blue-600 font-semibold">+225 07 07 07 07</a> 
            ou par email à 
            <a href="mailto:contact@jatsmanor.ci" class="text-blue-600 font-semibold">contact@jatsmanor.ci</a>
        </p>
    </div>
</div>
@endsection
