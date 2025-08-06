<!-- Informations client -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <h3 class="font-semibold mb-4 flex items-center gap-2">
        <i class="fas fa-user text-blue-700"></i> Vos informations
    </h3>
    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium mb-1">Prénom *</label>
            <input type="text" name="first_name" class="border p-2 rounded w-full" value="{{ old('first_name') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nom *</label>
            <input type="text" name="last_name" class="border p-2 rounded w-full" value="{{ old('last_name') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email *</label>
            <input type="email" name="email" class="border p-2 rounded w-full" value="{{ old('email') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Téléphone *</label>
            <input type="tel" name="phone" class="border p-2 rounded w-full" value="{{ old('phone') }}" required />
        </div>
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Entreprise (optionnel)</label>
        <input type="text" name="company" class="border p-2 rounded w-full" value="{{ old('company') }}" />
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Demandes spéciales</label>
        <textarea
            name="special_requests"
            class="border p-2 rounded w-full"
            rows="2"
            placeholder="Allergie alimentaire, lit supplémentaire, arrivée tardive..."
        >{{ old('special_requests') }}</textarea>
    </div>
</div>
