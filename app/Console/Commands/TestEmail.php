<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality with Mailtrap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email functionality...');

        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+225 01 02 03 04 05',
            'subject' => 'Test de fonctionnalité email',
            'message' => 'Ceci est un message de test pour vérifier que l\'intégration Mailtrap fonctionne correctement.'
        ];

        try {
            Mail::to(config('mail.from.address'))
                ->send(new ContactMessage($testData));
                
            $this->info('✅ Email sent successfully to Mailtrap!');
            $this->info('Check your Mailtrap inbox at: https://mailtrap.io/inboxes');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
        }
    }
}
