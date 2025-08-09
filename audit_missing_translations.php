<?php
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Residence;
use App\Models\Testimonial;

// Audit missing English translations for residences
$residences = Residence::all();
echo "\n--- Residences missing English fields ---\n";
foreach ($residences as $residence) {
    $missing = [];
    if (empty($residence->name_en)) $missing[] = 'name_en';
    if (empty($residence->description_en)) $missing[] = 'description_en';
    if (empty($residence->short_description_en)) $missing[] = 'short_description_en';
    if (!empty($missing)) {
        echo "ID: {$residence->id}, Slug: {$residence->slug}, Missing: " . implode(', ', $missing) . "\n";
    }
}

// Audit missing English translations for testimonials
$testimonials = Testimonial::all();
echo "\n--- Testimonials missing English fields ---\n";
foreach ($testimonials as $testimonial) {
    $missing = [];
    if (empty($testimonial->commentaire_en)) $missing[] = 'commentaire_en';
    if (empty($testimonial->nom_en)) $missing[] = 'nom_en';
    if (empty($testimonial->ville_en)) $missing[] = 'ville_en';
    if (!empty($missing)) {
        echo "ID: {$testimonial->id}, Missing: " . implode(', ', $missing) . "\n";
    }
}

echo "\nAudit complete.\n";
