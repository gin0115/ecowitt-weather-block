<?php

/**
 * Application Tests for the Asset_Loader class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Application\Admin;

use PinkCrab\Perique\Application\Config;
use PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader;
use PinkCrab\Ecowitt_Weather_Block\Tests\Util\App_Helper_Trait;

/**
 * Application Tests for the Asset_Loader class.
 * These tests require a booted Perique application instance.
 *
 * @group application
 * @group admin
 * @group asset_loader
 */
class Test_Asset_Loader extends \WP_UnitTestCase {
use App_Helper_Trait;

	/**
	 * Unset the app instance on teardown.
	 */
	public function tear_down(): void {
		$this->unset_app_instance();
	}

	/**
	 * @testdox It should return a valid assets URL when Config::url is available
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader::assets_url
	 */
	public function test_assets_url_returns_valid_url(): void {
		$this->pre_populated_app_provider( [ 'url' => [ 'assets' => 'https://example.com/assets/' ] ] )->boot();
		$this->assertEquals( 'https://example.com/assets/', Asset_Loader::assets_url() );
	}

	/**
	 * @testdox It should enqueue assets correctly when called
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader::enqueue_assets
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_enqueue_assets_registers_correctly(): void {
		// Mock the current screen to prevent WordPress Site Health from interfering
		set_current_screen( 'dashboard' );

		$app = $this->pre_populated_app_provider( [ 'url' => [ 'assets' => 'https://example.com/assets/' ] ] )->boot();
		$debug = $app->__debugInfo();
		$asset_loader = new Asset_Loader( new $debug['app_config'] );

		add_action( 'admin_enqueue_scripts', function() use ( $asset_loader ) {
			$asset_loader->enqueue_assets();
		} );

		// Boot the app and trigger the admin enqueue scripts action
		do_action( 'init' );
		do_action( 'admin_enqueue_scripts' );

		// Assert that the style was enqueued
		$this->assertTrue( wp_style_is( 'ecowitt-admin-styles', 'enqueued' ) );

		// Clean up
		set_current_screen( null );
	}
}
