<?php
/**
 * Ecowitt Weather Block
 *
 * @since       0.1.0
 * @version     1.0.0-RC1
 * @author      WordPress.com Special Projects
 * @license     GPL-3.0-or-later
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             PinkCrab - Ecowitt Weather Block
 * Description:             A simple block to display weather data from an Ecowitt weather station.
 * Version:                 1.0.0-RC1
 * Requires at least:       6.1
 * Requires PHP:            8.1
 * Author:                  Glynn Quelch<glynn.quelch@gmail.com>
 * License:                 GPL-3.0-or-later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pinkcrab-weather-block
 * Domain Path: /languages
 * Update URI: https://github.com/gin0115/ecowitt-weather-block
 */

defined( 'ABSPATH' ) || exit;

define( 'PC_WEATHER_BASENAME', plugin_basename( __FILE__ ) );

use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Perique_Admin_Menu\Module\Admin_Menu;
use PinkCrab\Ajax\Module\Ajax;
use PinkCrab\Route\Module\Route;
use PinkCrab\Plugin_Lifecycle\Plugin_Life_Cycle;
use PinkCrab\Perique\Migration\Module\Perique_Migrations;
use PinkCrab\Ecowitt_Weather_Block\Cache\Migration\Create_Observation_Cache_Table;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Fires after WordPress has finished loading but before any headers are sent.
 */
add_action(
	'init',
	function (): void {
		$app = ( new App_Factory( __DIR__ ) )
			->default_setup()
			->module( Admin_Menu::class )
			->module( Ajax::class )
			->module( Route::class )
			->module(
				Plugin_Life_Cycle::class,
				fn( Plugin_Life_Cycle $e ) => $e
					->plugin_base_file( __FILE__ )
			)
			->module(
				Perique_Migrations::class,
				fn ( Perique_Migrations $m ) => $m
					->set_migration_log_key( 'ecowitt_weather_migrations' )
					->add_migration( Create_Observation_Cache_Table::class )
			)
			->di_rules( require __DIR__ . '/config/dependencies.php' )
			->app_config( require __DIR__ . '/config/settings.php' )
			->registration_classes( require __DIR__ . '/config/registration.php' );
		$app->boot();

	},
	0
);

// Instruct WordPress to fetch update information from GitHub.
add_filter(
	'update_plugins_github.com',
	static function ( $update, array $plugin_data, string $plugin_file ) {
		if ( PC_WEATHER_BASENAME !== $plugin_file || false !== $update ) {
			return $update;
		}

		$latest_release_info = get_site_transient( 'pc_weather_latest_release_info' );
		if ( false === $latest_release_info ) {
			$response = wp_remote_get(
				'https://api.github.com/repos/gin0115/ecowitt-weather-block/releases/latest',
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/vnd.github+json',
					),
				)
			);
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return $update;
			}
			$latest_release_info = wp_remote_retrieve_body( $response );
			set_site_transient( 'pc_weather_latest_release_info', $latest_release_info, HOUR_IN_SECONDS );
		}

		if ( empty( $latest_release_info ) ) {
			return $update;
		}

		$latest_release_info = json_decode( $latest_release_info, true );
		if (
			! is_array( $latest_release_info ) ||
			empty( $latest_release_info['tag_name'] ) ||
			empty( $latest_release_info['html_url'] ) ||
			empty( $latest_release_info['assets'] ) ||
			! is_array( $latest_release_info['assets'] )
		) {
			return $update;
		}

		$package_url = '';
		foreach ( $latest_release_info['assets'] as $asset ) {
			if ( ! empty( $asset['browser_download_url'] ) && str_ends_with( $asset['browser_download_url'], '.zip' ) ) {
				$package_url = $asset['browser_download_url'];
				break;
			}
		}
		if ( '' === $package_url ) {
			return $update;
		}

		$latest_release_version = ltrim( $latest_release_info['tag_name'], 'v' );
		if ( version_compare( $plugin_data['Version'], $latest_release_version, '<' ) ) {
			$update = array(
				'slug'    => $plugin_data['TextDomain'],
				'version' => $latest_release_version,
				'url'     => $latest_release_info['html_url'],
				'package' => $package_url,
			);
		} else {
			$update = false;
		}

		return $update;
	},
	10,
	3
);
