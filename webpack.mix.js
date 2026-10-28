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


const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/store/index.js', 'public/js')
    .css('resources/css/app.css', 'public/css').vue();
