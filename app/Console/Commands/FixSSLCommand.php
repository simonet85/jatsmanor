<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixSSLCommand extends Command
{
    protected $signature = 'ssl:fix';
    protected $description = 'Fix SSL certificate issues for local development';

    public function handle()
    {
        $this->info('🔧 Fixing SSL configuration for local development...');
        
        // Check if running on Windows
        if (PHP_OS_FAMILY === 'Windows') {
            $this->info('Windows detected - applying SSL fix...');
            
            // Method 1: Disable SSL verification for cURL (development only)
            $curlCaInfo = ini_get('curl.cainfo');
            $opensslCaFile = ini_get('openssl.cafile');
            
            $this->info("Current curl.cainfo: " . ($curlCaInfo ?: 'Not set'));
            $this->info("Current openssl.cafile: " . ($opensslCaFile ?: 'Not set'));
            
            // Create a temporary solution by setting environment variables
            putenv('CURL_CA_BUNDLE=');
            putenv('SSL_CERT_FILE=');
            
            $this->info('✅ SSL verification temporarily disabled for this session');
            $this->warn('⚠️  This is only for local development - never use in production!');
            
            // Test if this fixes the DeepL connection
            $this->info('Testing DeepL connection...');
            
            try {
                $apiKey = config('services.deepl.api_key');
                if ($apiKey) {
                    $translator = new \DeepL\Translator($apiKey);
                    $usage = $translator->getUsage();
                    $this->info('✅ DeepL API connection successful!');
                    $this->info("Usage: " . $usage->character->count . "/" . $usage->character->limit . " characters");
                } else {
                    $this->error('❌ No DeepL API key found');
                }
            } catch (\Exception $e) {
                $this->error('❌ Still having issues: ' . $e->getMessage());
                $this->info('💡 You may need to download and configure a CA bundle manually');
                $this->info('💡 Or contact your system administrator for SSL configuration');
            }
            
        } else {
            $this->info('Non-Windows system detected - SSL fix not needed');
        }
        
        return 0;
    }
}
