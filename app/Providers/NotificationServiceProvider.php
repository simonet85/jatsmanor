<?php

namespace App\Providers;

use App\Models\ContactMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share notification data with all dashboard-related views using specific patterns
        View::composer([
            'layouts.dashboard', 
            'dashboard.layout',
            'partials.dashboard-sidebar',
            'partials.contact-notifications-widget',
            'dashboard.partials.topnav',
            'dashboard',
            'dashboard.index',
            'admin.contact.*'
        ], function ($view) {
            // Only load notifications for users with permission
            if (auth()->check() && auth()->user()->can('view-contact-notifications')) {
                $notifications = $this->getNotificationCounts();
                $view->with('notifications', $notifications);
            }
        });

        // Also add a global share for all views that contain 'dashboard'
        View::composer('*', function ($view) {
            if ((str_contains($view->getName(), 'dashboard') || str_contains($view->getName(), 'admin')) 
                && auth()->check() && auth()->user()->can('view-contact-notifications')) {
                $notifications = $this->getNotificationCounts();
                $view->with('notifications', $notifications);
            }
        });
    }

    private function getNotificationCounts()
    {
        return Cache::remember('admin_notifications', 60, function() { // Cache for 1 minute
            return [
                'contact_messages' => [
                    'pending' => ContactMessage::where('status', 'pending')->count(),
                    'total_unread' => ContactMessage::where('status', 'pending')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->count(),
                    'today' => ContactMessage::whereDate('created_at', today())->count(),
                    'this_week' => ContactMessage::whereBetween('created_at', [
                        now()->startOfWeek(), 
                        now()->endOfWeek()
                    ])->count(),
                    'urgent' => ContactMessage::where('status', 'pending')
                        ->where('created_at', '<=', now()->subHours(24))
                        ->count(), // Messages older than 24h
                ],
                'last_updated' => now()->format('H:i'),
            ];
        });
    }
}
