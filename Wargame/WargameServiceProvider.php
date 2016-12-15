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
        \App\Services\WargameService::addProvider(__DIR__);

        \App\Services\WargameService::addBattleMap(__DIR__.'/Area/Maps');
        \App\Services\WargameService::addBattleMap(__DIR__.'/TMCW/Maps');
        \App\Services\WargameService::addBattleMap(__DIR__.'/Troops/Maps');
        \App\Services\WargameService::addBattleMap(__DIR__.'/Mollwitz/Maps');



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
            __DIR__.'/universal.scss' => base_path('resources/assets/sass/vendor/wargame/universal.scss')
        ], 'common-sass');

        
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
            __DIR__.'/Troops/Images' => public_path('js'),
            __DIR__.'/Troops/Maps' => public_path('battle-maps'),
            __DIR__.'/Troops/Images' => public_path('vendor/wargame/troops/images'),
        ], 'troops');
        
        $this->publishes([
            __DIR__.'/TMCW/Manchuria1976/all.css' => public_path('vendor/wargame/tmcw/css/manchuria1976.css'),
            __DIR__.'/TMCW/Moskow/all.css' => public_path('vendor/wargame/tmcw/css/moskow.css'),
            __DIR__.'/TMCW/RetreatOne/all.css' => public_path('vendor/wargame/tmcw/css/retreatone.css'),
            __DIR__.'/TMCW/MartianCivilWar/all.css' => public_path('vendor/wargame/tmcw/css/martiancivilwar.css'),
            __DIR__.'/TMCW/Chawinda1965/all.css' => public_path('vendor/wargame/tmcw/css/chawinda1965.css'),
            __DIR__.'/TMCW/Amph/all.css' => public_path('vendor/wargame/tmcw/css/amph.css'),
            __DIR__.'/TMCW/Airborne/all.css' => public_path('vendor/wargame/tmcw/css/airborne.css'),
            __DIR__.'/TMCW/Kiev/all.css' => public_path('vendor/wargame/tmcw/css/kiev.css'),
            __DIR__.'/TMCW/KievCorps/all.css' => public_path('vendor/wargame/tmcw/css/kievcorps.css'),
            __DIR__.'/NTA/all.css' => public_path('vendor/wargame/css/nta.css'),
            __DIR__.'/SPI/ClashOverCrude/all.css' => public_path('vendor/wargame/spi/css/clashovercrude.css'),
            __DIR__.'/SPI/FinalChapter/all.css' => public_path('vendor/wargame/spi/css/finalchapter.css'),
            __DIR__.'/SPI/TinCans/all.css' => public_path('vendor/wargame/spi/css/tincans.css'),

        ], "tmcw-css");

        $this->publishes([
            __DIR__.'/Mollwitz/Aliwal1845/all.css' => public_path('vendor/wargame/mollwitz/css/Aliwal1845.css'),
            __DIR__.'/Mollwitz/Brandywine1777/all.css' => public_path('vendor/wargame/mollwitz/css/Brandywine1777.css'),
            __DIR__.'/Mollwitz/Burkersdorf/all.css' => public_path('vendor/wargame/mollwitz/css/Burkersdorf.css'),
            __DIR__.'/Mollwitz/Chillianwallah1849/all.css' => public_path('vendor/wargame/mollwitz/css/Chillianwallah1849.css'),
            __DIR__.'/Mollwitz/Dubba1843/all.css' => public_path('vendor/wargame/mollwitz/css/Dubba1843.css'),
            __DIR__.'/Mollwitz/Ferozesha/all.css' => public_path('vendor/wargame/mollwitz/css/Ferozesha.css'),
            __DIR__.'/Mollwitz/Fontenoy1745/all.css' => public_path('vendor/wargame/mollwitz/css/Fontenoy1745.css'),
            __DIR__.'/Mollwitz/Fraustadt1706/all.css' => public_path('vendor/wargame/mollwitz/css/Fraustadt1706.css'),
            __DIR__.'/Mollwitz/FreemansFarm1777/all.css' => public_path('vendor/wargame/mollwitz/css/FreemansFarm1777.css'),
            __DIR__.'/Mollwitz/Gadebusch1712/all.css' => public_path('vendor/wargame/mollwitz/css/Gadebusch1712.css'),
            __DIR__.'/Mollwitz/Germantown1777/all.css' => public_path('vendor/wargame/mollwitz/css/Germantown1777.css'),
            __DIR__.'/Mollwitz/Golymin1806/all.css' => public_path('vendor/wargame/mollwitz/css/Golymin1806.css'),
            __DIR__.'/Mollwitz/Goojerat1849/all.css' => public_path('vendor/wargame/mollwitz/css/Goojerat1849.css'),
            __DIR__.'/Mollwitz/Hanau1813/all.css' => public_path('vendor/wargame/mollwitz/css/Hanau1813.css'),
            __DIR__.'/Mollwitz/Hastenbeck/all.css' => public_path('vendor/wargame/mollwitz/css/Hastenbeck.css'),
            __DIR__.'/Mollwitz/Helsingborg1710/all.css' => public_path('vendor/wargame/mollwitz/css/Helsingborg1710.css'),
            __DIR__.'/Mollwitz/Hohenfriedeberg/all.css' => public_path('vendor/wargame/mollwitz/css/Hohenfriedeberg.css'),
            __DIR__.'/Mollwitz/Holowczyn1708/all.css' => public_path('vendor/wargame/mollwitz/css/Holowczyn1708.css'),
            __DIR__.'/Mollwitz/Jagersdorf/all.css' => public_path('vendor/wargame/mollwitz/css/Jagersdorf.css'),
            __DIR__.'/Mollwitz/Kesselsdorf1745/all.css' => public_path('vendor/wargame/mollwitz/css/Kesselsdorf1745.css'),
            __DIR__.'/Mollwitz/Klissow1702/all.css' => public_path('vendor/wargame/mollwitz/css/Klissow1702.css'),
            __DIR__.'/Mollwitz/Kolin1757/all.css' => public_path('vendor/wargame/mollwitz/css/Kolin1757.css'),
            __DIR__.'/Mollwitz/Lesnaya1708/all.css' => public_path('vendor/wargame/mollwitz/css/Lesnaya1708.css'),
            __DIR__.'/Mollwitz/Lobositz/all.css' => public_path('vendor/wargame/mollwitz/css/Lobositz.css'),
            __DIR__.'/Mollwitz/Malplaquet/all.css' => public_path('vendor/wargame/mollwitz/css/Malplaquet.css'),
            __DIR__.'/Mollwitz/Meanee1843/all.css' => public_path('vendor/wargame/mollwitz/css/Meanee1843.css'),
            __DIR__.'/Mollwitz/Minden/all.css' => public_path('vendor/wargame/mollwitz/css/Minden.css'),
            __DIR__.'/Mollwitz/Mollwitz/all.css' => public_path('vendor/wargame/mollwitz/css/Mollwitz.css'),
            __DIR__.'/Mollwitz/Montmirail1814/all.css' => public_path('vendor/wargame/mollwitz/css/Montmirail1814.css'),
            __DIR__.'/Mollwitz/Moodkee1845/all.css' => public_path('vendor/wargame/mollwitz/css/Moodkee1845.css'),
            __DIR__.'/Mollwitz/Oudenarde1708/all.css' => public_path('vendor/wargame/mollwitz/css/Oudenarde1708.css'),
            __DIR__.'/Mollwitz/Zorndorf/all.css' => public_path('vendor/wargame/mollwitz/css/Zorndorf.css'),
            __DIR__.'/Mollwitz/Gemauerthof1705/all.css' => public_path('vendor/wargame/mollwitz/css/Gemauerthof1705.css'),
            __DIR__.'/Mollwitz/LaRothiere1814/all.css' => public_path('vendor/wargame/mollwitz/css/LaRothiere1814.css'),
            __DIR__.'/Mollwitz/Jakobovo1812/all.css' => public_path('vendor/wargame/mollwitz/css/Jakobovo1812.css'),
            __DIR__.'/Mollwitz/Vimeiro1808/all.css' => public_path('vendor/wargame/mollwitz/css/Vimeiro1808.css'),
            __DIR__.'/Mollwitz/Maloyaroslavets1812/all.css' => public_path('vendor/wargame/mollwitz/css/Maloyaroslavets1812.css'),
            __DIR__.'/Mollwitz/Heilsberg1807/all.css' => public_path('vendor/wargame/mollwitz/css/Heilsberg1807.css'),
            __DIR__.'/Mollwitz/BarSurAube1814/all.css' => public_path('vendor/wargame/mollwitz/css/BarSurAube1814.css'),


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
