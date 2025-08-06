<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Jobs\ProcessContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    /**
     * Show contact form
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Store contact message
     */
    public function store(Request $request)
    {
        // Rate limiting - max 3 messages per 10 minutes per IP
        $key = 'contact_form_' . $request->ip();
        if (Cache::get($key, 0) >= 3) {
            return back()->withErrors(['message' => 'Trop de messages envoyés. Veuillez patienter avant de renvoyer un message.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000|min:10', // Minimum length to avoid spam
        ]);

        // Simple spam detection
        $message = strtolower($request->message);
        $spamKeywords = ['viagra', 'casino', 'loan', 'credit', 'bitcoin', 'cryptocurrency'];
        foreach ($spamKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                // Log potential spam but don't inform user
                \Log::warning('Potential spam message detected', [
                    'ip' => $request->ip(),
                    'email' => $request->email,
                    'message' => $request->message
                ]);
                return back()->with('success', 'Votre message a été envoyé avec succès !');
            }
        }

        // Store in database with status
        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'status' => 'pending',
        ]);

        // Increment rate limiting counter
        Cache::put($key, Cache::get($key, 0) + 1, 600); // 10 minutes

        try {
            // Dispatch job to process email (queued, non-blocking)
            ProcessContactMessage::dispatch($contactMessage);
                
            return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons bientôt.');
        } catch (\Exception $e) {
            \Log::error('Failed to dispatch contact message job: ' . $e->getMessage());
            return back()->with('success', 'Votre message a été enregistré avec succès ! Nous vous répondrons bientôt.');
        }
    }

    /**
     * Subscribe to newsletter
     */
    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Newsletter::firstOrCreate(
            ['email' => $request->email],
            [
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribe_token' => \Illuminate\Support\Str::random(40),
            ]
        );

        return back()->with('success', 'Merci pour votre abonnement à notre newsletter !');
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('unsubscribe_token', $token)->first();
        
        if ($newsletter) {
            $newsletter->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
            
            return view('newsletter.unsubscribed');
        }

        return abort(404);
    }
}
