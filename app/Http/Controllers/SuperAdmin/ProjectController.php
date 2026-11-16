<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['company', 'projectCategory', 'projectTemplate']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        // category: id অথবা পুরোনো slug দুটোই চলবে
        if ($request->filled('category')) {
            if (is_numeric($request->category)) {
                $query->where('project_category_id', $request->category);
            } else {
                $query->where('category', $request->category);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalProjects = Project::count();
        $activeProjects = Project::where('is_active', true)->count();
        $featuredProjects = Project::where('is_featured', true)->count();
        $upcomingProjects = Project::where('status', 'upcoming')->count();

        return view('super-admin.projects.index', array_merge(
            $this->formData(),
            compact(
                'projects',
                'totalProjects',
                'activeProjects',
                'featuredProjects',
                'upcomingProjects'
            )
        ));
    }

    public function create()
    {
        return view('super-admin.projects.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        // নতুন প্রজেক্টে available না দিলে সব শেয়ারই available
        $validated['available_shares'] = $validated['available_shares'] ?? $validated['total_shares'];

        if ($validated['available_shares'] > $validated['total_shares']) {
            return back()
                ->withInput()
                ->withErrors([
                    'available_shares' => 'Available quantity cannot exceed total quantity.',
                ]);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        } else {
            unset($validated['image']);
        }

        $validated = $this->syncLegacyColumns($validated);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Project::create($validated);

        return redirect()
            ->route('super-admin.projects.index')
            ->with('success', 'Share product created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['company', 'projectCategory', 'projectTemplate']);

        return view('super-admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('super-admin.projects.edit', array_merge(
            $this->formData(),
            compact('project')
        ));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request);

        $newImage = null;
        $oldImage = $project->image;

        try {
            DB::transaction(function () use ($request, $project, &$validated, &$newImage) {
                // শেয়ার কেনা-বেচার সাথে race ঠেকাতে row lock
                $locked = Project::whereKey($project->id)->lockForUpdate()->firstOrFail();

                // বিক্রি হওয়া শেয়ার = আগের total - আগের available
                $sold = max(0, $locked->total_shares - $locked->available_shares);

                if ($validated['total_shares'] < $sold) {
                    throw ValidationException::withMessages([
                        'total_shares' => "Total shares cannot be less than already sold shares ({$sold}).",
                    ]);
                }

                // available_shares ফর্ম থেকে নেওয়া হবে না, সবসময় হিসাব করা হবে
                $validated['available_shares'] = $validated['total_shares'] - $sold;

                if ($request->hasFile('image')) {
                    $newImage = $request->file('image')->store('projects', 'public');
                    $validated['image'] = $newImage;
                } else {
                    unset($validated['image']);
                }

                $validated = $this->syncLegacyColumns($validated);

                // নাম বদলালে তবেই slug বদলাবে
                if ($validated['name'] !== $locked->name) {
                    $validated['slug'] = $this->generateUniqueSlug($validated['name'], $locked->id);
                }

                $validated['sort_order'] = $validated['sort_order'] ?? 0;
                $validated['is_featured'] = $request->boolean('is_featured');
                $validated['is_active'] = $request->boolean('is_active');

                $locked->update($validated);
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $e;
        }

        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('super-admin.projects.index')
            ->with('success', 'Share product updated successfully.');
    }

    public function destroy(Project $project)
    {
        $sold = $project->total_shares - $project->available_shares;

        if ($sold > 0) {
            return back()->with(
                'error',
                'This product cannot be deleted because shares have already been sold. Set its status to "paused" or "completed" instead.'
            );
        }

        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()
            ->route('super-admin.projects.index')
            ->with('success', 'Share product deleted successfully.');
    }

    /* ------------------------------------------------------------------ */

    private function validateProject(Request $request): array
    {
        return $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'project_category_id' => ['required', 'exists:project_categories,id'],
            'project_template_id' => ['required', 'exists:project_templates,id'],
            'name' => ['required', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'total_shares' => ['required', 'integer', 'min:1'],
            'available_shares' => ['nullable', 'integer', 'min:0'],
            'share_price' => ['required', 'numeric', 'min:1'],
            'daily_profit_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_buy_shares' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:upcoming,active,completed,paused'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * পুরোনো `category` ও `template` কলাম সিঙ্ক রাখা,
     * যাতে আগের view/query না ভাঙে।
     */
    private function syncLegacyColumns(array $data): array
    {
        $category = ProjectCategory::find($data['project_category_id']);
        $template = ProjectTemplate::find($data['project_template_id']);

        $data['category'] = $category?->slug ?? 'other';
        $data['template'] = $template?->layout_type ?? 'grid';

        return $data;
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'project';
        $slug = $base;
        $counter = 1;

        while (
            Project::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function formData(): array
    {
        return [
            'companies' => Company::where('status', 'active')->orderBy('name')->get(),
            'categories' => ProjectCategory::where('status', 'active')
                ->orderBy('sort_order')->orderBy('name')->get(),
            'templates' => ProjectTemplate::where('status', 'active')
                ->orderBy('sort_order')->orderBy('name')->get(),
        ];
    }
}