<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * Display announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $announcements = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $totalAnnouncements = Announcement::count();

        $publishedAnnouncements = Announcement::where(
            'status',
            'published'
        )->count();

        $draftAnnouncements = Announcement::where(
            'status',
            'draft'
        )->count();

        $activeAnnouncements = Announcement::published()->count();

        return view('super-admin.announcements.index', compact(
            'announcements',
            'totalAnnouncements',
            'publishedAnnouncements',
            'draftAnnouncements',
            'activeAnnouncements'
        ));
    }

    /**
     * Store announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'message' => ['required', 'string'],

            'type' => [
                'required',
                Rule::in(['info', 'success', 'warning', 'danger']),
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],

            'is_dismissible' => ['nullable', 'boolean'],

            'start_at' => ['nullable', 'date'],

            'end_at' => [
                'nullable',
                'date',
                'after_or_equal:start_at',
            ],
        ]);

        $validated['is_dismissible'] = $request->boolean(
            'is_dismissible'
        );

        Announcement::create($validated);

        return redirect()
            ->route('super-admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Show announcement details.
     */
    public function show(Announcement $announcement)
    {
        return response()->json([
            'id' => $announcement->id,
            'title' => $announcement->title,
            'message' => $announcement->message,
            'type' => $announcement->type,
            'status' => $announcement->status,
            'is_dismissible' => $announcement->is_dismissible,

            'start_at' => $announcement->start_at?->format('Y-m-d\TH:i'),

            'end_at' => $announcement->end_at?->format('Y-m-d\TH:i'),

            'created_at' => $announcement->created_at?->format(
                'd M Y h:i A'
            ),

            'updated_at' => $announcement->updated_at?->format(
                'd M Y h:i A'
            ),
        ]);
    }

    /**
     * Update announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'message' => ['required', 'string'],

            'type' => [
                'required',
                Rule::in(['info', 'success', 'warning', 'danger']),
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],

            'is_dismissible' => ['nullable', 'boolean'],

            'start_at' => ['nullable', 'date'],

            'end_at' => [
                'nullable',
                'date',
                'after_or_equal:start_at',
            ],
        ]);

        $validated['is_dismissible'] = $request->boolean(
            'is_dismissible'
        );

        $announcement->update($validated);

        return redirect()
            ->route('super-admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Delete announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('super-admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}