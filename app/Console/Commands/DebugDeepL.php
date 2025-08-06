<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DeepL\Translator;

class DebugDeepL extends Command
{
    protected $signature = 'deepl:debug';
    protected $description = 'Debug DeepL API configuration';

    public function handle()
    {
        $this->info('🔍 Debugging DeepL Configuration...');
        
        // 1. Check environment variable
        $apiKey = env('DEEPL_API_KEY');
        $this->info("ENV DEEPL_API_KEY: " . ($apiKey ? "Present (length: " . strlen($apiKey) . ")" : "Not found"));
        
        // 2. Check config
        $configKey = config('services.deepl.api_key');
        $this->info("Config services.deepl.api_key: " . ($configKey ? "Present (length: " . strlen($configKey) . ")" : "Not found"));
        
        // 3. Show the actual key (first and last 5 characters)
        if ($apiKey) {
            $this->info("API Key format: " . substr($apiKey, 0, 5) . "..." . substr($apiKey, -5));
        }
        
        // 4. Try to initialize DeepL
        try {
            if ($apiKey) {
                $this->info("Attempting to initialize DeepL Translator...");
                
                // Test 1: Default initialization
                try {
                    $translator = new Translator($apiKey);
                    $this->info("✅ Default Translator initialized successfully");
                } catch (\Exception $e) {
                    $this->error("❌ Default initialization failed: " . $e->getMessage());
                    
                    // Test 2: With SSL options (check if DeepL supports this)
                    $this->info("Trying alternative initialization...");
                    
                    // For cURL, we might need to set CURLOPT_SSL_VERIFYPEER to false
                    $context = stream_context_create([
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ],
                        "http" => [
                            "timeout" => 30,
                        ]
                    ]);
                    
                    // Try setting a temporary curl option globally
                    if (function_exists('curl_version')) {
                        $this->info("Setting CURL SSL options...");
                        // This is a workaround for development only
                        putenv('CURL_CA_BUNDLE=');
                    }
                    
                    $translator = new Translator($apiKey);
                    $this->info("✅ Alternative Translator initialized successfully");
                }
                
                // 5. Test API call
                $this->info("Testing API call...");
                $usage = $translator->getUsage();
                $this->info("✅ API call successful");
                $this->info("Usage: " . $usage->character->count . "/" . $usage->character->limit . " characters");
                
                // 6. Test translation
                $this->info("Testing translation...");
                $result = $translator->translateText('Bonjour', 'fr', 'en-US');
                $this->info("✅ Translation successful: Bonjour → " . $result->text);
                
            } else {
                $this->error("❌ No API key found");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->info("💡 This might be an SSL certificate issue in local development.");
            $this->info("💡 Try downloading the latest CA bundle or configuring SSL properly.");
        }
        
        return 0;
    }
}
