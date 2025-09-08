<?php

/**
 * Tests for the Device DTO.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\DTO\V3;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT;

/**
 * Tests for the Device DTO.
 *
 * @group unit
 * @group ecowitt
 * @group dto
 */
class Test_Device extends \WP_UnitTestCase {

	/**
	 * Sample device data for testing.
	 *
	 * @var array<string, mixed>
	 */
	private array $sample_device_data;

	/**
	 * Sample IOT device data for testing.
	 *
	 * @var array<string, mixed>
	 */
	private array $sample_iot_data;

	public function set_up(): void {
		parent::set_up();

		$this->sample_iot_data = array(
			'name'          => 'Indoor Sensor',
			'default_title' => 'WH31_CH1',
			'device_id'     => 'iot123',
			'version'       => '1.6.8',
			'createtime'    => '2023-01-15 10:30:00',
		);

		$this->sample_device_data = array(
			'id'             => 12345,
			'name'           => 'My Weather Station',
			'mac'            => '00:11:22:33:44:55',
			'imei'           => '123456789012345',
			'type'           => 1,
			'date_zone_id'   => 'America/New_York',
			'createtime'     => 1642248600,
			'longitude'      => -74.006,
			'latitude'       => 40.7128,
			'stationtype'    => 'GW1100A_V2.2.8',
			'iotdevice_list' => array( $this->sample_iot_data ),
		);
	}

	/**
	 * @testdox It should be possible to create a Device instance with all required parameters.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::__construct
	 */
	public function test_can_create_device_instance(): void {
		$device = new Device(
			12345,
			'My Weather Station',
			'00:11:22:33:44:55',
			'123456789012345',
			1,
			'America/New_York',
			1642248600,
			-74.006,
			40.7128,
			'GW1100A_V2.2.8',
			array()
		);

		$this->assertInstanceOf( Device::class, $device );
	}

	/**
	 * @testdox It should return correct property values when accessed.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::__construct
	 */
	public function test_device_properties_are_accessible(): void {
		$iot_device = new IOT(
			$this->sample_iot_data['name'],
			$this->sample_iot_data['default_title'],
			$this->sample_iot_data['device_id'],
			$this->sample_iot_data['version'],
			$this->sample_iot_data['createtime'],
			array()
		);

		$device = new Device(
			$this->sample_device_data['id'],
			$this->sample_device_data['name'],
			$this->sample_device_data['mac'],
			$this->sample_device_data['imei'],
			$this->sample_device_data['type'],
			$this->sample_device_data['date_zone_id'],
			$this->sample_device_data['createtime'],
			$this->sample_device_data['longitude'],
			$this->sample_device_data['latitude'],
			$this->sample_device_data['stationtype'],
			array( $iot_device )
		);

		$this->assertSame( $this->sample_device_data['id'], $device->id );
		$this->assertSame( $this->sample_device_data['name'], $device->name );
		$this->assertSame( $this->sample_device_data['mac'], $device->mac );
		$this->assertSame( $this->sample_device_data['imei'], $device->imei );
		$this->assertSame( $this->sample_device_data['type'], $device->type );
		$this->assertSame( $this->sample_device_data['date_zone_id'], $device->date_zone_id );
		$this->assertSame( $this->sample_device_data['createtime'], $device->createtime );
		$this->assertSame( $this->sample_device_data['longitude'], $device->longitude );
		$this->assertSame( $this->sample_device_data['latitude'], $device->latitude );
		$this->assertSame( $this->sample_device_data['stationtype'], $device->stationtype );
		$this->assertCount( 1, $device->iotdevice_list );
		$this->assertInstanceOf( IOT::class, $device->iotdevice_list[0] );
	}

