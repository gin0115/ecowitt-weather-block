<?php
/**
 * Ecowitt Weather Block
 *
 * @since       0.1.0
 * @version     0.1.0
 * @author      WordPress.com Special Projects
 * @license     GPL-3.0-or-later
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             PinkCrab - Ecowitt Weather Block
 * Description:             A simple block to display weather data from an Ecowitt weather station.
 * Version:                 0.1.0
 * Requires at least:       6.1
 * Requires PHP:            8.1
 * Author:                  Glynn Quelch<glynn.quelch@gmail.com>
 * License:                 GPL-3.0-or-later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pinkcrab-weather-block
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'PC_ECOWITT_WEATHER_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'PC_ECOWITT_WEATHER_DIR', plugin_dir_path( __FILE__ ) );
define( 'PC_ECOWITT_WEATHER_URL', plugin_dir_url( __FILE__ ) );

// Include the rest of the blocks plugin's files if system requirements check out.
if ( is_php_version_compatible( PC_ECOWITT_WEATHER_METADATA['RequiresPHP'] ) && is_wp_version_compatible( PC_ECOWITT_WEATHER_METADATA['RequiresWP'] ) ) {
	foreach ( glob( __DIR__ . '/includes/*.php' ) as $pc_ecowitt_weather_filename ) {
		if ( preg_match( '#/includes/_#i', $pc_ecowitt_weather_filename ) ) {
			continue; // Ignore files prefixed with an underscore.
		}

		include $fse_pilot_blocks_filename;
	}
}
