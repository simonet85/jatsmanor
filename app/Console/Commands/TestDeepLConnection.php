<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DeepL\Translator;

class TestDeepLConnection extends Command
{
    protected $signature = 'deepl:connect-test';
    protected $description = 'Test DeepL connection with SSL workaround';

    public function handle()
    {
        $this->info('🔍 Testing DeepL connection with SSL workaround...');
        
        $apiKey = config('services.deepl.api_key');
        if (!$apiKey) {
            $this->error('❌ No DeepL API key found');
            return 1;
        }
        
        // Méthode 1: Temporairement modifier la configuration SSL pour ce processus
        if (config('app.env') === 'local') {
            $this->info('Local environment detected - applying SSL workaround...');
            
            // Sauvegarder les paramètres actuels
            $originalCurlOpts = [
                'cainfo' => ini_get('curl.cainfo'),
                'cafile' => ini_get('openssl.cafile'),
            ];
            
            // Temporairement désactiver la vérification SSL
            ini_set('curl.cainfo', '');
            putenv('CURL_CA_BUNDLE=');
            
            // Set custom stream context for all HTTP requests
            $defaultContext = stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'timeout' => 30,
                ]
            ]);
        }
        
        try {
            $this->info('Initializing DeepL Translator...');
            $translator = new Translator($apiKey);
            
            $this->info('Testing API connection...');
            $usage = $translator->getUsage();
            
            $this->info('✅ DeepL API connection successful!');
            $this->info("📊 Usage: " . $usage->character->count . "/" . $usage->character->limit . " characters");
            
            // Test une traduction simple
            $this->info('Testing translation...');
            $result = $translator->translateText('Bonjour le monde', 'fr', 'en');
            $this->info("✅ Translation test: 'Bonjour le monde' → '" . $result->text . "'");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ DeepL connection failed: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'SSL')) {
                $this->warn('');
                $this->warn('🔧 SSL Fix Instructions:');
                $this->warn('1. Download CA bundle from: https://curl.se/docs/caextract.html');
                $this->warn('2. Save as: C:\\laragon\\etc\\ssl\\cacert.pem');
                $this->warn('3. Add to php.ini:');
                $this->warn('   curl.cainfo = "C:\\laragon\\etc\\ssl\\cacert.pem"');
                $this->warn('   openssl.cafile = "C:\\laragon\\etc\\ssl\\cacert.pem"');
                $this->warn('4. Restart Laragon');
            }
            
            return 1;
        } finally {
            // Restaurer les paramètres SSL si on les a modifiés
            if (config('app.env') === 'local' && isset($originalCurlOpts)) {
                if ($originalCurlOpts['cainfo']) {
                    ini_set('curl.cainfo', $originalCurlOpts['cainfo']);
                }
            }
        }
    }
}
