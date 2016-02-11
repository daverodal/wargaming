<?php
namespace Wargame;
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
        $this->loadViewsFrom(__DIR__, 'wargame');


        $this->publishes([
            __DIR__.'/TMCW/Amph/Images/' => public_path('vendor/wargame/tmcw/amph/images'),
        ], 'amph');

        $this->publishes([
            __DIR__.'/Genre/Images' => public_path('vendor/wargame/genre/images'),
        ], 'genre');

        $this->publishes([
            __DIR__.'/SPI/TinCans/Images' => public_path('vendor/wargame/spi/tincans/images'),
        ], 'tincans');


        $this->publishes([
            __DIR__.'/Mollwitz/Images' => public_path('vendor/wargame/mollwitz/images'),
        ], 'mollwitz');

        $this->publishes([
            __DIR__.'/SPI/ClashOverCrude/Images' => public_path('vendor/wargame/spi/clashovercrude/images'),
            __DIR__.'/SPI/FinalChapter/Images' => public_path('vendor/wargame/spi/finalchapter/images'),
        ], 'finalchapter');
        $this->publishes([
            __DIR__.'/TMCW/Airborne/Images' => public_path('vendor/wargame/tmcw/airborne/images'),
        ], 'airborne');
        $this->publishes([
            __DIR__.'/TMCW/Kiev/Images' => public_path('vendor/wargame/tmcw/kiev/images'),
            __DIR__.'/TMCW/Kiev/Fonts' => public_path('vendor/wargame/tmcw/kiev/fonts'),
            __DIR__.'/TMCW/Moskow/Images' => public_path('vendor/wargame/tmcw/moskow/images'),
        ], 'kiev');
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
