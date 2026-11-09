<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonalInformationController extends Controller
{
    private function countries(): array
    {
        return [
            'BD' => 'Bangladesh',
            'IN' => 'India',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'SA' => 'Saudi Arabia',
            'AE' => 'United Arab Emirates',
        ];
    }

    /**
     * Display personal information.
     */
    public function index()
    {
        $user = auth()->user();

        $countries = $this->countries();

        return view('user.profile.personal-information', compact(
            'user',
            'countries'
        ));
    }

    /**
     * Update personal information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $countries = $this->countries();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
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

            'country' => [
                'required',
                'string',
                Rule::in(array_keys($countries)),
            ],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Name must contain at least 2 characters.',
            'phone.regex' => 'Enter a valid international phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
            'country.in' => 'Please select a valid country.',
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('user.personal-information.index')
            ->with('success', 'Your personal information has been updated successfully.');
    }
}