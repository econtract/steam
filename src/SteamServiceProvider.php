<?php namespace Econtract\Steam;


use Illuminate\Support\ServiceProvider;

class SteamServiceProvider extends ServiceProvider {

    protected $defer = false;


    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            SteamService::class,
            function($app)
            {
                return new SteamService( $_SERVER[ 'STEAM_API_URL' ], $_SERVER[ 'STEAM_API_KEY' ] );
            }
        );

        $this->app->alias(SteamService::class, 'Steam');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return array('Steam');
    }

    public function boot()
    {
        // ...
    }

}
