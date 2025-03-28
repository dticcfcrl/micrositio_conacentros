const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

/*
mix.js('resources/js/app.js', 'public/js/')
    .js('resources/js/hope-ui.js', 'public/js/')
    .js('resources/js/libs.min.js', 'public/js/')
    .js('resources/js/leaflet.js', 'public/js/')
    .sass('resources/scss/custom.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/scss/hope-ui.scss', 'public/css')
    .css('resources/css/leaflet.css', 'public/css')
    .css('resources/css/libs.min.css', 'public/css')
    .options({
        processCssUrls: false
    });
*/

mix.js('resources/js/app.js', 'public/js/')
    .js('resources/js/hope-ui.js', 'public/js/')
    .js('resources/js/libs.min.js', 'public/js/')
    .js('resources/js/leaflet.js', 'public/js/')
    .js('resources/js/OpenLayers.js', 'public/js/')
    .sass('resources/scss/custom.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/scss/hope-ui.scss', 'public/css')
    .sass('resources/scss/dark.scss', 'public/css')
    .sass('resources/scss/rtl.scss', 'public/css')
    .sass('resources/scss/customizer.scss', 'public/css')
    .css('resources/css/leaflet.css', 'public/css')
    .css('resources/css/libs.min.css', 'public/css')
    .options({
        processCssUrls: false
    });


/**
 
mix.js('resources/js/app.js', 'public/js/')
    .js('resources/js/libs.js', 'public/js/libs.min.js')
    .js('resources/js/hope-ui.js', 'public/js/')
    .js('resources/js/libs.min.js', 'public/js/')
    .sass('resources/sass/libs.scss','public/css/libs.min.css')
    .sass('resources/scss/custom.scss', 'public/css')
    .sass('public/scss/hope-ui.scss', 'public/css') 
    .sass('public/scss/dark.scss', 'public/css')
    .sass('public/scss/rtl.scss', 'public/css')
    .sass('public/scss/customizer.scss', 'public/css')
    .css('resources/css/leaflet.css', 'public/css')
    .css('resources/css/libs.min.css', 'public/css')
    .options({
        processCssUrls: false
    });
 */

    
    