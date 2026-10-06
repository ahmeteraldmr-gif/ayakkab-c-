<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Whitelist of allowed setting keys
     */
    protected array $allowedKeys = [
        'site_name',
        'site_title',
        'site_description',
        'site_phone',
        'site_whatsapp',
        'site_instagram',
        'site_email',
        'site_address',
        'site_hours',
        'about_mini',
        'about_full',
        'free_shipping_threshold',
        'shipping_cost',
        'facebook_url',
        'twitter_url',
    ];

    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        // 1. Strict Validation
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:100',
            'site_title' => 'nullable|string|max:200',
            'site_description' => 'nullable|string|max:500',
            'site_phone' => 'nullable|string|max:50',
            'site_whatsapp' => 'nullable|string|max:50',
            'site_instagram' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:100',
            'site_address' => 'nullable|string|max:300',
            'site_hours' => 'nullable|string|max:200',
            'about_mini' => 'nullable|string|max:1000',
            'about_full' => 'nullable|string|max:5000',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,ico|max:1024',
        ]);

        // 2. Process only whitelisted keys
        foreach ($this->allowedKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // 3. Secure File Uploads
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && !str_starts_with($oldLogo, 'http')) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && !str_starts_with($oldFavicon, 'http')) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path);
        }

        Cache::forget('app_settings');

        \App\Models\AuditLog::record(
            'settings_updated',
            Setting::class,
            null,
            'Site genel ayarları güncellendi.'
        );

        return back()->with('success', 'Site ayarları başarıyla güncellendi.');
    }
}
