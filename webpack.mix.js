let mix = require('laravel-mix');
let webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.webpackConfig({
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            'window.$': 'jquery',
            Popper: ['popper.js', 'default']
        })
    ], module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                exclude: /node_modules/,
            },{
                test: /\.(m4a)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {}
                    }
                ]
            }
        ],
    },
    resolve: {
        symlinks: false,
        extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
    },
});
mix.setPublicPath("../game-dispatcher/public/vendor/");
mix.copyDirectory('Wargame/wargame-helpers/audio','../game-dispatcher/public/assets/audio')
    .copyDirectory('Wargame/wargame-helpers/unit-images', '../game-dispatcher/public/assets/unit-images')
    .copyDirectory('Wargame/wargame-helpers/map-symbols', '../game-dispatcher/public/assets/map-symbols')
    .copyDirectory('Wargame/Genre', '../game-dispatcher/public/vendor/wargame/genre')
    .copyDirectory('Wargame/Mollwitz/Images', '../game-dispatcher/public/vendor/wargame/mollwitz/images')
mix.sass('Wargame/Mollwitz/all-nations-colors.scss', 'css/wargame/all-nations-colors.css')
mix.sass('Wargame/TMCW/commonPlay.scss', 'css/wargame/common-play.css');
mix.sass('Wargame/TMCW/Nomonhan/all.scss', 'css/wargame/nomonhan.css')
mix.sass('Wargame/TMCW/RetreatOne/all.scss', 'css/wargame/retreatOne.css')
mix.sass('Wargame/TMCW/Airborne/all.scss', 'css/wargame/airborne.css');
mix.sass('Wargame/TMCW/Amph/all.scss', 'css/wargame/amph.css');
mix.sass('Wargame/TMCW/MartianCivilWar/all.scss', 'css/wargame/martiancivilwar.css');
mix.sass('Wargame/TMCW/KievCorps/all.scss', 'css/wargame/kievCorps.css');
mix.sass('Wargame/TMCW/Manchuria1976/all.scss', 'css/wargame/manchuria1976.css');
mix.sass('Wargame/TMCW/Chawinda1965/all.scss', 'css/wargame/chawinda1965.css');
mix.sass('Wargame/TMCW/EastWest/all.scss', 'css/wargame/eastwest.css');
mix.sass('Wargame/TMCW/Collapse/all.scss', 'css/wargame/collapse.css');
mix.sass('Wargame/TMCW/NorthVsSouth/all.scss', 'css/wargame/northvssouth.css');
mix.sass('Wargame/TMCW/Moskow/all.scss', 'css/wargame/moskow.css');
mix.sass('Wargame/Area/AreaOne/areaone.scss', 'css/wargame/areaone.css');

mix.sass('Wargame/NTA/all.scss', 'css/wargame/nta.css');
mix.sass('Wargame/Vu/all.scss', 'css/wargame/vu.css');




mix.js('Wargame/wargame.js', 'javascripts/wargame/wargame.js');
// mix.js('Wargame/TMCW/RetreatOne/retreatOne.js', 'javascripts/wargame/retreatOne.js');
mix.js('Wargame/TMCW/KievCorps/kiev-corps.js', 'javascripts/wargame/kievCoprs.js');
mix.js('Wargame/TMCW/Amph/amph.js', 'javascripts/wargame/amph.js');
mix.js('Wargame/TMCW/Airborne/airborne.js', 'javascripts/wargame/airborne.js');
mix.js('Wargame/Vu/vu.js', 'javascripts/wargame/vu.js');
mix.js('Wargame/TMCW/Moskow/moskow.js', 'javascripts/wargame/moskow.js');
mix.js('Wargame/TMCW/Collapse/collapse.js', 'javascripts/wargame/collapse.js');
mix.js('Wargame/TMCW/NorthVsSouth/northvssouth.js', 'javascripts/wargame/northvssouth.js');
mix.js('Wargame/TMCW/EastWest/east-west.js', 'javascripts/wargame/east-west.js');
mix.js('Wargame/TMCW/Manchuria1976/manchuria1976.js', 'javascripts/wargame/manchuria1976.js');
mix.js('Wargame/Area/AreaOne/area-one.js', 'javascripts/wargame/area-one.js');


mix.setResourceRoot("/vendor/").sass('Wargame/Mollwitz/horse-musket.scss', 'css/wargame/horse-musket.css')
