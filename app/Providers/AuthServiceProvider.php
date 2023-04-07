<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Video;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        Video::class => VideoPolicy::class,
        User::class => UserPolicy::class,
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
        $this->registerGates();
    }

    private function registerGates()
    {
        Gate::before(function ($user, $ability){
            if ($user->isAdmin() and $ability != 'republish')
            {
                return true;
            }
        });
    }
}
