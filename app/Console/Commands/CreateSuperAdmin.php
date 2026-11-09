<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin';

    protected $description = 'Create a Super Admin account';

    public function handle(): int
    {
        $name = $this->ask('Enter Super Admin Name');

        $phone = $this->ask('Enter Phone Number (+880...)');

        $email = $this->ask('Enter Email (optional)');

        if (User::where('phone', $phone)->exists()) {
            $this->error('This phone number is already registered.');

            return self::FAILURE;
        }

        if ($email && User::where('email', $email)->exists()) {
            $this->error('This email is already registered.');

            return self::FAILURE;
        }

        if (!preg_match('/^\+[1-9][0-9]{7,14}$/', $phone)) {
            $this->error('Invalid international phone number.');

            return self::FAILURE;
        }

        $password = $this->secret('Enter Password');

        $confirmPassword = $this->secret('Confirm Password');

        if (!$password || $password !== $confirmPassword || strlen($password) < 8) {
            $this->error('Password confirmation failed or password is too short.');

            return self::FAILURE;
        }

        $referralCode = strtoupper(Str::random(10));

        while (User::where('referral_code', $referralCode)->exists()) {
            $referralCode = strtoupper(Str::random(10));
        }

        $user = User::create([
            'name' => $name,
            'country' => 'BD',
            'phone' => $phone,
            'email' => $email ?: null,
            'password' => $password,
        ]);

        $user->forceFill([
            'role' => 'super_admin',
            'referral_code' => $referralCode,
        ])->save();

        $this->info('Super Admin account created successfully!');

        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $user->name],
                ['Phone', $user->phone],
                ['Role', $user->role],
            ]
        );

        return self::SUCCESS;
    }
}