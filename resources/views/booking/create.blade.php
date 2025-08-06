@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500">
    <a href="{{ route('residence.details', $residence) }}" class="hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Retour aux détails
    </a>
</div>

<!-- Main content -->
<main class="max-w-7xl mx-auto px-4 py-6 grid md:grid-cols-3 gap-8">
    <!-- Reservation form -->
    <div class="md:col-span-2 space-y-6">
        <h2 class="text-2xl font-bold mb-2">Finaliser votre réservation</h2>
        
        <form method="POST" action="{{ route('booking.store', $residence) }}" id="booking-form">
            @csrf
            
            <!-- Dates et invités -->
            <div class="bg-white rounded-lg shadow p-6 mb-4">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-700"></i> Dates et invités
                </h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Date d'arrivée</label>
                        <input type="date" name="check_in_date" 
                               value="{{ old('check_in_date', request('checkin')) }}"
                               class="border p-2 rounded w-full @error('check_in_date') border-red-500 @enderror" 
                               required />
                        @error('check_in_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date de départ</label>
                        <input type="date" name="check_out_date" 
                               value="{{ old('check_out_date', request('checkout')) }}"
                               class="border p-2 rounded w-full @error('check_out_date') border-red-500 @enderror" 
                               required />
                        @error('check_out_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre d'invités</label>
                        <select name="guests_count" class="border p-2 rounded w-full @error('guests_count') border-red-500 @enderror" required>
                            <option value="1" {{ old('guests_count', request('guests')) == '1' ? 'selected' : '' }}>1 personne</option>
                            <option value="2" {{ old('guests_count', request('guests')) == '2' || !old('guests_count') ? 'selected' : '' }}>2 personnes</option>
                            <option value="3" {{ old('guests_count', request('guests')) == '3' ? 'selected' : '' }}>3 personnes</option>
                            <option value="4" {{ old('guests_count', request('guests')) == '4' ? 'selected' : '' }}>4 personnes</option>
                        </select>
                        @error('guests_count')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="bg-white rounded-lg shadow p-6 mb-4">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-700"></i> Vos informations
                </h3>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Prénom *</label>
                        <input type="text" name="first_name" 
                               value="{{ old('first_name', Auth::user()->first_name ?? explode(' ', Auth::user()->name)[0] ?? '') }}"
                               class="border p-2 rounded w-full @error('first_name') border-red-500 @enderror" required />
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" name="last_name" 
                               value="{{ old('last_name', Auth::user()->last_name ?? (count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name, 2)[1] : '')) }}"
                               class="border p-2 rounded w-full @error('last_name') border-red-500 @enderror" required />
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" name="email" 
                               value="{{ old('email', Auth::user()->email) }}"
                               class="border p-2 rounded w-full @error('email') border-red-500 @enderror" required />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone *</label>
                        <input type="tel" name="phone" 
                               value="{{ old('phone', Auth::user()->phone ?? '') }}"
                               class="border p-2 rounded w-full @error('phone') border-red-500 @enderror" required />
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Entreprise (optionnel)</label>
                    <input type="text" name="company" 
                           value="{{ old('company') }}"
                           class="border p-2 rounded w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Demandes spéciales</label>
                    <textarea name="special_requests" 
                              class="border p-2 rounded w-full" 
                              rows="2" 
                              placeholder="Allergie alimentaire, lit supplémentaire, arrivée tardive...">{{ old('special_requests') }}</textarea>
                </div>
            </div>

            <!-- Options et conditions -->
            <div class="bg-white rounded-lg shadow p-6 mb-4">
                <div class="flex items-center gap-2 text-green-600 text-sm mb-4">
                    <i class="fas fa-lock"></i> Informations sécurisées SSL 256-bit
                </div>
                <div class="mb-4 space-y-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="newsletter_subscription" 
                               {{ old('newsletter_subscription') ? 'checked' : '' }}
                               class="accent-blue-700" />
                        Je souhaite recevoir les offres et actualités par email
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="terms_accepted" 
                               {{ old('terms_accepted') ? 'checked' : '' }}
                               class="accent-blue-700" required />
                        J'accepte les <a href="{{ route('terms') }}" class="text-blue-600 hover:underline" target="_blank">conditions générales</a> et la <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline" target="_blank">politique de confidentialité</a> *
                    </label>
                    @error('terms_accepted')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" 
                        class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded px-4 py-3 mt-2 text-lg"
                        id="submit-booking">
                    Confirmer la réservation
                </button>
            </div>
        </form>
    </div>

    <!-- Résumé -->
    <aside>
        <div class="bg-white rounded-lg shadow p-6 mb-6" id="booking-summary">
            <h3 class="font-semibold mb-4">Résumé de la réservation</h3>
            <div class="flex gap-4 items-center mb-4">
                @if($residence->image)
                    <img src="{{ asset($residence->image) }}?v={{ time() }}" 
                         alt="{{ $residence->name }}" 
                         class="w-24 h-16 object-cover rounded"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <div class="w-24 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded flex items-center justify-center" style="display: none;">
                        <i class="fas fa-home text-blue-400"></i>
                    </div>
                @elseif($residence->images->first())
                    <img src="{{ asset('storage/' . $residence->images->first()->image_path) }}?v={{ time() }}" 
                         alt="{{ $residence->name }}" 
                         class="w-24 h-16 object-cover rounded"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <div class="w-24 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded flex items-center justify-center" style="display: none;">
                        <i class="fas fa-home text-blue-400"></i>
                    </div>
                @else
                    <div class="w-24 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded flex items-center justify-center">
                        <i class="fas fa-home text-blue-400"></i>
                    </div>
                @endif
                <div>
                    <div class="font-bold">
                        {{ $residence->name }}
                        @if($residence->reviews->count() > 0)
                            <span class="text-yellow-500">
                                <i class="fas fa-star"></i> {{ number_format($residence->reviews->avg('rating'), 1) }}
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500">{{ $residence->location }}</div>
                    <div class="text-xs text-gray-500">
                        Jusqu'à {{ $residence->max_guests }} personnes • {{ $residence->surface }}m²
                    </div>
                </div>
            </div>

            <!-- Équipements -->
            @if($residence->amenities->count() > 0)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($residence->amenities->take(3) as $amenity)
                <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                    <i class="{{ $amenity->icon ?? 'fas fa-check' }}"></i> {{ $amenity->name }}
                </span>
                @endforeach
            </div>
            @endif

            <!-- Calcul du prix -->
            <div class="border-t pt-4 mt-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Prix par nuit</span>
                        <span id="price-per-night">{{ number_format($residence->price_per_night, 0, ',', '.') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nombre de nuits</span>
                        <span id="total-nights">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Frais de nettoyage</span>
                        <span>15.000 FCFA</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total</span>
                        <span id="total-amount">-</span>
                    </div>
                </div>
            </div>

            <ul class="text-xs text-green-700 space-y-1 mt-4">
                <li><i class="fas fa-check"></i> Annulation gratuite jusqu'à 48h avant</li>
                <li><i class="fas fa-check"></i> Confirmation immédiate</li>
                <li><i class="fas fa-check"></i> Support client 24h/24</li>
            </ul>
        </div>
    </aside>
</main>

@push('scripts')
<script>
const pricePerNight = {{ $residence->price_per_night }};
const cleaningFee = 15000;

function calculateTotal() {
    const checkinDate = document.querySelector('input[name="check_in_date"]').value;
    const checkoutDate = document.querySelector('input[name="check_out_date"]').value;
    
    if (checkinDate && checkoutDate) {
        const checkin = new Date(checkinDate);
        const checkout = new Date(checkoutDate);
        const timeDiff = checkout.getTime() - checkin.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff > 0) {
            const subtotal = daysDiff * pricePerNight;
            const total = subtotal + cleaningFee;
            
            document.getElementById('total-nights').textContent = daysDiff + (daysDiff > 1 ? ' nuits' : ' nuit');
            document.getElementById('total-amount').textContent = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
        } else {
            document.getElementById('total-nights').textContent = '-';
            document.getElementById('total-amount').textContent = '-';
        }
    }
}

// Calculer le total à chaque changement de date
document.querySelector('input[name="check_in_date"]').addEventListener('change', calculateTotal);
document.querySelector('input[name="check_out_date"]').addEventListener('change', calculateTotal);

// Calculer au chargement de la page
document.addEventListener('DOMContentLoaded', calculateTotal);

// Validation des dates
document.querySelector('input[name="check_in_date"]').addEventListener('change', function() {
    const checkinDate = this.value;
    const checkoutInput = document.querySelector('input[name="check_out_date"]');
    
    // Définir la date minimum pour le checkout (lendemain du checkin)
    if (checkinDate) {
        const minCheckout = new Date(checkinDate);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutInput.min = minCheckout.toISOString().split('T')[0];
        
        // Si la date de checkout est antérieure, la réinitialiser
        if (checkoutInput.value && new Date(checkoutInput.value) <= new Date(checkinDate)) {
            checkoutInput.value = minCheckout.toISOString().split('T')[0];
        }
    }
});

// Définir la date minimum (aujourd'hui)
const today = new Date().toISOString().split('T')[0];
document.querySelector('input[name="check_in_date"]').min = today;
</script>
@endpush
@endsection
