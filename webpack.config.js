/**
 * Custom webpack config extending wp-scripts defaults.
 *
 * Overrides SVG handling for meteocons directory so animated SVGs
 * are emitted as separate files (not inlined as base64 data URIs).
 */
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const meteoconsPath = path.resolve( __dirname, 'block/src/icons/meteocons' );

module.exports = {
	...defaultConfig,
	module: {
		...defaultConfig.module,
		rules: [
			// Meteocons SVGs: emit as separate files to preserve animations.
			{
				test: /\.svg$/,
				include: meteoconsPath,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name].[contenthash:8][ext]',
				},
			},
			// Keep all other rules, but exclude meteocons from the default SVG rules.
			...defaultConfig.module.rules.map( ( rule ) => {
				if ( rule.test && rule.test.toString() === '/\\.svg$/' ) {
					return {
						...rule,
						exclude: meteoconsPath,
					};
				}
				return rule;
			} ),
		],
	},
};
