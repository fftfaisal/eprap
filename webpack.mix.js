const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js').vue()
    // .sass('resources/sass/app.scss', 'public/css')
    // .sourceMaps();

mix.js('resources/js/learn-study-material.js', 'frontend/js').vue();
mix.js('resources/js/create-study-material.js', 'backend/js').vue();
mix.js('resources/js/update-study-material.js', 'backend/js').vue();

mix.js('resources/js/profile-verification.js', 'backend/js').vue().sourceMaps();


