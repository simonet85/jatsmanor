<?php

/**
 * Script de test pour les pages d'erreur personnalisées
 * Usage: php test_error_pages.php
 */

echo "🧪 Test des pages d'erreur personnalisées - Jatsmanor\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Configuration
$base_url = 'http://residences.test';
$error_codes = [404, 403, 500];

echo "📍 URL de base: {$base_url}\n";
echo "🔍 Tests des codes d'erreur: " . implode(', ', $error_codes) . "\n\n";

// Fonction pour tester une URL
function testErrorPage($url, $expected_code) {
    echo "🌐 Test: {$url}\n";
    
    // Initialiser cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Jatsmanor Error Page Tester');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Erreur cURL: {$error}\n";
        return false;
    }
    
    echo "📊 Code HTTP: {$http_code}\n";
    
    if ($http_code == $expected_code) {
        echo "✅ Code d'erreur correct\n";
        
        // Vérifier la présence de contenu spécifique
        if (strpos($response, 'Jatsmanor') !== false) {
            echo "✅ Contenu Jatsmanor détecté\n";
        } else {
            echo "⚠️  Contenu Jatsmanor non détecté\n";
        }
        
        if (strpos($response, $expected_code) !== false) {
            echo "✅ Code d'erreur affiché dans la page\n";
        } else {
            echo "⚠️  Code d'erreur non affiché dans la page\n";
        }
        
        return true;
    } else {
        echo "❌ Code d'erreur incorrect (attendu: {$expected_code})\n";
        return false;
    }
}

// Tests des pages d'erreur
$results = [];

foreach ($error_codes as $code) {
    echo "\n" . str_repeat("-", 30) . "\n";
    echo "🔍 Test de l'erreur {$code}\n";
    echo str_repeat("-", 30) . "\n";
    
    $test_url = "{$base_url}/test-{$code}";
    $success = testErrorPage($test_url, $code);
    $results[$code] = $success;
    
    echo "\n";
}

// Test d'une page inexistante (vraie 404)
echo str_repeat("-", 30) . "\n";
echo "🔍 Test page inexistante (vraie 404)\n";
echo str_repeat("-", 30) . "\n";

$fake_url = "{$base_url}/page-qui-nexiste-pas-" . time();
$results['real_404'] = testErrorPage($fake_url, 404);

// Résumé des résultats
echo "\n" . str_repeat("=", 50) . "\n";
echo "📋 RÉSUMÉ DES TESTS\n";
echo str_repeat("=", 50) . "\n";

$total_tests = count($results);
$passed_tests = count(array_filter($results));

foreach ($results as $test => $passed) {
    $status = $passed ? "✅ PASSÉ" : "❌ ÉCHOUÉ";
    echo "{$test}: {$status}\n";
}

echo "\n";
echo "📊 Résultat global: {$passed_tests}/{$total_tests} tests passés\n";

if ($passed_tests == $total_tests) {
    echo "🎉 Tous les tests sont passés ! Les pages d'erreur fonctionnent correctement.\n";
} else {
    echo "⚠️  Certains tests ont échoué. Vérifiez la configuration.\n";
}

echo "\n📝 Notes:\n";
echo "- Assurez-vous que le serveur local est démarré\n";
echo "- Vérifiez que l'URL de base est correcte\n";
echo "- Les routes de test doivent être activées (environnement local)\n";
echo "- Les caches Laravel doivent être vidés\n";

echo "\n🔗 URLs de test manuelles:\n";
foreach ($error_codes as $code) {
    echo "- {$base_url}/test-{$code}\n";
}

echo "\n✨ Test terminé !\n";
