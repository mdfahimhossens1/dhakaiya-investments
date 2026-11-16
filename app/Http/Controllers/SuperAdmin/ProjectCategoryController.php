<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::withCount('projects')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);

        $totalCategories = ProjectCategory::count();
        $activeCategories = ProjectCategory::where('status', 'active')->count();
        $inactiveCategories = ProjectCategory::where('status', 'inactive')->count();
        $totalProjects = Project::whereNotNull('project_category_id')->count();

        return view('super-admin.project-categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'totalProjects'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        ProjectCategory::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('super-admin.project-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $validated = $this->validateCategory($request);

        $projectCategory->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $projectCategory->id),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        // slug বদলালে প্রজেক্টের পুরোনো `category` কলামও সিঙ্ক থাকবে
        $projectCategory->projects()->update(['category' => $projectCategory->slug]);

        return redirect()
            ->route('super-admin.project-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        if ($projectCategory->projects()->exists()) {
            return redirect()
                ->route('super-admin.project-categories.index')
                ->with('error', 'This category cannot be deleted because projects are assigned to it.');
        }

        $projectCategory->delete();

        return redirect()
            ->route('super-admin.project-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $counter = 1;

        while (
            ProjectCategory::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}