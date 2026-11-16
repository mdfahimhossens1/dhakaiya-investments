<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    /**
     * Display all banners.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(15);

        $totalBanners = Banner::count();
        $activeBanners = Banner::where('status', 'active')->count();
        $inactiveBanners = Banner::where('status', 'inactive')->count();

        return view('super-admin.banners.index', compact(
            'banners',
            'totalBanners',
            'activeBanners',
            'inactiveBanners'
        ));
    }

    /**
     * Store a new banner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'url', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['image'] = $request->file('image')
            ->store('banners', 'public');

        Banner::create($validated);

        return redirect()
            ->route('super-admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    /**
     * Show a specific banner.
     */
    public function show(Banner $banner)
    {
        return response()->json([
            'id' => $banner->id,
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'description' => $banner->description,
            'image' => $banner->image
                ? Storage::disk('public')->url($banner->image)
                : null,
            'button_text' => $banner->button_text,
            'button_url' => $banner->button_url,
            'status' => $banner->status,
            'sort_order' => $banner->sort_order,
            'created_at' => $banner->created_at?->format('d M Y, h:i A'),
        ]);
    }

    /**
     * Update an existing banner.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'url', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $validated['image'] = $request->file('image')
                ->store('banners', 'public');
        } else {
            unset($validated['image']);
        }

        $banner->update($validated);

        return redirect()
            ->route('super-admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    /**
     * Delete a banner.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()
            ->route('super-admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}