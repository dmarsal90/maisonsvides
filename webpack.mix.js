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

mix.js([
	'resources/js/app.js',
	'node_modules/datatables.net/js/jquery.dataTables.js',
	'node_modules/datatables.net-responsive/js/dataTables.responsive.js',
	'node_modules/timepicker/jquery.timepicker.js',
	'resources/js/waitMe.js',
	'resources/js/config.js',
	'resources/js/map.js',
	], 'public/js/app.js')
	.sass('resources/sass/app.scss', 'public/css')
	.sourceMaps();

mix.js('resources/js/script.js', 'public/js/script.js');



// Custom CSS and JS to certain functionalities
mix.less('resources/less/style.less', 'public/css/');

// Vendor

mix.scripts([
	'node_modules/fullcalendar/main.js',
	'node_modules/sweetalert2/dist/sweetalert2.all.js',
	'node_modules/js-datepicker/dist/datepicker.min.js',
	'node_modules/js-cookie/src/js.cookie.js',
	], 'public/js/vendor.js')
	.less('resources/less/vendor.less', 'public/css/');
