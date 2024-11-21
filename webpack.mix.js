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

mix.js('resources/js/admin.js', 'public/js')
    .js('resources/js/search.js', 'public/js')
    .js('resources/js/selectpicker.js', 'public/js')
    .js('resources/js/functions.js', 'public/js')
    .js('resources/js/datatables.js', 'public/js')
    .sass('resources/sass/bootstrap.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css')
    .sass('resources/sass/selectpicker.scss', 'public/css')
    .sass('resources/sass/datatables.scss', 'public/css')
    .sass('resources/sass/pages/admin-auth.scss', 'public/css/pages')
    .copyDirectory('resources/js/datatables', 'public/js/datatables')
    .copyDirectory('resources/js/datepicker', 'public/js/datepicker')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

if (mix.inProduction()) {
    mix.version();
}