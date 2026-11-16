<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('projects')
            ->latest()
            ->paginate(15);

        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $inactiveCompanies = Company::where('status', 'inactive')->count();

        return view('super-admin.companies.index', compact(
            'companies',
            'totalCompanies',
            'activeCompanies',
            'inactiveCompanies'
        ));
    }

    public function create()
    {
        return view('super-admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompany($request);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('companies', 'public')
            : null;

        Company::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('super-admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('super-admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $this->validateCompany($request);

        $logoPath = $company->logo;
        $oldLogo = $company->logo;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies', 'public');
        }

        $company->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $company->id),
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        // নতুন logo সফলভাবে save হওয়ার পরেই পুরোনোটা মুছবে
        if ($request->hasFile('logo') && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        return redirect()
            ->route('super-admin.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->projects()->exists()) {
            return back()->with(
                'error',
                'This company cannot be deleted because it has associated products.'
            );
        }

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()
            ->route('super-admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    private function validateCompany(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'company';
        $slug = $base;
        $counter = 1;

        while (
            Company::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}