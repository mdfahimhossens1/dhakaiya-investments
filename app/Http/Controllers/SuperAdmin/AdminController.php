<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->latest()
            ->paginate(15);

        return view('super-admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('super-admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => [
                'required',
                'string',
                'regex:/^\+[1-9][0-9]{7,14}$/',
                Rule::unique('users', 'phone'),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {

            do {
                $referralCode = strtoupper(Str::random(10));
            } while (User::where('referral_code', $referralCode)->exists());

            $admin = User::create([
                'name' => $validated['name'],
                'country' => 'BD',
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'],
            ]);

            $admin->forceFill([
                'role' => 'admin',
                'referral_code' => $referralCode,
            ])->save();
        });

        return redirect()
            ->route('super-admin.admins.index')
            ->with('success', 'Admin created successfully.');
    }
}