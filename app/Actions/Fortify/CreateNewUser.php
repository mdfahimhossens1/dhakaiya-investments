<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'country' => [
                'required',
                'string',
                'size:2',
            ],

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

            'referral_code_input' => [
                'nullable',
                'string',
                'exists:users,referral_code',
            ],

            'password' => $this->passwordRules(),
        ], [
            'phone.regex' => 'Enter a valid international phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'country.size' => 'Select a valid country.',
            'referral_code_input.exists' => 'Invalid referral code.',
        ])->validate();

        return DB::transaction(function () use ($input) {

            $referrer = null;

            if (!empty($input['referral_code_input'])) {
                $referrer = User::where(
                    'referral_code',
                    strtoupper(trim($input['referral_code_input']))
                )->firstOrFail();
            }

            // Generate a unique referral code
            do {
                $referralCode = strtoupper(Str::random(10));
            } while (
                User::where('referral_code', $referralCode)->exists()
            );

            $user = User::create([
                'name' => trim($input['name']),
                'country' => strtoupper($input['country']),
                'phone' => $input['phone'],
                'email' => $input['email'] ?? null,
                'password' => $input['password'],
            ]);

            $user->forceFill([
                'role' => 'user',
                'referral_code' => $referralCode,
                'referred_by_id' => $referrer?->id,
            ])->save();

            return $user;
        });
    }
}