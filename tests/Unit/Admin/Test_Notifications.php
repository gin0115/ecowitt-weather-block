<?php

/**
 * Tests for the Admin Notifications class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Admin;

use Gin0115\WPUnit_Helpers\Output;
use PinkCrab\Ecowitt_Weather_Block\Admin\Notifications;

/**
 * Tests for the Admin Notifications class.
 * 
 * @group unit
 * @group admin
 */
class Test_Notifications extends \WP_UnitTestCase {

    // public function set_up(): void {
	// 	parent::set_up();
	// }

    /**
     * @testdox It should be possible to add a success notification.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::add_success
     */
    public function test_add_success_notification(): void {
        $notifications = new Notifications();
        $notifications->add_success( 'Test success message.' );
        $this->assertCount( 1, $notifications->get_notifications() );
        $this->assertSame( 'Test success message.', $notifications->get_notifications()[0]['message'] );
        $this->assertSame( 'success', $notifications->get_notifications()[0]['type'] );
        
        // Render and check output.
        $rendered = Output::buffer( fn() => $notifications->render_notifications() );
        $this->assertNotNull( $rendered);
        // Check the rendered output contains the message. and notice-success class.
        $this->assertStringContainsString( 'Test success message.', $rendered );
        $this->assertStringContainsString( 'notice-success', $rendered );
    }

    /**
     * @testdox It should be possible to add an error notification.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::add_error
     */
    public function test_add_error_notification(): void {
        $notifications = new Notifications();
        $notifications->add_error( 'Test error message.' );
        $this->assertCount( 1, $notifications->get_notifications() );
        $this->assertSame( 'Test error message.', $notifications->get_notifications()[0]['message'] );
        $this->assertSame( 'error', $notifications->get_notifications()[0]['type'] );   
        // Render and check output.
        $rendered = Output::buffer( fn() => $notifications->render_notifications() );
        $this->assertNotNull( $rendered);
        // Check the rendered output contains the message. and notice-error class.
        $this->assertStringContainsString( 'Test error message.', $rendered );
        $this->assertStringContainsString( 'notice-error', $rendered );
    }

    /**
     * @testdox It should be possible to add an info notification.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::add_info
     */
    public function test_add_info_notification(): void {
        $notifications = new Notifications();
        $notifications->add_info( 'Test info message.' );
        $this->assertCount( 1, $notifications->get_notifications() );
        $this->assertSame( 'Test info message.', $notifications->get_notifications()[0]['message'] );
        $this->assertSame( 'info', $notifications->get_notifications()[0]['type'] );

        // Render and check output.
        $rendered = Output::buffer( fn() => $notifications->render_notifications() );
        $this->assertNotNull( $rendered);
        // Check the rendered output contains the message. and notice-info class.
        $this->assertStringContainsString( 'Test info message.', $rendered );
        $this->assertStringContainsString( 'notice-info', $rendered );
    }

    /**
     * @testdox When an empty notification is added, it should still be stored but not rendered.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::add_notification
     */
    public function test_add_empty_success_notification(): void {
        $notifications = new Notifications();
        $notifications->add_notification( 'foo', '' );
        
        
        $this->assertCount( 1, $notifications->get_notifications() );
        $this->assertSame( '', $notifications->get_notifications()[0]['message'] );
        $this->assertSame( 'foo', $notifications->get_notifications()[0]['type'] );

        // Render and check output.
        $rendered = Output::buffer( fn() => $notifications->render_notifications() );
        $this->assertEquals( '', $rendered );
    }

    /**
     * @testdox It should be possible to check if we have any notifications.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::has_notifications
     */
    public function test_has_notifications(): void {
        $notifications = new Notifications();
        $this->assertFalse( $notifications->has_notifications() );
        $notifications->add_success( 'Test success message.' );
        $this->assertTrue( $notifications->has_notifications() );
    }

    /**
     * @testdox It should be possible to render multiple notifications.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::render_notifications
     */
    public function test_render_multiple_notifications(): void {
        $notifications = new Notifications();
        $notifications->add_success( 'Test success message.' );
        $notifications->add_error( 'Test error message.' );
        $notifications->add_info( 'Test info message.' );

        // Render and check output.
        $rendered = Output::buffer( fn() => $notifications->render_notifications() );
        $this->assertNotNull( $rendered );
        // Check the rendered output contains all messages and respective classes.
        $this->assertStringContainsString( 'Test success message.', $rendered );
        $this->assertStringContainsString( 'notice-success', $rendered );
        $this->assertStringContainsString( 'Test error message.', $rendered );
        $this->assertStringContainsString( 'notice-error', $rendered );
        $this->assertStringContainsString( 'Test info message.', $rendered );
        $this->assertStringContainsString( 'notice-info', $rendered );

    }

    /**
     * @testdox it should be possible to clear notifications.
     * @cover \PinkCrab\Ecowitt_Weather_Block\Admin\Notifications::clear_notifications
     */
    public function test_clear_notifications(): void {
        $notifications = new Notifications();
        $notifications->add_success( 'Test success message.' );
        $notifications->add_error( 'Test error message.' );
        $notifications->add_info( 'Test info message.' );
        $this->assertCount( 3, $notifications->get_notifications() );
        $notifications->clear_notifications();
        $this->assertCount( 0, $notifications->get_notifications() );
    }   
}