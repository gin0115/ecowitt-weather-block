<?php

/**
 * The device component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device;

use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;
use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device as DeviceDTO;


/**
 * The device component.
 *
 * @phpstan-type DeviceComponentArray array{
	 *     id: int,
	 *     name: string,
	 *     mac: string,
	 *     imei: string,
	 *     type: int,
	 *     date_zone_id: string,
	 *     createtime: int,
	 *     longitude: float,
	 *     latitude: float,
	 *     stationtype: string,
	 *     iot_devices: array<int, IOT>
	 * }
 *
 * @view admin.device.device
 */
class Device extends Component implements \JsonSerializable {

	public int $id               = 0;
	public string $name          = '';
	public string $mac           = '';
	public string $imei          = '';
	public int $type             = 0;
	public string $type_label    = '';
	public string $date_zone_id  = '';
	public int $createtime       = 0;
	public string $creation_date = '';
	public float $longitude      = 0.0;
	public float $latitude       = 0.0;
	public string $stationtype   = '';
	public string $connection_id;

	public DeviceDTO $device;

	/**
	 * List of IOT devices.
	 *
	 * @var array<int, IOT>
	 */
	public array $iot_devices = array();

	/**
	 * Creates an instance of the Device Component.
	 *
	 * @param DeviceDTO $device
	 * @param string    $connection_id
	 */
	public function __construct( DeviceDTO $device, string $connection_id ) {
		$this->device        = $device;
		$this->id            = $device->id;
		$this->name          = $device->name;
		$this->mac           = $device->mac;
		$this->imei          = $device->imei;
		$this->type          = $device->type;
		$this->date_zone_id  = $device->date_zone_id;
		$this->createtime    = $device->createtime;
		$this->longitude     = $device->longitude;
		$this->latitude      = $device->latitude;
		$this->stationtype   = $device->stationtype;
		$this->connection_id = $connection_id;

		// Set formatted type label
		$this->type_label = $this->type_label();

		// Set formatted creation date using site timezone
		if ( $this->createtime > 0 ) {
			$this->creation_date = wp_date( Utils::get_datetime_format(), $this->createtime ) ?: '';
		} else {
			$this->creation_date = '';
		}

		// Convert IOT devices to IOT components
		$this->iot_devices = array_map( fn( $iot ) => new IOT( $iot ), $device->iotdevice_list );
	}

	/**
	 * Get the formatted device type label.
	 *
	 * @return string
	 */
	public function type_label(): string {
		switch ( $this->type ) {
			case 1:
				return 'Weather Detector';
			case 2:
				return 'Camera';
			default:
				return 'Unknown';
		}
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
	 * Check if device has IOT devices.
	 *
	 * @return boolean
	 */
	public function has_iot_devices(): bool {
		return ! empty( $this->iot_devices );
	}

	/**
	 * Get the count of IOT devices.
	 *
	 * @return integer
	 */
	public function iot_devices_count(): int {
		return count( $this->iot_devices );
	}

	/**
	 * Get the device.
	 *
	 * @return DeviceDTO
	 */
	public function get_device(): DeviceDTO {
		return $this->device;
	}

	/**
	 * Get the device as an array.
	 *
	 * @return DeviceComponentArray
	 */
	public function to_array(): array {
		return array(
			'id'           => $this->id,
			'name'         => $this->name,
			'mac'          => $this->mac,
			'imei'         => $this->imei,
			'type'         => $this->type,
			'date_zone_id' => $this->date_zone_id,
			'createtime'   => $this->createtime,
			'longitude'    => $this->longitude,
			'latitude'     => $this->latitude,
			'stationtype'  => $this->stationtype,
			'iot_devices'  => $this->iot_devices,
		);
	}

	/**
	 * Get the device as a JSON string.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return $this->to_array();
	}
}
