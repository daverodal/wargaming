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
        \App\Services\WargameService::addProvider(__DIR__.'/Mollwitz');
        \App\Services\WargameService::addProvider(__DIR__.'/TMCW');
        \App\Services\WargameService::addProvider(__DIR__.'/Area');
        \App\Services\WargameService::addProvider(__DIR__.'/Additional');
        \App\Services\WargameService::addProvider(__DIR__.'/NTA');

        \App\Services\WargameService::addBattleMap(__DIR__.'/Area/Maps');
        \App\Services\WargameService::addBattleMap(__DIR__.'/TMCW/Maps');
        \App\Services\WargameService::addBattleMap(__DIR__.'/Mollwitz/Maps');



        $this->loadViewsFrom(__DIR__, 'wargame');

        $this->publishes([
            __DIR__.'/Area/Maps' => public_path('battle-maps'),
        ], 'area');
        
        $this->publishes([
            __DIR__.'/TMCW/Manchuria1976/Images/' => public_path('vendor/wargame/tmcw/manchuria1976/images'),
            __DIR__.'/TMCW/Airborne/Images' => public_path('vendor/wargame/tmcw/airborne/images'),
            __DIR__.'/TMCW/Kiev/Images' => public_path('vendor/wargame/tmcw/kiev/images'),
            __DIR__.'/TMCW/Kiev/Fonts' => public_path('vendor/wargame/tmcw/kiev/fonts'),
            __DIR__.'/TMCW/KievCorps/Images' => public_path('vendor/wargame/tmcw/kievcorps/images'),
            __DIR__.'/TMCW/Amph/Images/' => public_path('vendor/wargame/tmcw/amph/images'),
            __DIR__.'/TMCW/KievCorps/Fonts' => public_path('vendor/wargame/tmcw/kievcorps/fonts'),
            __DIR__ . '/TMCW/Moskow/Images' => public_path('vendor/wargame/tmcw/moskow/images'),
            __DIR__.'/TMCW/Chawinda1965/Images/' => public_path('vendor/wargame/tmcw/chawinda1965/images'),
            __DIR__.'/TMCW/MartianCivilWar/Images/' => public_path('vendor/wargame/tmcw/martiancivilwar/images'),
            __DIR__.'/TMCW/RetreatOne/Images/' => public_path('vendor/wargame/tmcw/retreatone/images'),
            __DIR__.'/TMCW/Nomonhan/Images/' => public_path('vendor/wargame/tmcw/nomonhan/images'),
            __DIR__ . '/TMCW/Collapse/Images/' => public_path('vendor/wargame/tmcw/collapse/images'),

            __DIR__.'/TMCW/Maps' => public_path('battle-maps'),
        ], 'tmcw');



        $this->publishes([
            __DIR__.'/universal.scss' => base_path('resources/assets/sass/vendor/wargame/universal.scss')
        ], 'common-sass');

        $this->publishes([
            __DIR__.'/wargame-helpers/unit-images' => public_path('assets/unit-images'),
        ], 'unit-images');

        $this->publishes([
            __DIR__.'/Genre/Images' => public_path('vendor/wargame/genre/images'),
        ], 'genre');


        $this->publishes([
            __DIR__.'/Mollwitz/Images' => public_path('vendor/wargame/mollwitz/images'),
            __DIR__.'/Mollwitz/Maps' => public_path('battle-maps')
        ], 'mollwitz-maps-images');

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
