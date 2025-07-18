<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebsiteSettingController extends Controller
{
    /**
     * Show the settings form.
     */
    public function index()
    {
        $settings = WebsiteSetting::getSettings();
        return view('admin.settings.website', compact('settings'));
    }

    /**
     * Update the website settings.
     */
    public function update(Request $request)
    {
        Log::info('Website settings update request', ['request' => $request->all()]);
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string|max:1000',
            'copyright_text' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $settings = WebsiteSetting::getSettings();

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($settings->site_logo) {
                Storage::delete('public/' . $settings->site_logo);
            }
            
            $logoPath = $request->file('site_logo')->store('logos', 'public');
            $validated['site_logo'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($settings->favicon) {
                Storage::delete('public/' . $settings->favicon);
            }
            
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $validated['favicon'] = $faviconPath;
        }

        // Handle meta tags
        $validated['meta_tags'] = [
            'description' => $request->input('meta_description'),
            'keywords' => $request->input('meta_keywords'),
        ];

        $settings->update($validated);
        
        // Clear cache
        Cache::forget('website_settings');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Website settings updated successfully!']);
        }
        
        return redirect()->route('admin.settings.website')
            ->with('success', 'Website settings updated successfully!');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        $settings = WebsiteSetting::getSettings();
        
        if ($settings->site_logo) {
            Storage::delete('public/' . $settings->site_logo);
            $settings->update(['site_logo' => null]);
            Cache::forget('website_settings');
        }

        return redirect()->route('admin.settings.website')
            ->with('success', 'Logo removed successfully!');
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        $settings = WebsiteSetting::getSettings();
        
        if ($settings->favicon) {
            Storage::delete('public/' . $settings->favicon);
            $settings->update(['favicon' => null]);
            Cache::forget('website_settings');
        }

        return redirect()->route('admin.settings.website')
            ->with('success', 'Favicon removed successfully!');
    }
}
