<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Display FAQ management page.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $faqs = $query
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Faq::count(),
            'published' => Faq::where('status', 'published')->count(),
            'draft' => Faq::where('status', 'draft')->count(),
            'categories' => Faq::whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct('category')
                ->count('category'),
        ];

        // Available categories
        $categories = Faq::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('super-admin.faqs.index', compact(
            'faqs',
            'stats',
            'categories'
        ));
    }

    /**
     * Store a new FAQ.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['category'] = filled($validated['category'] ?? null)
            ? trim($validated['category'])
            : null;

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Faq::create($validated);

        return redirect()
            ->route('super-admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Display a specific FAQ.
     */
    public function show(Faq $faq)
    {
        return response()->json([
            'success' => true,
            'faq' => [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category,
                'status' => $faq->status,
                'sort_order' => $faq->sort_order,
                'created_at' => $faq->created_at?->format('d M Y, h:i A'),
                'updated_at' => $faq->updated_at?->format('d M Y, h:i A'),
            ],
        ]);
    }

    /**
     * Update an existing FAQ.
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['category'] = filled($validated['category'] ?? null)
            ? trim($validated['category'])
            : null;

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq->update($validated);

        return redirect()
            ->route('super-admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete an FAQ.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()
            ->route('super-admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}