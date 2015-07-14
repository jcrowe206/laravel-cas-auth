<?php namespace JCrowe\LaravelCasAuth;

use Illuminate\Support\ServiceProvider;

class CasAuthServiceProvider extends ServiceProvider {


    /**
     * Bootstrapt the provider. Publish configs
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
        $this->app['guard']->extend('laravel-cas', function() {
            return $this->app->make(LaravelCasAuthGuardProvider::class);
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