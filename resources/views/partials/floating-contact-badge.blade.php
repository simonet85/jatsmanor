@can('view-contact-notifications')
    @if(isset($notifications) && $notifications['contact_messages']['pending'] > 0)
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('admin.contact.index') }}" class="relative inline-flex items-center justify-center w-16 h-16 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group">
        <i class="fas fa-envelope text-xl"></i>
        
        <!-- Badge Count -->
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full min-w-[24px] text-center shadow-lg">
            {{ $notifications['contact_messages']['pending'] }}
        </span>
        
        @if($notifications['contact_messages']['urgent'] > 0)
            <!-- Urgent pulsing ring -->
            <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 animate-ping"></span>
        @endif
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
            @if($notifications['contact_messages']['urgent'] > 0)
                {{ $notifications['contact_messages']['urgent'] }} message(s) urgent(s)
            @else
                {{ $notifications['contact_messages']['pending'] }} nouveau(x) message(s)
            @endif
            <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
        </div>
    </a>
</div>
@endif
@endcan
