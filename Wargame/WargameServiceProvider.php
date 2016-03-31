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
            __DIR__.'/TMCW/Amph/all.css' => public_path('vendor/wargame/tmcw/amph/css/all.css'),
        ], 'amph');

        $this->publishes([
            __DIR__.'/TMCW/Chawinda1965/Images/' => public_path('vendor/wargame/tmcw/chawinda1965/images'),
            __DIR__.'/TMCW/Chawinda1965/all.css' => public_path('vendor/wargame/tmcw/chawinda1965/css/all.css'),
        ], 'chawinda');

        $this->publishes([
            __DIR__.'/TMCW/MartianCivilWar/Images/' => public_path('vendor/wargame/tmcw/martiancivilwar/images'),
            __DIR__.'/TMCW/MartianCivilWar/all.css' => public_path('vendor/wargame/tmcw/martiancivilwar/css/all.css'),
        ], 'martiancivilwar');
        
        $this->publishes([
            __DIR__.'/TMCW/RetreatOne/Images/' => public_path('vendor/wargame/tmcw/retreatone/images'),
            __DIR__.'/TMCW/RetreatOne/all.css' => public_path('vendor/wargame/tmcw/retreatone/css/all.css'),
        ], 'retreatone');


        $this->publishes([
            __DIR__.'/TMCW/Manchuria1976/Images/' => public_path('vendor/wargame/tmcw/manchuria1976/images'),
            __DIR__.'/TMCW/Manchuria1976/all.css' => public_path('vendor/wargame/tmcw/manchuria1976/css/all.css'),
        ], 'retreatone');
        
        $this->publishes([
            __DIR__.'/Genre/Images' => public_path('vendor/wargame/genre/images'),
        ], 'genre');

        $this->publishes([
            __DIR__.'/SPI/TinCans/Images' => public_path('vendor/wargame/spi/tincans/images'),
        ], 'tincans');


        $this->publishes([
            __DIR__.'/Mollwitz/Images' => public_path('vendor/wargame/mollwitz/images'),
            __DIR__.'/Mollwitz/FreemansFarm1777/Images' => public_path('vendor/wargame/mollwitz/images'),
        ], 'mollwitz');

        $this->publishes([
            __DIR__.'/SPI/ClashOverCrude/Images' => public_path('vendor/wargame/spi/clashovercrude/images'),
            __DIR__.'/SPI/FinalChapter/Images' => public_path('vendor/wargame/spi/finalchapter/images'),
            __DIR__.'/SPI/ClashOverCrude/all.css' => public_path('vendor/wargame/spi/clashovercrude/css/all.css'),
        ], 'finalchapter');
        $this->publishes([
            __DIR__.'/TMCW/Airborne/Images' => public_path('vendor/wargame/tmcw/airborne/images'),
            __DIR__.'/TMCW/Airborne/all.css' => public_path('vendor/wargame/tmcw/airborne/css/all.css'),
        ], 'airborne');
        $this->publishes([
            __DIR__.'/TMCW/Kiev/Images' => public_path('vendor/wargame/tmcw/kiev/images'),
            __DIR__.'/TMCW/Kiev/Fonts' => public_path('vendor/wargame/tmcw/kiev/fonts'),
            __DIR__.'/TMCW/Kiev/all.css' => public_path('vendor/wargame/tmcw/kiev/css/all.css'),

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
