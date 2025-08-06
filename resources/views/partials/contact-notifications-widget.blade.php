@if(isset($notifications))
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-envelope text-blue-600"></i>
            Messages de Contact
        </h3>
        <a href="{{ route('admin.contact.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Voir tout →
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="text-center p-3 bg-yellow-50 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $notifications['contact_messages']['pending'] }}</div>
            <div class="text-xs text-yellow-800 font-medium">En attente</div>
        </div>
        
        <div class="text-center p-3 bg-blue-50 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $notifications['contact_messages']['today'] }}</div>
            <div class="text-xs text-blue-800 font-medium">Aujourd'hui</div>
        </div>
        
        <div class="text-center p-3 bg-purple-50 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $notifications['contact_messages']['this_week'] }}</div>
            <div class="text-xs text-purple-800 font-medium">Cette semaine</div>
        </div>
        
        @if($notifications['contact_messages']['urgent'] > 0)
        <div class="text-center p-3 bg-red-50 rounded-lg border-2 border-red-200">
            <div class="text-2xl font-bold text-red-600 animate-pulse">{{ $notifications['contact_messages']['urgent'] }}</div>
            <div class="text-xs text-red-800 font-medium">🚨 Urgents</div>
        </div>
        @else
        <div class="text-center p-3 bg-green-50 rounded-lg">
            <div class="text-2xl font-bold text-green-600">✓</div>
            <div class="text-xs text-green-800 font-medium">Tout traité</div>
        </div>
        @endif
    </div>

    @if($notifications['contact_messages']['pending'] > 0)
    <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            <span class="text-sm text-yellow-800 font-medium">
                {{ $notifications['contact_messages']['pending'] }} message(s) en attente de traitement
            </span>
        </div>
    </div>
    @endif

    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>Dernière mise à jour: {{ $notifications['last_updated'] }}</span>
            <button onclick="window.location.reload()" class="hover:text-gray-700 transition-colors">
                <i class="fas fa-refresh"></i> Actualiser
            </button>
        </div>
    </div>
</div>
@endif
