<?php
namespace WargameII;
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
        \App\Services\WargameService::$viewBase = "WargameII";
        \App\Services\WargameService::addProvider(__DIR__.'/ThrityYears');


        $this->loadViewsFrom(__DIR__, 'wargame');

//        $this->publishes([
//            __DIR__.'/TMCW/Manchuria1976/Images/' => public_path('vendor/wargame/tmcw/manchuria1976/images'),
//            __DIR__.'/TMCW/Airborne/Images' => public_path('vendor/wargame/tmcw/airborne/images'),
//            __DIR__.'/TMCW/Kiev/Images' => public_path('vendor/wargame/tmcw/kiev/images'),
//            __DIR__.'/TMCW/Kiev/Fonts' => public_path('vendor/wargame/tmcw/kiev/fonts'),
//            __DIR__ . '/TMCW/Kiev1941/Images' => public_path('vendor/wargame/tmcw/kiev1941/images'),
//            __DIR__.'/TMCW/Amph/Images/' => public_path('vendor/wargame/tmcw/amph/images'),
//            __DIR__ . '/TMCW/Kiev1941/Fonts' => public_path('vendor/wargame/tmcw/kiev1941/fonts'),
//            __DIR__.'/TMCW/Chawinda1965/Images/' => public_path('vendor/wargame/tmcw/chawinda1965/images'),
//            __DIR__.'/TMCW/MartianCivilWar/Images/' => public_path('vendor/wargame/tmcw/martiancivilwar/images'),
//            __DIR__.'/TMCW/RetreatOne/Images/' => public_path('vendor/wargame/tmcw/retreatone/images'),
//            __DIR__.'/TMCW/Nomonhan/Images/' => public_path('vendor/wargame/tmcw/nomonhan/images'),
//            __DIR__.'/TMCW/TinCans1916/Images/' => public_path('vendor/wargame/tmcw/tincans1915/images'),
//            __DIR__ . '/Additional/Collapse/Images/' => public_path('vendor/wargame/additional/collapse/images'),
//            __DIR__ . '/Additional/EastWest/Images' => public_path('vendor/wargame/additional/eastwest/images'),
//            __DIR__ . '/Additional/Moskow/Images' => public_path('vendor/wargame/additional/moskow/images'),
//            __DIR__.'/TMCW/Maps' => public_path('battle-maps'),
//        ], 'tmcw');
//
//
//
//        $this->publishes([
//            __DIR__.'/universal.scss' => base_path('resources/assets/sass/vendor/wargame/universal.scss')
//        ], 'common-sass');
//
//        $this->publishes([
//            __DIR__.'/wargame-helpers/unit-images' => public_path('assets/unit-images'),
//        ], 'unit-images');
//
//        $this->publishes([
//            __DIR__.'/Genre/Images' => public_path('vendor/wargame/genre/images'),
//        ], 'genre');
//
//
//        $this->publishes([
//            __DIR__.'/Mollwitz/Images' => public_path('vendor/wargame/mollwitz/images'),
//            __DIR__.'/Mollwitz/Maps' => public_path('battle-maps')
//        ], 'mollwitz-maps-images');

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
