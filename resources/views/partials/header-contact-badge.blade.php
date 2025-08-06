@can('view-contact-notifications')
    @if(isset($notifications))
    <a href="{{ route('admin.contact.index') }}" class="relative p-2 text-gray-600 hover:text-gray-800 transition-colors" title="Messages de contact">
    <i class="fas fa-envelope text-xl"></i>
    
    @if($notifications['contact_messages']['pending'] > 0)
        <!-- Badge with count -->
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
            {{ $notifications['contact_messages']['pending'] > 9 ? '9+' : $notifications['contact_messages']['pending'] }}
        </span>
        
        @if($notifications['contact_messages']['urgent'] > 0)
            <!-- Urgent pulsing dot -->
            <span class="absolute -top-2 -left-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
        @endif
    @else
        <!-- No messages indicator -->
        <span class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            <i class="fas fa-check text-xs"></i>
        </span>
    @endif
</a>
@endif
@endcan
