<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContactMessage;
use App\Providers\NotificationServiceProvider;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the notification system and display counts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Notification System...');
        $this->newLine();

        // Get notification data
        $provider = new NotificationServiceProvider(app());
        $method = new \ReflectionMethod($provider, 'getNotificationCounts');
        $method->setAccessible(true);
        $notifications = $method->invoke($provider);

        // Display notification counts
        $this->info('📊 Contact Message Statistics:');
        $this->table([
            'Metric', 'Count'
        ], [
            ['Total Messages', ContactMessage::count()],
            ['Pending Messages', $notifications['contact_messages']['pending']],
            ['Urgent Messages (>24h)', $notifications['contact_messages']['urgent']],
            ['Today\'s Messages', $notifications['contact_messages']['today']],
            ['This Week\'s Messages', $notifications['contact_messages']['this_week']],
        ]);

        // Test individual counts
        $this->newLine();
        $this->info('🔍 Direct Database Queries:');
        $this->line('• Total contact messages: ' . ContactMessage::count());
        $this->line('• Pending messages: ' . ContactMessage::where('status', 'pending')->count());
        $this->line('• Urgent messages: ' . ContactMessage::where('status', 'pending')->where('created_at', '<=', now()->subHours(24))->count());
        $this->line('• Today\'s messages: ' . ContactMessage::whereDate('created_at', today())->count());

        // Check if notifications match direct queries
        $this->newLine();
        $directPending = ContactMessage::where('status', 'pending')->count();
        $cachedPending = $notifications['contact_messages']['pending'];
        
        if ($directPending === $cachedPending) {
            $this->info('✅ Cached notifications match direct database queries!');
        } else {
            $this->error('❌ Mismatch detected: Direct=' . $directPending . ', Cached=' . $cachedPending);
            $this->warn('Try clearing the cache: php artisan cache:clear');
        }

        $this->newLine();
        $this->info('🎯 Notification system test completed!');
        $this->line('Last updated: ' . $notifications['last_updated']);
    }
}
