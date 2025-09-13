<?php

/**
 * The IOT device list component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT as IOTDTO;

/**
 * The IOT device list component.
 *
 * @view admin.device.iot_list
 */
class IOT_List extends Component {

	/**
	 * The IOT devices.
	 *
	 * @var array<int, IOT>
	 */
	public array $iot_devices = array();

	/**
	 * Creates an instance of the IOT_List Component.
	 *
	 * @param array<int, mixed> $iot_devices
	 */
	public function __construct( array $iot_devices = array() ) {
		$iot_devices       = array_filter( $iot_devices, fn( $iot ) => $iot instanceof IOTDTO );
		$this->iot_devices = array_map( fn( $iot ) => new IOT( $iot ), $iot_devices );
	}

	/**
	 * Get the IOT devices.
	 *
	 * @return array<int, IOT>
	 */
	public function iot_devices(): array {
		return $this->iot_devices;
	}

	/**
	 * Check if there are any IOT devices.
	 *
	 * @return bool
	 */
	public function has_iot_devices(): bool {
		return ! empty( $this->iot_devices );
	}

	/**
	 * Get the count of IOT devices.
	 *
	 * @return int
	 */
	public function iot_devices_count(): int {
		return count( $this->iot_devices );
	}
}
