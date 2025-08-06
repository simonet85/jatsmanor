@extends('dashboard.layout')

@section('title', 'Messages de Contact')

@section('content')
<div class="flex-1 p-8">
    <!-- Header with Statistics -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Messages de Contact</h1>
            <p class="text-gray-600 mt-2">Gérez et répondez aux messages des clients</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Auto-refresh toggle -->
            <button id="autoRefreshToggle" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                <span id="refreshText">Auto-refresh OFF</span>
            </button>
            
            <!-- Manual refresh -->
            <button onclick="window.location.reload()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-refresh mr-2"></i>
                Actualiser
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Traités</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['processed'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Aujourd'hui</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['today'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Cette semaine</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['this_week'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters and Search -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <form method="GET" action="{{ route('admin.contact.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher par nom, email ou sujet..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="processed" {{ $status === 'processed' ? 'selected' : '' }}>Traités</option>
                <option value="replied" {{ $status === 'replied' ? 'selected' : '' }}>Répondus</option>
            </select>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            
            <a href="{{ route('admin.contact.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6" id="bulkActions" style="display: none;">
        <form method="POST" action="{{ route('admin.contact.bulk') }}" class="flex items-center gap-4">
            @csrf
            <span class="text-gray-600">Actions en lot:</span>
            
            <select name="action" required class="px-3 py-2 border border-gray-300 rounded">
                <option value="">Choisir une action</option>
                <option value="mark_processed">Marquer comme traité</option>
                <option value="mark_replied">Marquer comme répondu</option>
                <option value="delete">Supprimer</option>
            </select>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Exécuter
            </button>
            
            <span id="selectedCount" class="text-gray-600"></span>
        </form>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($messages as $message)
                        <tr class="hover:bg-gray-50 {{ $message->status === 'pending' && $message->created_at <= now()->subHours(24) ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected[]" value="{{ $message->id }}" class="message-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $message->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                    @if($message->phone)
                                        <div class="text-sm text-gray-500">{{ $message->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($message->subject, 50) }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($message->message, 100) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $message->created_at->format('d/m/Y') }}</div>
                                <div>{{ $message->created_at->format('H:i') }}</div>
                                @if($message->status === 'pending' && $message->created_at <= now()->subHours(24))
                                    <div class="text-red-600 text-xs font-semibold mt-1">
                                        <i class="fas fa-exclamation-triangle"></i> Urgent
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.contact.updateStatus', $message) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs px-2 py-1 rounded border-0 {{ 
                                        $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($message->status === 'processed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') 
                                    }}">
                                        <option value="pending" {{ $message->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="processed" {{ $message->status === 'processed' ? 'selected' : '' }}>Traité</option>
                                        <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Répondu</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors" title="Répondre">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <button onclick="showMessageDetails({{ $message->id }})" 
                                            class="text-gray-600 hover:text-gray-800 transition-colors" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-envelope text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Aucun message trouvé</h3>
                <p class="text-gray-500">{{ request('search') ? 'Essayez de modifier vos critères de recherche.' : 'Les nouveaux messages apparaîtront ici.' }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Message Details Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-96 overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Détails du message</h3>
                <button onclick="closeMessageModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="messageContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh functionality
let autoRefresh = false;
let refreshInterval;

document.getElementById('autoRefreshToggle').addEventListener('click', function() {
    autoRefresh = !autoRefresh;
    const text = document.getElementById('refreshText');
    
    if (autoRefresh) {
        text.textContent = 'Auto-refresh ON';
        this.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        this.classList.add('bg-green-600', 'hover:bg-green-700');
        
        refreshInterval = setInterval(() => {
            window.location.reload();
        }, 30000); // Refresh every 30 seconds
    } else {
        text.textContent = 'Auto-refresh OFF';
        this.classList.remove('bg-green-600', 'hover:bg-green-700');
        this.classList.add('bg-blue-600', 'hover:bg-blue-700');
        
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
});

// Bulk actions
const selectAllCheckbox = document.getElementById('selectAll');
const messageCheckboxes = document.querySelectorAll('.message-checkbox');
const bulkActions = document.getElementById('bulkActions');
const selectedCount = document.getElementById('selectedCount');

function updateBulkActions() {
    const checked = document.querySelectorAll('.message-checkbox:checked');
    
    if (checked.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = `${checked.length} message(s) sélectionné(s)`;
        
        // Update hidden inputs for bulk form
        const form = bulkActions.querySelector('form');
        // Remove existing hidden inputs
        form.querySelectorAll('input[name="selected[]"]').forEach(input => input.remove());
        
        // Add new hidden inputs
        checked.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selected[]';
            hiddenInput.value = checkbox.value;
            form.appendChild(hiddenInput);
        });
    } else {
        bulkActions.style.display = 'none';
    }
}

selectAllCheckbox.addEventListener('change', function() {
    messageCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

messageCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

// Message details modal
function showMessageDetails(messageId) {
    // You can implement AJAX call here to load message details
    // For now, we'll use a simple implementation
    document.getElementById('messageModal').classList.remove('hidden');
    document.getElementById('messageModal').classList.add('flex');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    document.getElementById('messageModal').classList.remove('flex');
}

// Close modal on outside click
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMessageModal();
    }
});
</script>
@endpush
@endsection
