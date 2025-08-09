<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProfessionalTranslationService;
use App\Models\Residence;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    private ProfessionalTranslationService $translationService;
    
    public function __construct(ProfessionalTranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Get DeepL API status
     */
    public function status()
    {
        try {
            $available = $this->translationService->isDeepLAvailable();
            $usage = null;
            
            if ($available) {
                $usage = $this->translationService->getUsageInfo();
            }
            
            return response()->json([
                'available' => $available,
                'usage' => $usage,
                'provider' => $available ? 'DeepL API' : 'Fallback Dictionary'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Translate text
     */
    public function translate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $startTime = microtime(true);
            $translation = $this->translationService->translateToEnglish($request->text, false);
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            return response()->json([
                'translation' => $translation,
                'duration_ms' => $duration,
                'provider' => $this->translationService->isDeepLAvailable() ? 'DeepL API' : 'Fallback',
                'original' => $request->text
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Translation failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test observer functionality
     */
    public function testObserver(Request $request)
    {
        try {
            // Create a test residence to trigger observer
            $testResidence = Residence::create([
                'name' => 'Test API Villa ' . now()->format('H:i:s'),
                'description' => 'Villa de test pour vérifier la traduction automatique avec observer',
                'short_description' => 'Villa de test pour API',
                'location' => 'Test Location',
                'price' => 100000,
                'price_per_night' => 300,
                'size' => 80,
                'surface' => 80,
                'max_guests' => 4,
                'floor' => '1er étage',
                'rating' => 4.5,
                'availability_status' => 'available',
                'image' => 'test-api.jpg',
                'is_featured' => false,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Observer test completed successfully',
                'residence' => [
                    'id' => $testResidence->id,
                    'name' => $testResidence->name,
                    'name_en' => $testResidence->name_en,
                    'description' => $testResidence->description,
                    'description_en' => $testResidence->description_en,
                    'short_description' => $testResidence->short_description,
                    'short_description_en' => $testResidence->short_description_en
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get residences translation status
     */
    public function residencesStatus()
    {
        try {
            $total = Residence::count();
            $translated = Residence::whereNotNull('name_en')
                ->where('name_en', '!=', '')
                ->count();
            
            $percentage = $total > 0 ? round(($translated / $total) * 100, 1) : 0;
            
            $recentResidences = Residence::select('id', 'name', 'name_en', 'description_en', 'short_description_en', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($residence) {
                    return [
                        'id' => $residence->id,
                        'name' => $residence->name,
                        'name_en' => $residence->name_en,
                        'has_translation' => !empty($residence->name_en),
                        'created_at' => $residence->created_at->format('d/m/Y H:i')
                    ];
                });

            return response()->json([
                'total' => $total,
                'translated' => $translated,
                'percentage' => $percentage,
                'recent_residences' => $recentResidences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get residences status',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Translate all residences
     */
    public function translateAll(Request $request)
    {
        try {
            $force = $request->boolean('force', false);
            $limit = $request->integer('limit', 10);
            
            $query = Residence::query();
            
            if (!$force) {
                $query->where(function ($q) {
                    $q->whereNull('name_en')
                      ->orWhere('name_en', '')
                      ->orWhereNull('description_en')
                      ->orWhere('description_en', '');
                });
            }
            
            $residences = $query->limit($limit)->get();
            $translated = 0;
            $errors = [];
            
            foreach ($residences as $residence) {
                try {
                    // Force translation by updating the model
                    $residence->touch(); // This will trigger the observer
                    $translated++;
                } catch (\Exception $e) {
                    $errors[] = "Residence {$residence->id}: " . $e->getMessage();
                }
            }
            
            return response()->json([
                'success' => true,
                'processed' => $residences->count(),
                'translated' => $translated,
                'errors' => $errors,
                'force_mode' => $force
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
