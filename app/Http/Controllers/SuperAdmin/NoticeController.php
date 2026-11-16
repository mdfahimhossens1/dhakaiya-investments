<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NoticeController extends Controller
{
    /**
     * Display all notices.
     */
    public function index(Request $request)
    {
        $query = Notice::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority Filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Notice List
        $notices = $query
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $totalNotices = Notice::count();

        $publishedNotices = Notice::where('status', 'published')->count();

        $draftNotices = Notice::where('status', 'draft')->count();

        $pinnedNotices = Notice::where('is_pinned', true)->count();

        return view('super-admin.notices.index', compact(
            'notices',
            'totalNotices',
            'publishedNotices',
            'draftNotices',
            'pinnedNotices'
        ));
    }


    /**
     * Store a new notice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'content' => ['required', 'string'],

            'priority' => [
                'required',
                Rule::in(['normal', 'important', 'urgent']),
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],

            'is_pinned' => ['nullable', 'boolean'],

            'publish_date' => ['nullable', 'date'],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:publish_date',
            ],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['title']
        );

        $validated['is_pinned'] = $request->boolean('is_pinned');

        Notice::create($validated);

        return redirect()
            ->route('super-admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }


    /**
     * Display a single notice.
     */
    public function show(Notice $notice)
    {
        return response()->json([
            'id' => $notice->id,
            'title' => $notice->title,
            'slug' => $notice->slug,
            'content' => $notice->content,
            'priority' => $notice->priority,
            'status' => $notice->status,
            'is_pinned' => $notice->is_pinned,
            'publish_date' => $notice->publish_date?->format('Y-m-d'),
            'expiry_date' => $notice->expiry_date?->format('Y-m-d'),
            'created_at' => $notice->created_at?->format('d M Y h:i A'),
            'updated_at' => $notice->updated_at?->format('d M Y h:i A'),
        ]);
    }


    /**
     * Update an existing notice.
     */
    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'content' => ['required', 'string'],

            'priority' => [
                'required',
                Rule::in(['normal', 'important', 'urgent']),
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],

            'is_pinned' => ['nullable', 'boolean'],

            'publish_date' => ['nullable', 'date'],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:publish_date',
            ],
        ]);

        // Update slug only if title changes
        if ($notice->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['title'],
                $notice->id
            );
        }

        $validated['is_pinned'] = $request->boolean('is_pinned');

        $notice->update($validated);

        return redirect()
            ->route('super-admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }


    /**
     * Delete a notice.
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()
            ->route('super-admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }


    /**
     * Generate a unique slug.
     */
    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);

        if ($slug === '') {
            $slug = 'notice';
        }

        $originalSlug = $slug;
        $counter = 1;

        while (
            Notice::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}