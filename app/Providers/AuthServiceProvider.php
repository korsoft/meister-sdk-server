<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        /*Passport::tokensCan([
            'system-admin' => 'System Admin User',
            'client-admin' => 'Client Admin User',
            'client-user'  => 'Client User'
        ]);*/

        Passport::routes();

        //Passport::tokensExpireIn((new \DateTime())->add(new \DateInterval('PT30M')));

        //Passport::refreshTokensExpireIn((new \DateTime())->add(new \DateInterval('PT30M')));
    }
}
