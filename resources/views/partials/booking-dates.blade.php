<!-- Dates et invités -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <h3 class="font-semibold mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-blue-700"></i> Dates et invités
    </h3>
    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Date d'arrivée</label>
            <input type="date" name="arrival_date" class="border p-2 rounded w-full" value="{{ old('arrival_date', $arrival_date ?? '') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date de départ</label>
            <input type="date" name="departure_date" class="border p-2 rounded w-full" value="{{ old('departure_date', $departure_date ?? '') }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nombre d'invités</label>
            <select name="guests" class="border p-2 rounded w-full">
                <option value="1" {{ old('guests', $guests ?? '') == '1' ? 'selected' : '' }}>1 personne</option>
                <option value="2" {{ old('guests', $guests ?? '2') == '2' ? 'selected' : '' }}>2 personnes</option>
                <option value="3" {{ old('guests', $guests ?? '') == '3' ? 'selected' : '' }}>3 personnes</option>
                <option value="4" {{ old('guests', $guests ?? '') == '4' ? 'selected' : '' }}>4 personnes</option>
            </select>
        </div>
    </div>
</div>
