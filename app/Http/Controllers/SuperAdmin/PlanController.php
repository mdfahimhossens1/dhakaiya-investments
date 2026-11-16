<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    /**
     * Display all plans.
     */
    public function index(Request $request)
    {
        $query = Plan::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $plans = $query
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Plan::count(),
            'active' => Plan::where('status', 'active')->count(),
            'inactive' => Plan::where('status', 'inactive')->count(),
            'featured' => Plan::where('is_featured', true)->count(),
        ];

        return view('super-admin.plans.index', compact(
            'plans',
            'stats'
        ));
    }

    /**
     * Show create plan form.
     */
    public function create()
    {
        return view('super-admin.plans.create');
    }

    /**
     * Store a new plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
                'max:36500',
            ],

            'display_price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'features' => [
                'nullable',
                'array',
                'max:30',
            ],

            'features.*' => [
                'required',
                'string',
                'max:255',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name']
        );

        $validated['features'] = array_values(array_filter(
            $validated['features'] ?? [],
            fn ($feature) => trim($feature) !== ''
        ));

        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Plan::create($validated);

        return redirect()
            ->route('super-admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display a specific plan.
     */
    public function show(Plan $plan)
    {
        return view('super-admin.plans.show', compact('plan'));
    }

    /**
     * Show edit plan form.
     */
    public function edit(Plan $plan)
    {
        return view('super-admin.plans.edit', compact('plan'));
    }

    /**
     * Update an existing plan.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
                'max:36500',
            ],

            'display_price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'features' => [
                'nullable',
                'array',
                'max:30',
            ],

            'features.*' => [
                'required',
                'string',
                'max:255',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
        ]);

        // Update slug only when the name changes.
        if ($plan->name !== $validated['name']) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $plan->id
            );
        }

        $validated['features'] = array_values(array_filter(
            $validated['features'] ?? [],
            fn ($feature) => trim($feature) !== ''
        ));

        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $plan->update($validated);

        return redirect()
            ->route('super-admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Update plan status.
     */
    public function updateStatus(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $plan->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Plan status updated successfully.');
    }

    /**
     * Delete a plan.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()
            ->route('super-admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }

    /**
     * Generate a unique slug.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'plan';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Plan::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}