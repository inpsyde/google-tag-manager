const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );

const config = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,
		new CopyWebpackPlugin( {
			patterns: [ { from: './resources/images', to: './images' } ],
		} ),
	],
};

module.exports = {
	...config,
	entry: {
		'inpsyde-google-tag-manager-admin': './resources/js/admin',
	},
	output: {
		path: __dirname + '/assets',
		filename: '[name].js',
	},
};
