@can('view-contact-notifications')
    @if(isset($notifications) && $notifications['contact_messages']['pending'] > 0)
        <a href="{{ route('admin.contact.index') }}" class="relative inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
            <i class="fas fa-envelope"></i>
            <span class="text-sm font-medium">Messages</span>
            
            <!-- Badge Count -->
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full min-w-[20px] text-center">
                {{ $notifications['contact_messages']['pending'] }}
            </span>
            
            @if($notifications['contact_messages']['urgent'] > 0)
                <!-- Urgent indicator -->
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
            @endif
        </a>
    @else
        <a href="{{ route('admin.contact.index') }}" class="relative inline-flex items-center gap-2 px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
            <i class="fas fa-envelope"></i>
            <span class="text-sm font-medium">Messages</span>
            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-check"></i>
            </span>
        </a>
    @endif
@endcan