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


use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Perique_Admin_Menu\Module\Admin_Menu;

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
            ->di_rules( require __DIR__ . '/config/dependencies.php' )
            ->app_config( require __DIR__ . '/config/settings.php' )
            ->registration_classes( require __DIR__ . '/config/registration.php' );
		$app->boot();
	},
	0
);
