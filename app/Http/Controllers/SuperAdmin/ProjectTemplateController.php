<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectTemplateController extends Controller
{
    public function index()
    {
        $templates = ProjectTemplate::withCount('projects')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);

        $totalTemplates = ProjectTemplate::count();
        $activeTemplates = ProjectTemplate::where('status', 'active')->count();
        $inactiveTemplates = ProjectTemplate::where('status', 'inactive')->count();
        $defaultTemplate = ProjectTemplate::where('is_default', true)->first();
        $totalProjects = Project::whereNotNull('project_template_id')->count();

        return view('super-admin.project-templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'inactiveTemplates',
            'defaultTemplate',
            'totalProjects'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTemplate($request);
        $isDefault = $request->boolean('is_default');

        if ($isDefault && $validated['status'] !== 'active') {
            return back()
                ->withInput()
                ->withErrors([
                    'is_default' => 'An inactive template cannot be the default template.',
                ]);
        }

        $imagePath = $request->hasFile('preview_image')
            ? $request->file('preview_image')->store('project-templates', 'public')
            : null;

        $slug = $this->generateUniqueSlug($validated['name']);

        try {
            DB::transaction(function () use ($validated, $slug, $imagePath, $isDefault) {
                if ($isDefault) {
                    ProjectTemplate::where('is_default', true)
                        ->update(['is_default' => false]);
                }

                ProjectTemplate::create([
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'layout_type' => $validated['layout_type'],
                    'preview_image' => $imagePath,
                    'description' => $validated['description'] ?? null,
                    'settings' => $validated['settings'] ?? null,
                    'is_default' => $isDefault,
                    'status' => $validated['status'],
                    'sort_order' => $validated['sort_order'] ?? 0,
                ]);
            });
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        return redirect()
            ->route('super-admin.project-templates.index')
            ->with('success', 'Project template created successfully.');
    }

    public function update(Request $request, ProjectTemplate $projectTemplate)
    {
        $validated = $this->validateTemplate($request);

        // এখন যেটা default, সেটা এখান থেকে default-মুক্ত করা যাবে না
        // (অন্য template-কে default করলে এটা নিজে থেকে বাদ পড়বে)
        $isDefault = $projectTemplate->is_default ? true : $request->boolean('is_default');

        if ($isDefault && $validated['status'] !== 'active') {
            return back()
                ->withInput()
                ->withErrors([
                    'status' => 'The default template must stay active. Make another template default first.',
                ]);
        }

        $oldImage = $projectTemplate->preview_image;
        $imagePath = $oldImage;

        if ($request->hasFile('preview_image')) {
            $imagePath = $request->file('preview_image')
                ->store('project-templates', 'public');
        }

        $slug = $this->generateUniqueSlug($validated['name'], $projectTemplate->id);

        try {
            DB::transaction(function () use (
                $validated,
                $projectTemplate,
                $slug,
                $imagePath,
                $isDefault
            ) {
                if ($isDefault) {
                    ProjectTemplate::where('is_default', true)
                        ->where('id', '!=', $projectTemplate->id)
                        ->update(['is_default' => false]);
                }

                $projectTemplate->update([
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'layout_type' => $validated['layout_type'],
                    'preview_image' => $imagePath,
                    'description' => $validated['description'] ?? null,
                    'settings' => $validated['settings'] ?? null,
                    'is_default' => $isDefault,
                    'status' => $validated['status'],
                    'sort_order' => $validated['sort_order'] ?? 0,
                ]);
            });
        } catch (\Throwable $e) {
            // DB fail করলে নতুন আপলোড করা ছবি মুছে দেওয়া
            if ($request->hasFile('preview_image') && $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        // সব ঠিকঠাক save হওয়ার পরেই পুরোনো ছবি মুছবে
        if ($request->hasFile('preview_image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        // layout_type বদলালে প্রজেক্টের পুরোনো `template` কলাম সিঙ্ক
        $projectTemplate->projects()->update(['template' => $projectTemplate->layout_type]);

        return redirect()
            ->route('super-admin.project-templates.index')
            ->with('success', 'Project template updated successfully.');
    }

    public function destroy(ProjectTemplate $projectTemplate)
    {
        if ($projectTemplate->is_default) {
            return redirect()
                ->route('super-admin.project-templates.index')
                ->with('error', 'The default template cannot be deleted. Make another template default first.');
        }

        if ($projectTemplate->projects()->exists()) {
            return redirect()
                ->route('super-admin.project-templates.index')
                ->with('error', 'This template cannot be deleted because projects are assigned to it.');
        }

        if ($projectTemplate->preview_image) {
            Storage::disk('public')->delete($projectTemplate->preview_image);
        }

        $projectTemplate->delete();

        return redirect()
            ->route('super-admin.project-templates.index')
            ->with('success', 'Project template deleted successfully.');
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'layout_type' => ['required', Rule::in(['grid', 'horizontal', 'featured', 'large'])],
            'preview_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'is_default' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'template';
        $slug = $base;
        $counter = 1;

        while (
            ProjectTemplate::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}