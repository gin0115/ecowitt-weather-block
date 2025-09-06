<?php

/**
 * Handles all admin notifications.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Admin;

/**
 * Handles all admin notifications.
 */
class Notifications {

	/**
	 * Holds all notifications.
	 *
	 * @var array<int, array{type: string, message: string}>
	 */
	protected array $notifications = array();

	/**
	 * Adds a notification.
	 *
	 * @param string $type    The type of notification.
	 * @param string $message The message to display.
	 *
	 * @return void
	 */
	public function add_notification( string $type, string $message ): void {
		$this->notifications[] = array(
			'type'    => $type,
			'message' => $message,
		);
	}

	/**
	 * Add a success notification.
	 *
	 * @param string $message The message to display.
	 *
	 * @return void
	 */
	public function add_success( string $message ): void {
		$this->add_notification( 'success', $message );
	}

	/**
	 * Add an error notification.
	 *
	 * @param string $message The message to display.
	 *
	 * @return void
	 */
	public function add_error( string $message ): void {
		$this->add_notification( 'error', $message );
	}

	/**
	 * Add an info notification.
	 *
	 * @param string $message The message to display.
	 *
	 * @return void
	 */
	public function add_info( string $message ): void {
		$this->add_notification( 'info', $message );
	}

	/**
	 * Checks if there are any notifications.
	 *
	 * @return boolean
	 */
	public function has_notifications(): bool {
		return ! empty( $this->notifications );
	}

	/**
	 * Get all notifications.
	 *
	 * @return array<int, array{type: string, message: string}>
	 */
	public function get_notifications(): array {
		return $this->notifications;
	}

	/**
	 * Render all notifications.
	 *
	 * @param boolean $is_dismissible If the notifications should be dismissible.
	 *
	 * @return void
	 */
	public function render_notifications( bool $is_dismissible = true ): void {
		$notifications = $this->notifications;
		$notifications = array_filter(
			$notifications,
			function ( $n ) {
				return ! empty( $n['message'] );
			}
		);
		if ( $this->has_notifications() ) {
			foreach ( $notifications as $notification ) {
				printf(
					'<div class="notice notice-%1$s%2$s"><p>%3$s</p></div>',
					esc_attr( $notification['type'] ),
					$is_dismissible ? ' is-dismissible' : '',
					esc_html( $notification['message'] )
				);
			}
		}
	}

	/**
	 * Clears all notifications.
	 *
	 * @return void
	 */
	public function clear_notifications(): void {
		$this->notifications = array();
	}
}
