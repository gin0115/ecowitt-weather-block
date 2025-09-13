<?php

/**
 * The device list component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device as DeviceDTO;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

/**
 * The device list component.
 *
 * @view admin.device.device_list
 */
class Device_List extends Component {

	/**
	 * The devices.
	 *
	 * @var array<int, Device>
	 */
	public array $devices = array();

	/**
	 * The connection object.
	 *
	 * @var Connection
	 */
	public Connection $connection;


	/**
	 * Creates an instance of the Device_List Component.
	 *
	 * @param array<int, mixed> $devices
	 * @param Connection $connection
	 */
	public function __construct( array $devices, Connection $connection ) {
		$devices          = array_filter( $devices, fn( $device ) => $device instanceof DeviceDTO );
		$this->devices    = array_map( fn( $device ) => new Device( $device, $connection ), $devices );
		$this->connection = $connection;
	}

	/**
	 * Get the devices.
	 *
	 * @return array<int, Device>
	 */
	public function devices(): array {
		return $this->devices;
	}

	/**
	 * Check if there are any devices.
	 *
	 * @return bool
	 */
	public function has_devices(): bool {
		return ! empty( $this->devices );
	}

	/**
	 * Get the count of devices.
	 *
	 * @return int
	 */
	public function devices_count(): int {
		return count( $this->devices );
	}

	/**
	 * Get total count of IOT devices across all devices.
	 *
	 * @return int
	 */
	public function total_iot_devices_count(): int {
		return array_sum( array_map( fn( $device ) => $device->iot_devices_count(), $this->devices ) );
	}
}
