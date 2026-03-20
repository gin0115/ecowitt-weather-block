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
	 * @var string
	 */
	public string $connection_id;


	/**
	 * Creates an instance of the Device_List Component.
	 *
	 * @param array<int, mixed> $devices
	 * @param string            $connection_id
	 */
	public function __construct( array $devices, string $connection_id ) {
		$devices             = array_filter( $devices, fn( $device ) => $device instanceof DeviceDTO );
		$this->devices       = array_map( fn( $device ) => new Device( $device, $connection_id ), $devices );
		$this->connection_id = $connection_id;
	}
}
