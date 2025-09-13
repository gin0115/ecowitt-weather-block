<?php

/**
 * Device DTO for Ecowitt API v3 responses.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Device DTO class for Ecowitt API v3.
 */
class Device {

	/**
	 * Device ID.
	 *
	 * @var int
	 */
	public int $id;

	/**
	 * Device name.
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * Device MAC address.
	 *
	 * @var string
	 */
	public string $mac;

	/**
	 * Device IMEI.
	 *
	 * @var string
	 */
	public string $imei;

	/**
	 * Device type (1=weather detector, 2=camera).
	 *
	 * @var int
	 */
	public int $type;

	/**
	 * Timezone ID.
	 *
	 * @var string
	 */
	public string $date_zone_id;

	/**
	 * Creation timestamp.
	 *
	 * @var int
	 */
	public int $createtime;

	/**
	 * Longitude coordinate.
	 *
	 * @var float
	 */
	public float $longitude;

	/**
	 * Latitude coordinate.
	 *
	 * @var float
	 */
	public float $latitude;

	/**
	 * Station type.
	 *
	 * @var string
	 */
	public string $stationtype;

	/**
	 * List of IOT devices.
	 *
	 * @var IOT[]
	 */
	public array $iotdevice_list;

	/**
	 * Constructor.
	 *
	 * @param int $id Device ID.
	 * @param string $name Device name.
	 * @param string $mac Device MAC address.
	 * @param string $imei Device IMEI.
	 * @param int $type Device type.
	 * @param string $date_zone_id Timezone ID.
	 * @param int $createtime Creation timestamp.
	 * @param float $longitude Longitude coordinate.
	 * @param float $latitude Latitude coordinate.
	 * @param string $stationtype Station type.
	 * @param IOT[] $iotdevice_list List of IOT devices.
	 */
	public function __construct(
		int $id,
		string $name,
		string $mac,
		string $imei,
		int $type,
		string $date_zone_id,
		int $createtime,
		float $longitude,
		float $latitude,
		string $stationtype,
		array $iotdevice_list = array()
	) {
		$this->id             = $id;
		$this->name           = $name;
		$this->mac            = $mac;
		$this->imei           = $imei;
		$this->type           = $type;
		$this->date_zone_id   = $date_zone_id;
		$this->createtime     = $createtime;
		$this->longitude      = $longitude;
		$this->latitude       = $latitude;
		$this->stationtype    = $stationtype;
		$this->iotdevice_list = $iotdevice_list;
	}

	/**
	 * Create Device from API v3 response array.
	 *
	 * @param array<mixed> $data Device data from API v3 response.
	 * @return Device Device instance.
	 */
	public static function from_array( array $data ): Device {
		// Validate required fields exist
		$required_fields = array( 'id', 'name', 'mac', 'type', 'date_zone_id', 'createtime', 'longitude', 'latitude', 'stationtype' );
		foreach ( $required_fields as $field ) {
			if ( ! isset( $data[ $field ] ) ) {
				throw new \InvalidArgumentException(
					sprintf( 'Required field "%s" is missing from device data', esc_html( $field ) )
				);
			}
		}

		// Sanitize and validate data - never trust API data
		$id           = absint( is_scalar( $data['id'] ) ? $data['id'] : 0 );
		$name         = sanitize_text_field( is_scalar( $data['name'] ) ? (string) $data['name'] : '' );
		$mac          = sanitize_text_field( is_scalar( $data['mac'] ) ? (string) $data['mac'] : '' );
		$imei         = sanitize_text_field( is_scalar( $data['imei'] ?? '' ) ? (string) ( $data['imei'] ?? '' ) : '' );
		$type         = absint( is_scalar( $data['type'] ) ? $data['type'] : 0 );
		$date_zone_id = sanitize_text_field( is_scalar( $data['date_zone_id'] ) ? (string) $data['date_zone_id'] : '' );
		$createtime   = absint( is_scalar( $data['createtime'] ) ? $data['createtime'] : 0 );
		$longitude    = (float) sanitize_text_field( is_scalar( $data['longitude'] ) ? (string) $data['longitude'] : '0' );
		$latitude     = (float) sanitize_text_field( is_scalar( $data['latitude'] ) ? (string) $data['latitude'] : '0' );
		$stationtype  = sanitize_text_field( is_scalar( $data['stationtype'] ) ? (string) $data['stationtype'] : '' );

		// Process IOT devices list
		$iot_devices = array();
		if ( isset( $data['iotdevice_list'] ) && is_array( $data['iotdevice_list'] ) ) {
			foreach ( $data['iotdevice_list'] as $iot_data ) {
				if ( is_array( $iot_data ) ) {
					/** @var array<string, mixed> $iot_data */
					$iot_devices[] = IOT::from_array( $iot_data );
				}
			}
		}

		return new self(
			$id,
			$name,
			$mac,
			$imei,
			$type,
			$date_zone_id,
			$createtime,
			$longitude,
			$latitude,
			$stationtype,
			$iot_devices
		);
	}

	/**
	 * Convert Device to array with proper PHPStan array shape.
	 *
	 * @return array{id: int, name: string, mac: string, imei: string, type: int, date_zone_id: string, createtime: int, longitude: float, latitude: float, stationtype: string, iotdevice_list: array<int, array{name: string, default_title: string, device_id: string, version: string, createtime: string, additional_data: array<string, mixed>}>}
	 */
	public function to_array(): array {
		$iot_arrays = array();
		foreach ( $this->iotdevice_list as $iot ) {
			$iot_arrays[] = array(
				'name'            => $iot->name,
				'default_title'   => $iot->default_title,
				'device_id'       => $iot->device_id,
				'version'         => $iot->version,
				'createtime'      => $iot->createtime,
				'additional_data' => $iot->additional_data,
			);
		}

		return array(
			'id'             => $this->id,
			'name'           => $this->name,
			'mac'            => $this->mac,
			'imei'           => $this->imei,
			'type'           => $this->type,
			'date_zone_id'   => $this->date_zone_id,
			'createtime'     => $this->createtime,
			'longitude'      => $this->longitude,
			'latitude'       => $this->latitude,
			'stationtype'    => $this->stationtype,
			'iotdevice_list' => $iot_arrays,
		);
	}
}
