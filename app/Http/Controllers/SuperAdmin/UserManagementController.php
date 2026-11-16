<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display all platform users.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'user');

        // Search users
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', strtoupper($request->country));
        }

        // Sorting
        $sort = $request->get('sort', 'latest');

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $users = $query
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $totalUsers = User::where('role', 'user')->count();

        $totalAdmins = User::where('role', 'admin')->count();

        $totalSuperAdmins = User::where('role', 'super_admin')->count();

        $countries = User::where('role', 'user')
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('super-admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalSuperAdmins',
            'countries'
        ));
    }

    public function show(User $user)
{
    // Only allow viewing normal user accounts
    abort_unless($user->role === 'user', 404);

    return view('super-admin.users.show', compact('user'));
}

public function edit(User $user)
{
    abort_unless($user->role === 'user', 404);

    return view('super-admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    abort_unless($user->role === 'user', 404);

    $validated = $request->validate([
        'name' => ['required', 'string', 'min:2', 'max:100'],

        'country' => [
            'required',
            'string',
            'size:2',
            'regex:/^[A-Z]{2}$/',
        ],

        'phone' => [
            'required',
            'string',
            'regex:/^\+[1-9][0-9]{7,14}$/',
            Rule::unique('users', 'phone')->ignore($user->id),
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
    ]);

    $user->update($validated);

    return redirect()
        ->route('super-admin.users.show', $user->id)
        ->with('success', 'User profile updated successfully.');
}

public function updateStatus(Request $request, User $user)
{
    abort_unless($user->role === 'user', 404);

    $validated = $request->validate([
        'status' => ['required', 'in:active,inactive'],
    ]);

    $user->update([
        'status' => $validated['status'],
    ]);

    return redirect()
        ->route('super-admin.users.show', $user->id)
        ->with('success', 'User account status updated successfully.');
}

}