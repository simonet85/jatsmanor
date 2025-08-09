<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Setting::set('site_name', $request->site_name, 'text', 'general');
            Setting::set('site_description', $request->site_description, 'textarea', 'general');

            return response()->json([
                'success' => true,
                'message' => 'Paramètres généraux mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update contact settings
     */
    public function updateContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Setting::set('contact_email', $request->contact_email, 'email', 'contact');
            Setting::set('contact_phone', $request->contact_phone, 'text', 'contact');
            Setting::set('contact_address', $request->contact_address, 'text', 'contact');

            return response()->json([
                'success' => true,
                'message' => 'Paramètres de contact mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking settings
     */
    public function updateBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min_stay_duration' => 'required|integer|min:1',
            'max_stay_duration' => 'required|integer|min:1',
            'cancellation_hours' => 'required|integer|min:0',
            'cleaning_fee' => 'required|integer|min:0',
            'security_deposit' => 'required|integer|min:0',
            'auto_confirm_bookings' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Setting::set('min_stay_duration', $request->min_stay_duration, 'number', 'booking');
            Setting::set('max_stay_duration', $request->max_stay_duration, 'number', 'booking');
            Setting::set('cancellation_hours', $request->cancellation_hours, 'number', 'booking');
            Setting::set('cleaning_fee', $request->cleaning_fee, 'number', 'booking');
            Setting::set('security_deposit', $request->security_deposit, 'number', 'booking');
            Setting::set('auto_confirm_bookings', $request->has('auto_confirm_bookings') ? '1' : '0', 'boolean', 'booking');

            return response()->json([
                'success' => true,
                'message' => 'Paramètres de réservation mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update frontend settings
     */
    public function updateFrontend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:255',
            'footer_description' => 'required|string|max:1000',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Setting::set('hero_title', $request->hero_title, 'text', 'frontend');
            Setting::set('hero_subtitle', $request->hero_subtitle, 'text', 'frontend');
            Setting::set('footer_description', $request->footer_description, 'textarea', 'frontend');

            // Gérer l'upload de l'image hero
            if ($request->hasFile('hero_image')) {
                $oldImage = Setting::get('hero_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                $imagePath = $request->file('hero_image')->store('images', 'public');
                Setting::set('hero_image', $imagePath, 'image', 'frontend');
            }

            return response()->json([
                'success' => true,
                'message' => 'Paramètres frontend mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update language settings
     */
    public function updateLanguage(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Language settings update request:', [
            'all_data' => $request->all(),
            'has_checkbox' => $request->has('show_language_selector'),
            'checkbox_value' => $request->get('show_language_selector')
        ]);

        $validator = Validator::make($request->all(), [
            'show_language_selector' => 'nullable|in:on,1,true,0,false',
            'default_language' => 'required|in:fr,en',
        ]);

        if ($validator->fails()) {
            \Log::error('Language settings validation failed:', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle checkbox value properly (can be 'on', '1', 'true', or absent)
            $showSelector = $request->has('show_language_selector') && 
                           in_array($request->show_language_selector, ['on', '1', 'true', true]) ? '1' : '0';
            
            Setting::set('show_language_selector', $showSelector, 'boolean', 'language');
            Setting::set('default_language', $request->default_language, 'select', 'language');

            return response()->json([
                'success' => true,
                'message' => 'Paramètres de langue mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }
}
