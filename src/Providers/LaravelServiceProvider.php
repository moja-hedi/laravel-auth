<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) 2014-2021 Sean armj <armj148@gmail.com>
 * (c) 2021 PHP Open Source Saver
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MojaHedi\Auth\Providers;


use MojaHedi\Auth\Facades\JWTAuth;
use MojaHedi\Auth\Facades\JWTFactory;
use MojaHedi\Auth\Facades\JWTProvider;
use MojaHedi\Auth\Http\Parser\Cookies;
use MojaHedi\Auth\Http\Parser\RouteParams;

class LaravelServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {


        $this->aliasMiddleware();

        $this->extendAuthGuard();

        $this->app['armj.jwt.parser']->addParser([
            new RouteParams(),
            new Cookies($this->app->make('config')->get('jwt.decrypt_cookies')),
        ]);

        if (isset($_SERVER['LARAVEL_OCTANE'])) {
            $clear = function () {
                JWTAuth::clearResolvedInstances();
                JWTFactory::clearResolvedInstances();
                JWTProvider::clearResolvedInstances();
            };

        }
    }

    /**
     * {@inheritdoc}
     */
    protected function registerStorageProvider()
    {
        $this->app->singleton('armj.jwt.provider.storage', function ($app) {
            $instance = $this->getConfigInstance($app, 'providers.storage');

            if (method_exists($instance, 'setLaravelVersion')) {
                $instance->setLaravelVersion($this->app->version());
            }

            return $instance;
        });
    }

    /**
     * Alias the middleware.
     *
     * @return void
     */
    protected function aliasMiddleware()
    {
        $router = $this->app['router'];

        $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';

        foreach ($this->middlewareAliases as $alias => $middleware) {
            $router->$method($alias, $middleware);
        }
    }
}
