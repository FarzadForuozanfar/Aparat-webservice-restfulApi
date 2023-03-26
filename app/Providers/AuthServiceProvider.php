<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Passport::routes();
        Passport::tokensExpireIn(now()->addDays(config('auth.token_expiration.token')));
        Passport::refreshTokensExpireIn(now()->addDays(config('auth.token_expiration.token')));
        //Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
