<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage-contacts']);
    }

    /**
     * Display contact messages with pagination and caching
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        // Create cache key based on filters
        $cacheKey = "contact_messages_{$status}_{$search}_" . request('page', 1);
        
        $messages = Cache::remember($cacheKey, 300, function() use ($status, $search) { // 5 minutes cache
            $query = ContactMessage::query()
                ->orderBy('created_at', 'desc');
            
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
                });
            }
            
            return $query->paginate(20);
        });
        
        // Get statistics with caching
        $stats = Cache::remember('contact_stats', 300, function() {
            return [
                'total' => ContactMessage::count(),
                'pending' => ContactMessage::where('status', 'pending')->count(),
                'processed' => ContactMessage::where('status', 'processed')->count(),
                'replied' => ContactMessage::where('status', 'replied')->count(),
                'today' => ContactMessage::whereDate('created_at', today())->count(),
                'this_week' => ContactMessage::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ];
        });
        
        return view('admin.contact.index', compact('messages', 'stats', 'status', 'search'));
    }
    
    /**
     * Update message status
     */
    public function updateStatus(ContactMessage $message, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,replied'
        ]);
        
        $message->update([
            'status' => $request->status,
            'processed_at' => $request->status === 'replied' ? now() : $message->processed_at
        ]);
        
        // Clear cache
        Cache::flush(); // In production, be more selective about cache clearing
        
        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }
    
    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_processed,mark_replied,delete',
            'selected' => 'required|array|min:1',
            'selected.*' => 'exists:contact_messages,id'
        ]);
        
        $messages = ContactMessage::whereIn('id', $request->selected);
        
        switch ($request->action) {
            case 'mark_processed':
                $messages->update(['status' => 'processed', 'processed_at' => now()]);
                $successMessage = 'Messages marqués comme traités.';
                break;
            case 'mark_replied':
                $messages->update(['status' => 'replied', 'processed_at' => now()]);
                $successMessage = 'Messages marqués comme répondus.';
                break;
            case 'delete':
                $messages->delete();
                $successMessage = 'Messages supprimés.';
                break;
        }
        
        // Clear cache
        Cache::flush();
        
        return redirect()->back()->with('success', $successMessage);
    }
}
