<?php namespace JCrowe\LaravelCasAuth;

use Illuminate\Support\ServiceProvider;
use JCrowe\LaravelCasAuth\LaravelCasUserProvider as UserProvider;

class CasAuthServiceProvider extends ServiceProvider {


    /**
     * Bootstrap the provider. Publish configs
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/laravel-cas.php' => config_path('laravel-cas.php')
        ]);
    }



    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['auth']->extend('laravel-cas', function() {
            return new LaravelCasAuthGuardProvider(
                new UserProvider(),
                $this->app->make(\Symfony\Component\HttpFoundation\Session\SessionInterface::class),
                $this->app->make(\Symfony\Component\HttpFoundation\Request::class)
            );
        });
    }


    /**
     * Get the service provider by the provider
     *
     * @return array
     */
    public function provides()
    {
        return [
            'laravel-cas'
        ];
    }


}