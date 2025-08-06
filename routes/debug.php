Route::get('/test-notifications', function() {
    $provider = new App\Providers\NotificationServiceProvider(app());
    $method = new ReflectionMethod($provider, 'getNotificationCounts');
    $method->setAccessible(true);
    $notifications = $method->invoke($provider);
    
    return response()->json([
        'notifications' => $notifications,
        'contact_messages_count' => App\Models\ContactMessage::count(),
        'pending_count' => App\Models\ContactMessage::where('status', 'pending')->count(),
        'urgent_count' => App\Models\ContactMessage::where('status', 'pending')->where('created_at', '<=', now()->subHours(24))->count()
    ]);
})->middleware('auth');
