const Encore = require( '@symfony/webpack-encore' );

Encore
	.configureBabel(null, {
		useBuiltIns: 'entry',
		corejs: '3.6'
	})
	.setOutputPath( 'assets/' )
	.setPublicPath( '/assets/' )
	.addEntry( 'inpsyde-google-tag-manager-admin', './resources/js/admin.js' )
	.copyFiles({
		'from': './resources/images',
		'to': 'images/[path][name].[ext]'
	})
	.enableSassLoader()
	.enableSourceMaps( !Encore.isProduction() )
	.cleanupOutputBeforeBuild( ['*.js', '*.css', '*.svg', '*.png'] )
	.disableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();