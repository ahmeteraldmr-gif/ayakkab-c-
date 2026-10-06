<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:250',
            'badge' => 'nullable|string|max:50',
            'discount_text' => 'nullable|string|max:50',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'discount_text' => $validated['discount_text'] ?? null,
            'button_text' => $validated['button_text'] ?? 'Modelleri Keşfet',
            'button_url' => $validated['button_url'] ?? '/urunler',
            'bg_color' => $validated['bg_color'] ?? '#111111',
            'image' => $imagePath,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanya / Banner başarıyla eklendi.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:250',
            'badge' => 'nullable|string|max:50',
            'discount_text' => 'nullable|string|max:50',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $campaign->image;
        if ($request->hasFile('image')) {
            if ($imagePath && !str_starts_with($imagePath, 'http')) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'discount_text' => $validated['discount_text'] ?? null,
            'button_text' => $validated['button_text'] ?? 'Modelleri Keşfet',
            'button_url' => $validated['button_url'] ?? '/urunler',
            'bg_color' => $validated['bg_color'] ?? '#111111',
            'image' => $imagePath,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanya güncellendi.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        if ($campaign->image && !str_starts_with($campaign->image, 'http')) {
            Storage::disk('public')->delete($campaign->image);
        }
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanya silindi.');
    }
}
