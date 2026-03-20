<?php

/**
 * Registers the Ecowitt Live Weather Gutenberg block.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Block;

use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Perique\Interfaces\Hookable;
use PinkCrab\Perique\Application\App_Config;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Registers the Ecowitt Live Weather block.
 */
class Weather_Live_Block implements Hookable {

	/**
	 * App_Config instance.
	 *
	 * @var App_Config
	 */
	protected App_Config $app_config;

	/**
	 * Constructor.
	 *
	 * @param App_Config $app_config App config.
	 */
	public function __construct( App_Config $app_config ) {
		$this->app_config = $app_config;
	}

	/**
	 * Register hooks.
	 *
	 * @param Hook_Loader $loader Hook loader.
	 * @return void
	 */
	public function register( Hook_Loader $loader ): void {
		$loader->action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register block type from build directory.
	 *
	 * @return void
	 */
	public function register_block(): void {
		register_block_type( dirname( __DIR__, 2 ) . '/block/build' );
	}
}
