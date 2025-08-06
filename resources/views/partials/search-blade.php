<section class="max-w-4xl mx-auto {{ $marginTop ?? '-mt-12' }} bg-white rounded-lg shadow p-6 relative z-20">
    <form action="{{ route('residences.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input 
            type="date" 
            name="arrival_date" 
            class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
            value="{{ request('arrival_date', date('Y-m-d')) }}"
            required 
        />
        <input 
            type="date" 
            name="departure_date" 
            class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
            value="{{ request('departure_date', date('Y-m-d', strtotime('+1 day'))) }}"
            required 
        />
        <select name="guests" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 {{ trans('messages.home.guests') }}</option>
            <option value="2" {{ request('guests') == '2' || !request('guests') ? 'selected' : '' }}>2 {{ trans('messages.home.guests') }}</option>
            <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3+ {{ trans('messages.home.guests') }}</option>
        </select>
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded px-4 py-2 w-full transition-colors duration-200">
            {{ trans('messages.home.search_button') }}
        </button>
    </form>
</section>
