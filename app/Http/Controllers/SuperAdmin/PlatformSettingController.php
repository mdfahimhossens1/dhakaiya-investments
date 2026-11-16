<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatformSettingController extends Controller
{
    /**
     * Display platform settings.
     */
    public function index()
    {
        $settings = PlatformSetting::pluck('value', 'key')->toArray();

        return view('super-admin.platform-settings.index', compact('settings'));
    }

    /**
     * Update platform settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General Settings
            'platform_name' => ['required', 'string', 'max:150'],
            'platform_tagline' => ['nullable', 'string', 'max:255'],
            'platform_description' => ['nullable', 'string', 'max:2000'],

            // Branding Settings
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico', 'max:1024'],
            'primary_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],

            // Contact Settings
            'support_email' => ['nullable', 'email', 'max:150'],
            'support_phone' => ['nullable', 'string', 'max:30'],
            'contact_address' => ['nullable', 'string', 'max:500'],

            // Social Media
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],

            // Localization
            'default_language' => ['required', 'in:English,Bangla'],
            'timezone' => ['required', 'timezone'],
            'display_currency' => ['required', 'in:BDT,USD'],

            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],

            // Footer
            'footer_description' => ['nullable', 'string', 'max:1000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],

            // Maintenance
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:1000'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | General Settings
        |--------------------------------------------------------------------------
        */

        $generalSettings = [
            'platform_name',
            'platform_tagline',
            'platform_description',
        ];

        foreach ($generalSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key] ?? '',
                'general'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Branding Settings
        |--------------------------------------------------------------------------
        */

        foreach (['logo', 'favicon'] as $key) {
            if ($request->hasFile($key)) {

                $oldPath = PlatformSetting::getValue($key);

                $path = $request->file($key)->store(
                    'platform/branding',
                    'public'
                );

                PlatformSetting::setValue(
                    $key,
                    $path,
                    'branding',
                    'image'
                );

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        PlatformSetting::setValue(
            'primary_color',
            $validated['primary_color'] ?? '#172554',
            'branding'
        );

        /*
        |--------------------------------------------------------------------------
        | Contact Settings
        |--------------------------------------------------------------------------
        */

        $contactSettings = [
            'support_email',
            'support_phone',
            'contact_address',
        ];

        foreach ($contactSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key] ?? '',
                'contact'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Social Media Settings
        |--------------------------------------------------------------------------
        */

        $socialSettings = [
            'facebook_url',
            'youtube_url',
            'linkedin_url',
            'instagram_url',
        ];

        foreach ($socialSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key] ?? '',
                'social'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Localization Settings
        |--------------------------------------------------------------------------
        */

        $localizationSettings = [
            'default_language',
            'timezone',
            'display_currency',
        ];

        foreach ($localizationSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key],
                'localization'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEO Settings
        |--------------------------------------------------------------------------
        */

        $seoSettings = [
            'meta_title',
            'meta_description',
            'meta_keywords',
        ];

        foreach ($seoSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key] ?? '',
                'seo'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Footer Settings
        |--------------------------------------------------------------------------
        */

        $footerSettings = [
            'footer_description',
            'copyright_text',
        ];

        foreach ($footerSettings as $key) {
            PlatformSetting::setValue(
                $key,
                $validated[$key] ?? '',
                'footer'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Maintenance Settings
        |--------------------------------------------------------------------------
        */

        PlatformSetting::setValue(
            'maintenance_mode',
            $request->boolean('maintenance_mode'),
            'maintenance',
            'boolean'
        );

        PlatformSetting::setValue(
            'maintenance_message',
            $validated['maintenance_message'] ?? '',
            'maintenance'
        );

        return redirect()
            ->route('super-admin.platform-settings.index')
            ->with('success', 'Platform settings updated successfully.');
    }
}