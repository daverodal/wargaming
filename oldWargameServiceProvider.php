<?php

use Illuminate\Support\ServiceProvider;

class WargameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Services\WargameService::$viewBase = "Wargame";
        $this->loadViewsFrom(__DIR__.'/Wargame/', 'wargame');
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
