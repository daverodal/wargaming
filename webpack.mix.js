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
    ]});
mix.setPublicPath("../game-dispatcher/public/vendor/");
mix.sass('Wargame/Mollwitz/all-nations-colors.scss', 'css/wargame/all-nations-colors.css')
// mix.sass('Wargame/TMCW/RetreatOne/all.scss', 'css/wargame/retreatOne.css')
// mix.sass('Wargame/TMCW/Airborne/all.scss', 'css/wargame/amph.css');
// mix.sass('Wargame/TMCW/Amph/all.scss', 'css/wargame/airborne.css');
// mix.sass('Wargame/TMCW/KievCorps/all.scss', 'css/wargame/kievCorps.css');
// mix.sass('Wargame/TMCW/Manchuria1976/all.scss', 'css/wargame/manchuria1976.css');
// mix.sass('Wargame/TMCW/Chawinda1965/all.scss', 'css/wargame/chawinda1965.css');

mix.js('wargame.js', 'javascripts/wargame/wargame.js');
// mix.js('Wargame/TMCW/RetreatOne/retreatOne.js', 'javascripts/wargame/retreatOne.js');
// mix.js('Wargame/TMCW/KievCorps/kievCorps.js', 'javascripts/wargame/kievCoprs.js');
// mix.js('Wargame/TMCW/Amph/amph.js', 'javascripts/wargame/amph.js');
// mix.js('Wargame/TMCW/Airborne/airborne.js', 'javascripts/wargame/airborne.js');
// mix.js('Wargame/TMCW/Moskow/moskow.js', 'javascripts/wargame/moskow.js');


mix.setResourceRoot("/vendor/").sass('Wargame/Mollwitz/horse-musket.scss', 'css/wargame/horse-musket.css')