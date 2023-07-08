<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Schema;
use \Auth;
use \App\Models\User;

class AppServiceProvider extends ServiceProvider
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
        if (env('APP_AUTOLOGIN', false)){
            $user = User::find(3);
            if ($user) {
                Auth::login($user);
            }
        }
    }
}
