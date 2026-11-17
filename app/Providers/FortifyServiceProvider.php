<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        Fortify::authenticateUsing(function (Request $request) {

            $user = User::where(
                Fortify::username(),
                $request->input(Fortify::username())
            )->first();

            if (
                $user &&
                $user->status === 'active' &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }

            return null;
        });

        /*
        |--------------------------------------------------------------------------
        | Login View
        |--------------------------------------------------------------------------
        */

        Fortify::loginView(function () {

            return auth()->check()
                ? redirect()->route('dashboard')
                : view('auth.login');

        });

        /*
        |--------------------------------------------------------------------------
        | Registration View
        |--------------------------------------------------------------------------
        */

        Fortify::registerView(function () {

            return auth()->check()
                ? redirect()->route('dashboard')
                : view('auth.register');

        });

        /*
        |--------------------------------------------------------------------------
        | Forgot Password View
        |--------------------------------------------------------------------------
        */

        Fortify::requestPasswordResetLinkView(function () {

            return view('auth.forgot-password');

        });

        /*
        |--------------------------------------------------------------------------
        | Reset Password View
        |--------------------------------------------------------------------------
        */

        Fortify::resetPasswordView(function (Request $request) {

            return view('auth.reset-password', [
                'request' => $request,
            ]);

        });

        /*
        |--------------------------------------------------------------------------
        | Fortify Actions
        |--------------------------------------------------------------------------
        */

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::updateUserProfileInformationUsing(
            UpdateUserProfileInformation::class
        );

        Fortify::updateUserPasswordsUsing(
            UpdateUserPassword::class
        );

        Fortify::resetUserPasswordsUsing(
            ResetUserPassword::class
        );

        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        /*
        |--------------------------------------------------------------------------
        | Login Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('login', function (Request $request) {

            $throttleKey = Str::transliterate(
                Str::lower(
                    $request->input(Fortify::username())
                ) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);

        });

        /*
        |--------------------------------------------------------------------------
        | Two Factor Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('two-factor', function (Request $request) {

            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );

        });

        /*
        |--------------------------------------------------------------------------
        | Passkeys Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('passkeys', function (Request $request) {

            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId())
                . '|' . $request->ip()
            );

        });
    }
}