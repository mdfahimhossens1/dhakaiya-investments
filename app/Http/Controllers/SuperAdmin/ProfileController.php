<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display profile page.
     */
    public function index()
    {
        return view('super-admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Edit profile page.
     */
    public function edit()
    {
        return view('super-admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('super-admin.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}