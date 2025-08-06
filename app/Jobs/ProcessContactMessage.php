<?php

namespace App\Jobs;

use App\Mail\ContactMessage as ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessContactMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contactMessage;
    public $tries = 3; // Retry up to 3 times
    public $backoff = [60, 300, 900]; // Backoff delays: 1min, 5min, 15min

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function handle()
    {
        try {
            $contactData = [
                'name' => $this->contactMessage->name,
                'email' => $this->contactMessage->email,
                'phone' => $this->contactMessage->phone,
                'subject' => $this->contactMessage->subject,
                'message' => $this->contactMessage->message,
                'id' => $this->contactMessage->id,
            ];

            Mail::to(config('mail.from.address'))
                ->send(new ContactMessageMail($contactData));

            // Update status to processed
            $this->contactMessage->update([
                'status' => 'processed',
                'processed_at' => now()
            ]);

            Log::info('Contact message email sent successfully', [
                'message_id' => $this->contactMessage->id,
                'email' => $this->contactMessage->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send contact message email', [
                'message_id' => $this->contactMessage->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // If this is the last attempt, mark as failed
            if ($this->attempts() >= $this->tries) {
                $this->contactMessage->update(['status' => 'failed']);
            }

            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Contact message job failed permanently', [
            'message_id' => $this->contactMessage->id,
            'error' => $exception->getMessage()
        ]);

        $this->contactMessage->update(['status' => 'failed']);
    }
}
