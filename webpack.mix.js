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

mix.webpackConfig({
	module: {
		rules: [
			{
				test: /\.svg$/,
				loader: 'svg-sprite-loader',
				include: path.resolve('resources/assets/js/icons'),
				options: {
					symbolId: 'icon-[name]'
				}
			}
		]
	}
});
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css').version();