	/**
	 * @testdox It should create a Device from array data using from_array method.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_can_create_device_from_array(): void {
		$device = Device::from_array( $this->sample_device_data );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertSame( $this->sample_device_data['id'], $device->id );
		$this->assertSame( $this->sample_device_data['name'], $device->name );
		$this->assertSame( $this->sample_device_data['mac'], $device->mac );
		$this->assertSame( $this->sample_device_data['imei'], $device->imei );
		$this->assertSame( $this->sample_device_data['type'], $device->type );
		$this->assertSame( $this->sample_device_data['date_zone_id'], $device->date_zone_id );
		$this->assertSame( $this->sample_device_data['createtime'], $device->createtime );
		$this->assertSame( $this->sample_device_data['longitude'], $device->longitude );
		$this->assertSame( $this->sample_device_data['latitude'], $device->latitude );
		$this->assertSame( $this->sample_device_data['stationtype'], $device->stationtype );
		$this->assertCount( 1, $device->iotdevice_list );
		$this->assertInstanceOf( IOT::class, $device->iotdevice_list[0] );
	}

	/**
	 * @testdox It should handle missing imei field in array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_handles_missing_imei_field(): void {
		$data_without_imei = $this->sample_device_data;
		unset( $data_without_imei['imei'] );

		$device = Device::from_array( $data_without_imei );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertSame( '', $device->imei );
	}

	/**
	 * @testdox It should handle empty iotdevice_list in array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_handles_empty_iot_device_list(): void {
		$data_without_iot_devices                   = $this->sample_device_data;
		$data_without_iot_devices['iotdevice_list'] = array();

		$device = Device::from_array( $data_without_iot_devices );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertCount( 0, $device->iotdevice_list );
	}

	/**
	 * @testdox It should handle missing iotdevice_list field in array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_handles_missing_iot_device_list_field(): void {
		$data_without_iot_devices = $this->sample_device_data;
		unset( $data_without_iot_devices['iotdevice_list'] );

		$device = Device::from_array( $data_without_iot_devices );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertCount( 0, $device->iotdevice_list );
	}

	/**
	 * @testdox It should handle invalid iotdevice_list field in array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_handles_invalid_iot_device_list_field(): void {
		$data_with_invalid_iot_devices = $this->sample_device_data;
		$data_with_invalid_iot_devices['iotdevice_list'] = 'not_an_array';

		$device = Device::from_array( $data_with_invalid_iot_devices );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertCount( 0, $device->iotdevice_list );
	}

	/**
	 * @testdox It should properly type cast values from array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_type_casts_values_correctly(): void {
		$string_data = array(
			'id'             => '12345',
			'name'           => 'My Weather Station',
			'mac'            => '00:11:22:33:44:55',
			'imei'           => '123456789012345',
			'type'           => '1',
			'date_zone_id'   => 'America/New_York',
			'createtime'     => '1642248600',
			'longitude'      => '-74.006',
			'latitude'       => '40.7128',
			'stationtype'    => 'GW1100A_V2.2.8',
			'iotdevice_list' => array(),
		);

		$device = Device::from_array( $string_data );

		$this->assertIsInt( $device->id );
		$this->assertSame( 12345, $device->id );
		$this->assertIsString( $device->name );
		$this->assertIsString( $device->mac );
		$this->assertIsString( $device->imei );
		$this->assertIsInt( $device->type );
		$this->assertSame( 1, $device->type );
		$this->assertIsString( $device->date_zone_id );
		$this->assertIsInt( $device->createtime );
		$this->assertSame( 1642248600, $device->createtime );
		$this->assertIsFloat( $device->longitude );
		$this->assertSame( -74.006, $device->longitude );
		$this->assertIsFloat( $device->latitude );
		$this->assertSame( 40.7128, $device->latitude );
		$this->assertIsString( $device->stationtype );
		$this->assertIsArray( $device->iotdevice_list );
	}

	/**
	 * @testdox It should process multiple IOT devices in iotdevice_list.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::from_array
	 */
	public function test_processes_multiple_iot_devices(): void {
		$second_iot_data = array(
			'name'          => 'Outdoor Sensor',
			'default_title' => 'WH31_CH2',
			'device_id'     => 'iot456',
			'version'       => '1.6.9',
			'createtime'    => '2023-01-16 11:30:00',
		);

		$data_with_multiple_iot                   = $this->sample_device_data;
		$data_with_multiple_iot['iotdevice_list'] = array(
			$this->sample_iot_data,
			$second_iot_data,
		);

		$device = Device::from_array( $data_with_multiple_iot );

		$this->assertInstanceOf( Device::class, $device );
		$this->assertCount( 2, $device->iotdevice_list );
		$this->assertInstanceOf( IOT::class, $device->iotdevice_list[0] );
		$this->assertInstanceOf( IOT::class, $device->iotdevice_list[1] );
		$this->assertSame( 'Indoor Sensor', $device->iotdevice_list[0]->name );
		$this->assertSame( 'Outdoor Sensor', $device->iotdevice_list[1]->name );
	}

	/**
	 * @testdox It should handle default empty array for iotdevice_list in constructor.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device::__construct
	 */
	public function test_constructor_default_empty_iot_device_list(): void {
		$device = new Device(
			12345,
			'My Weather Station',
			'00:11:22:33:44:55',
			'123456789012345',
			1,
			'America/New_York',
			1642248600,
			-74.006,
			40.7128,
			'GW1100A_V2.2.8'
			// Note: not passing iotdevice_list parameter to test default
		);

		$this->assertInstanceOf( Device::class, $device );
		$this->assertIsArray( $device->iotdevice_list );
		$this->assertCount( 0, $device->iotdevice_list );
	}
}
