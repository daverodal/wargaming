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
            __DIR__.'/Area/AreaOne/all.css' => public_path('vendor/wargame/area/areaone/css/all.css'),
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
            __DIR__.'/TMCW/Moskow/Images' => public_path('vendor/wargame/tmcw/moskow/images'),
            __DIR__.'/TMCW/Maps' => public_path('battle-maps'),
            __DIR__.'/TMCW/Chawinda1965/Images/' => public_path('vendor/wargame/tmcw/chawinda1965/images'),
            __DIR__.'/TMCW/MartianCivilWar/Images/' => public_path('vendor/wargame/tmcw/martiancivilwar/images'),
            __DIR__.'/TMCW/RetreatOne/Images/' => public_path('vendor/wargame/tmcw/retreatone/images'),
            __DIR__.'/TMCW/Nomonhan/Images/' => public_path('vendor/wargame/tmcw/nomonhan/images'),

        ], 'tmcw');
        
        $this->publishes([
            __DIR__.'/Genre/Images' => public_path('vendor/wargame/genre/images'),
        ], 'genre');

        $this->publishes([
            __DIR__.'/SPI/ClashOverCrude/Images' => public_path('vendor/wargame/spi/clashovercrude/images'),
            __DIR__.'/SPI/FinalChapter/Images' => public_path('vendor/wargame/spi/finalchapter/images'),
            __DIR__.'/SPI/TinCans/Images' => public_path('vendor/wargame/spi/tincans/images'),
        ], 'spi');

        $this->publishes([
            __DIR__.'/NTA/Images' => public_path('vendor/wargame/nta/images')
        ]);


        $this->publishes([
            __DIR__.'/Troops/Images' => public_path('vendor/wargame/troops/images')
        ], 'troops');
        
        $this->publishes([
            __DIR__.'/TMCW/Manchuria1976/all.css' => public_path('vendor/wargame/tmcw/manchuria1976/css/all.css'),
            __DIR__.'/TMCW/Moskow/all.css' => public_path('vendor/wargame/tmcw/moskow/css/all.css'),
            __DIR__.'/TMCW/RetreatOne/all.css' => public_path('vendor/wargame/tmcw/retreatone/css/all.css'),
            __DIR__.'/TMCW/MartianCivilWar/all.css' => public_path('vendor/wargame/tmcw/martiancivilwar/css/all.css'),
            __DIR__.'/TMCW/Chawinda1965/all.css' => public_path('vendor/wargame/tmcw/chawinda1965/css/all.css'),
            __DIR__.'/TMCW/Amph/all.css' => public_path('vendor/wargame/tmcw/amph/css/all.css'),
            __DIR__.'/TMCW/Airborne/all.css' => public_path('vendor/wargame/tmcw/airborne/css/all.css'),
            __DIR__.'/TMCW/Kiev/all.css' => public_path('vendor/wargame/tmcw/kiev/css/all.css'),
            __DIR__.'/TMCW/KievCorps/all.css' => public_path('vendor/wargame/tmcw/kievcorps/css/all.css'),
            __DIR__.'/NTA/all.css' => public_path('vendor/wargame/nta/css/all.css'),
            __DIR__.'/SPI/ClashOverCrude/all.css' => public_path('vendor/wargame/spi/clashovercrude/css/all.css'),
            __DIR__.'/SPI/FinalChapter/all.css' => public_path('vendor/wargame/spi/finalchapter/css/all.css'),
            __DIR__.'/SPI/TinCans/all.css' => public_path('vendor/wargame/spi/tincans/css/all.css'),

        ], "tmcw-css");

        $this->publishes([
            __DIR__.'/Mollwitz/Aliwal1845/all.css' => public_path('vendor/wargame/mollwitz/Aliwal1845/css/all.css'),
            __DIR__.'/Mollwitz/Brandywine1777/all.css' => public_path('vendor/wargame/mollwitz/Brandywine1777/css/all.css'),
            __DIR__.'/Mollwitz/Burkersdorf/all.css' => public_path('vendor/wargame/mollwitz/Burkersdorf/css/all.css'),
            __DIR__.'/Mollwitz/Chillianwallah1849/all.css' => public_path('vendor/wargame/mollwitz/Chillianwallah1849/css/all.css'),
            __DIR__.'/Mollwitz/Dubba1843/all.css' => public_path('vendor/wargame/mollwitz/Dubba1843/css/all.css'),
            __DIR__.'/Mollwitz/Ferozesha/all.css' => public_path('vendor/wargame/mollwitz/Ferozesha/css/all.css'),
            __DIR__.'/Mollwitz/Fontenoy1745/all.css' => public_path('vendor/wargame/mollwitz/Fontenoy1745/css/all.css'),
            __DIR__.'/Mollwitz/Fraustadt1706/all.css' => public_path('vendor/wargame/mollwitz/Fraustadt1706/css/all.css'),
            __DIR__.'/Mollwitz/FreemansFarm1777/all.css' => public_path('vendor/wargame/mollwitz/FreemansFarm1777/css/all.css'),
            __DIR__.'/Mollwitz/Gadebusch1712/all.css' => public_path('vendor/wargame/mollwitz/Gadebusch1712/css/all.css'),
            __DIR__.'/Mollwitz/Germantown1777/all.css' => public_path('vendor/wargame/mollwitz/Germantown1777/css/all.css'),
            __DIR__.'/Mollwitz/Golymin1806/all.css' => public_path('vendor/wargame/mollwitz/Golymin1806/css/all.css'),
            __DIR__.'/Mollwitz/Goojerat1849/all.css' => public_path('vendor/wargame/mollwitz/Goojerat1849/css/all.css'),
            __DIR__.'/Mollwitz/Hanau1813/all.css' => public_path('vendor/wargame/mollwitz/Hanau1813/css/all.css'),
            __DIR__.'/Mollwitz/Hastenbeck/all.css' => public_path('vendor/wargame/mollwitz/gamename/css/all.css'),
            __DIR__.'/Mollwitz/Helsingborg1710/all.css' => public_path('vendor/wargame/mollwitz/Helsingborg1710/css/all.css'),
            __DIR__.'/Mollwitz/Hohenfriedeberg/all.css' => public_path('vendor/wargame/mollwitz/Hohenfriedeberg/css/all.css'),
            __DIR__.'/Mollwitz/Holowczyn1708/all.css' => public_path('vendor/wargame/mollwitz/Holowczyn1708/css/all.css'),
            __DIR__.'/Mollwitz/Jagersdorf/all.css' => public_path('vendor/wargame/mollwitz/Jagersdorf/css/all.css'),
            __DIR__.'/Mollwitz/Kesselsdorf1745/all.css' => public_path('vendor/wargame/mollwitz/Kesselsdorf1745/css/all.css'),
            __DIR__.'/Mollwitz/Klissow1702/all.css' => public_path('vendor/wargame/mollwitz/Klissow1702/css/all.css'),
            __DIR__.'/Mollwitz/Kolin1757/all.css' => public_path('vendor/wargame/mollwitz/Kolin1757/css/all.css'),
            __DIR__.'/Mollwitz/Lesnaya1708/all.css' => public_path('vendor/wargame/mollwitz/Lesnaya1708/css/all.css'),
            __DIR__.'/Mollwitz/Lobositz/all.css' => public_path('vendor/wargame/mollwitz/Lobositz/css/all.css'),
            __DIR__.'/Mollwitz/Malplaquet/all.css' => public_path('vendor/wargame/mollwitz/Malplaquet/css/all.css'),
            __DIR__.'/Mollwitz/Meanee1843/all.css' => public_path('vendor/wargame/mollwitz/Meanee1843/css/all.css'),
            __DIR__.'/Mollwitz/Minden/all.css' => public_path('vendor/wargame/mollwitz/Minden/css/all.css'),
            __DIR__.'/Mollwitz/Mollwitz/all.css' => public_path('vendor/wargame/mollwitz/Mollwitz/css/all.css'),
            __DIR__.'/Mollwitz/Montmirail1814/all.css' => public_path('vendor/wargame/mollwitz/Montmirail1814/css/all.css'),
            __DIR__.'/Mollwitz/Moodkee1845/all.css' => public_path('vendor/wargame/mollwitz/Moodkee1845/css/all.css'),
            __DIR__.'/Mollwitz/Oudenarde1708/all.css' => public_path('vendor/wargame/mollwitz/Oudenarde1708/css/all.css'),
            __DIR__.'/Mollwitz/Zorndorf/all.css' => public_path('vendor/wargame/mollwitz/Zorndorf/css/all.css'),
            ], 'mollwitz-css');

        $this->publishes([
            __DIR__.'/Mollwitz/Images' => public_path('vendor/wargame/mollwitz/images'),
            __DIR__.'/Mollwitz/Maps' => public_path('battle-maps')
        ], 'mollwitz');

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
