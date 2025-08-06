<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification des images des résidences ===" . PHP_EOL;

$residences = App\Models\Residence::whereNotNull('image')->get(['id', 'name', 'image']);

if ($residences->count() === 0) {
    echo "Aucune résidence avec image trouvée." . PHP_EOL;
} else {
    foreach($residences as $residence) {
        echo "ID: {$residence->id}" . PHP_EOL;
        echo "Nom: {$residence->name}" . PHP_EOL;
        echo "Chemin image: {$residence->image}" . PHP_EOL;
        
        // Vérifier si le fichier existe
        $imagePath = str_replace('/storage/', '', $residence->image);
        $fullPath = storage_path('app/public/' . $imagePath);
        
        echo "Chemin complet: {$fullPath}" . PHP_EOL;
        echo "Fichier existe: " . (file_exists($fullPath) ? "OUI" : "NON") . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}

echo PHP_EOL . "=== Vérification du dossier storage ===" . PHP_EOL;
$storagePublicPath = storage_path('app/public/residences');
echo "Dossier residences: {$storagePublicPath}" . PHP_EOL;
echo "Dossier existe: " . (is_dir($storagePublicPath) ? "OUI" : "NON") . PHP_EOL;

if (is_dir($storagePublicPath)) {
    $files = glob($storagePublicPath . '/*');
    echo "Fichiers dans le dossier: " . count($files) . PHP_EOL;
    foreach($files as $file) {
        echo "  - " . basename($file) . PHP_EOL;
    }
}

echo PHP_EOL . "=== Vérification du lien symbolique ===" . PHP_EOL;
$publicStoragePath = public_path('storage');
echo "Dossier public/storage: {$publicStoragePath}" . PHP_EOL;
echo "Lien existe: " . (is_link($publicStoragePath) || is_dir($publicStoragePath) ? "OUI" : "NON") . PHP_EOL;
