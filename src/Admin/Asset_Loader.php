<?php

/**
 * Admin Asset Loader
 *
 * Handles the loading of CSS and JS assets for the admin area.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Admin;

use PinkCrab\Enqueue\Enqueue;
use PinkCrab\Perique\Interfaces\Hookable;
use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Perique\Application\Config;

class Asset_Loader implements Hookable {

	/**
	 * App_Config instance.
	 *
	 * @var \PinkCrab\Perique\Application\App_Config
	 */
	protected \PinkCrab\Perique\Application\App_Config $app_config;

	public function __construct( \PinkCrab\Perique\Application\App_Config $app_config ) {
		$this->app_config = $app_config;
	}

	/**
	 * Get assets url.
	 *
	 * @return string
	 */
	public static function assets_url(): string {
		$assets = Config::url( 'assets' ); // @phpstan-ignore-line, this is a magic method.
		return is_string( $assets )
			? $assets
			: wp_upload_dir()['baseurl'];
	}

	/**
	 * Register all hooks.
	 *
	 * @param Hook_Loader $loader
	 * @return void
	 */
	public function register( Hook_Loader $loader ): void {
		$loader->admin_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		Enqueue::style( 'ecowitt-admin-styles' )
			->src( self::assets_url() . '/css/admin.scss.css' )
			->ver( $this->app_config->version() )
			->register();
	}
}
