<?php

/**
 * Tests for the Asset_Loader class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Admin;

use PinkCrab\Loader\Hook_Loader;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader;

/**
 * Tests for the Asset_Loader class.
 *
 * @group unit
 * @group admin
 * @group asset_loader
 */
class Test_Asset_Loader extends \WP_UnitTestCase {



	/**
	 * @testdox The asset loader should register the shared admin styles and scripts
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader::__construct
     * @covers \PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader::register
	 */
	public function test_can_register_shared_admin_styles_and_scripts(): void {
        $asset_loader = new Asset_Loader( new App_Config() );

        $hook_loader = new Hook_Loader();
        $asset_loader->register( $hook_loader );
        $hooks = Objects::get_property( $hook_loader, 'hooks' )->export();

        $this->assertCount( 1, $hooks );
        $this->assertEquals( 'admin_enqueue_scripts', $hooks[0]->get_handle());
        $this->assertEquals(  $asset_loader, $hooks[0]->get_callback()[0]);
        $this->assertEquals( 'enqueue_assets', $hooks[0]->get_callback()[1]);
	}

}
