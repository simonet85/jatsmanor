<!-- Informations de paiement -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <h3 class="font-semibold mb-4 flex items-center gap-2">
        <i class="fas fa-credit-card text-blue-700"></i> Informations de paiement
    </h3>
    <div class="mb-4 flex gap-6">
        <label class="flex items-center gap-2">
            <input type="radio" name="payment_method" value="card" checked class="accent-blue-700" />
            Carte bancaire
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" name="payment_method" value="paypal" class="accent-blue-700" />
            PayPal
        </label>
    </div>
    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nom sur la carte *</label>
            <input type="text" name="card_name" class="border p-2 rounded w-full" value="{{ old('card_name') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Numéro de carte *</label>
            <input type="text" name="card_number" class="border p-2 rounded w-full" placeholder="1234 5678 9012 3456" value="{{ old('card_number') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date d'expiration *</label>
            <input type="text" name="card_expiry" class="border p-2 rounded w-full" placeholder="MM/AA" value="{{ old('card_expiry') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">CVV *</label>
            <input type="text" name="card_cvv" class="border p-2 rounded w-full" placeholder="123" value="{{ old('card_cvv') }}" required />
        </div>
    </div>
    <div class="flex items-center gap-2 text-green-600 text-sm mb-4">
        <i class="fas fa-lock"></i> Paiement sécurisé SSL 256-bit
    </div>
    <div class="mb-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="newsletter" class="accent-blue-700" />
            Je souhaite recevoir les offres et actualités par email
        </label>
        <label class="flex items-center gap-2 mt-2">
            <input type="checkbox" name="terms" class="accent-blue-700" required />
            J'accepte les <a href="{{ route('terms') }}" class="text-blue-700 underline">conditions générales</a> et la <a href="{{ route('privacy') }}" class="text-blue-700 underline">politique de confidentialité</a> *
        </label>
    </div>
    <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded px-4 py-3 mt-2 text-lg">
        Confirmer la réservation
    </button>
</div>
