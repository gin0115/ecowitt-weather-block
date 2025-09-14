<?php

/**
 * The IOT device component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT as IOTDTO;
use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;

/**
 * The IOT device component.
 *
 * @view admin.device.iot
 */
class IOT extends Component {

	public string $name          = '';
	public string $default_title = '';
	public string $device_id     = '';
	public string $version       = '';
	public string $createtime    = '';

	/**
	 * Additional IOT device data.
	 *
	 * @var array<string, mixed>
	 */
	public array $additional_data = array();

	/**
	 * Creates an instance of the IOT Component.
	 *
	 * @param IOTDTO|null $iot
	 */
	public function __construct( ?IOTDTO $iot = null ) {
		if ( is_null( $iot ) ) {
			return;
		}

		$this->name            = $iot->name;
		$this->default_title   = $iot->default_title;
		$this->device_id       = $iot->device_id;
		$this->version         = $iot->version;
		$this->createtime      = $iot->createtime;
		$this->additional_data = $iot->additional_data;
	}

	/**
	 * Get the display title for the IOT device.
	 * Uses name if available, otherwise falls back to default_title.
	 *
	 * @return string
	 */
	public function display_title(): string {
		return ! empty( $this->name ) ? $this->name : $this->default_title;
	}

	/**
	 * Get the formatted creation date.
	 *
	 * @return string
	 */
	public function creation_date(): string {
		if ( empty( $this->createtime ) || ! is_numeric( $this->createtime ) ) {
			return '';
		}
		return wp_date( Utils::get_datetime_format(), (int) $this->createtime ) ?: '';
	}

	/**
	 * Get additional data for the IOT device.
	 *
	 * @return array<string, mixed>
	 */
	public function additional_data(): array {
		return $this->additional_data;
	}

	/**
	 * Check if IOT device has additional data.
	 *
	 * @return bool
	 */
	public function has_additional_data(): bool {
		return ! empty( $this->additional_data );
	}

	/**
	 * Get a masked version of the device ID for display.
	 *
	 * @return string
	 */
	public function masked_device_id(): string {
		if ( empty( $this->device_id ) ) {
			return '';
		}

		$length = strlen( $this->device_id );
		if ( $length <= 8 ) {
			// For short IDs, show first 2 and last 2 characters
			return substr( $this->device_id, 0, 2 ) . str_repeat( '*', $length - 4 ) . substr( $this->device_id, -2 );
		}

		// For longer IDs, show first 4 and last 4 characters
		return substr( $this->device_id, 0, 4 ) . str_repeat( '*', $length - 8 ) . substr( $this->device_id, -4 );
	}
}
