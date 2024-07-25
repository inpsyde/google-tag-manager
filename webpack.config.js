/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
/**
 * External dependencies
 */
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
		'inpsyde-google-tag-manager-settings':
			'./resources/ts/inpsyde-google-tag-manager-settings',
	},
	output: {
		path: __dirname + '/assets',
		filename: '[name].js',
	},
};
