let mix = require('laravel-mix');

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

//mix.js('resources/assets/js/app.js', 'public/js')
//   .sass('resources/assets/sass/app.scss', 'public/css');
mix.scripts([
    'public/assets/jquery/jquery.min.js',
    'public/assets/bootstrap/js/bootstrap.bundle.min.js',
    'public/assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'public/js/adminlte/adminlte.js',
    'public/assets/jquery-mousewheel/jquery.mousewheel.js',
    'public/assets/raphael/raphael.min.js',
    'public/assets/jquery-mapael/jquery.mapael.min.js',
    'public/assets/jquery-mapael/maps/world_countries.min.js',
    'public/assets/chart.js/Chart.min.js',
    'public/js/adminlte/pages/dashboard2.js',
    'public/js/adminlte/admin.js'
], 'public/js/app.js').copyDirectory('public/assets/layer/src/theme/default/layer.css', 'public/css');


mix.scripts([
    'public/assets/jquery/jquery.min.js',
    'public/assets/layer/src/layer.js',
    'public/assets/bootstrap/js/bootstrap.min.js',
    'public/js/common.js',
    'public/assets/nice-validator/dist/jquery.validator.js?local=zh-CN'
    ], 'public/js/base.js');

mix.style([

]);