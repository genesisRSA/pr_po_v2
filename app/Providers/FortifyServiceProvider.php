<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\Facades\Session;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {
            return Inertia::render('Auth/Register');
        });

        Fortify::loginView(function () {
            return Inertia::render('Auth/Login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            if(Session::has('status')){
                return Inertia::render('Auth/Forgot-Password', ['props_status'=>Session::get('status')]);
            }
            return Inertia::render('Auth/Forgot-Password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/Reset-Password', ['props_email' => $request->email, 'token' => $request->token ]);
        });
    }
}
