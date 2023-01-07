<?php

namespace MojaHedi\Auth\Providers\Auth;

use MojaHedi\Auth\Http\Auth\AuthProvider;
use Illuminate\Support\ServiceProvider;

class LdapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('ldap', function () {
            return new AuthProvider(config('auth.providers.users.model'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
