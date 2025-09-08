<?php

/**
 * Tests for the IOT DTO.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\DTO\V3;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT;

/**
 * Tests for the IOT DTO.
 *
 * @group unit
 * @group ecowitt
 * @group dto
 */
class Test_IOT extends \WP_UnitTestCase {

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
			'extra_field'   => 'extra_value',
			'another_field' => 42,
		);
	}

	/**
	 * @testdox It should be possible to create an IOT instance with all parameters.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::__construct
	 */
	public function test_can_create_iot_instance(): void {
		$iot = new IOT(
			'Indoor Sensor',
			'WH31_CH1',
			'iot123',
			'1.6.8',
			'2023-01-15 10:30:00',
			array(
				'extra' => 'data',
			)
		);

		$this->assertInstanceOf( IOT::class, $iot );
	}

	/**
	 * @testdox It should be possible to create an IOT instance with default empty values.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::__construct
	 */
	public function test_can_create_iot_instance_with_defaults(): void {
		$iot = new IOT();

		$this->assertInstanceOf( IOT::class, $iot );
		$this->assertSame( '', $iot->name );
		$this->assertSame( '', $iot->default_title );
		$this->assertSame( '', $iot->device_id );
		$this->assertSame( '', $iot->version );
		$this->assertSame( '', $iot->createtime );
		$this->assertSame( array(), $iot->additional_data );
	}

	/**
	 * @testdox It should return correct property values when accessed.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::__construct
	 */
	public function test_iot_properties_are_accessible(): void {
		$additional_data = array( 'battery' => '95%', 'signal' => 'strong' );

		$iot = new IOT(
			'Indoor Sensor',
			'WH31_CH1',
			'iot123',
			'1.6.8',
			'2023-01-15 10:30:00',
			$additional_data
		);

		$this->assertSame( 'Indoor Sensor', $iot->name );
		$this->assertSame( 'WH31_CH1', $iot->default_title );
		$this->assertSame( 'iot123', $iot->device_id );
		$this->assertSame( '1.6.8', $iot->version );
		$this->assertSame( '2023-01-15 10:30:00', $iot->createtime );
		$this->assertSame( $additional_data, $iot->additional_data );
	}

	/**
	 * @testdox It should create an IOT from array data using from_array method.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_can_create_iot_from_array(): void {
		$iot = IOT::from_array( $this->sample_iot_data );

		$this->assertInstanceOf( IOT::class, $iot );
		$this->assertSame( $this->sample_iot_data['name'], $iot->name );
		$this->assertSame( $this->sample_iot_data['default_title'], $iot->default_title );
		$this->assertSame( $this->sample_iot_data['device_id'], $iot->device_id );
		$this->assertSame( $this->sample_iot_data['version'], $iot->version );
		$this->assertSame( $this->sample_iot_data['createtime'], $iot->createtime );

		// Check that additional data is captured
		$this->assertArrayHasKey( 'extra_field', $iot->additional_data );
		$this->assertArrayHasKey( 'another_field', $iot->additional_data );
		$this->assertSame( 'extra_value', $iot->additional_data['extra_field'] );
		$this->assertSame( 42, $iot->additional_data['another_field'] );
	}

	/**
	 * @testdox It should handle missing fields in array data with empty string defaults.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_handles_missing_fields_in_array(): void {
		$minimal_data = array(
			'name' => 'Sensor Only',
		);

		$iot = IOT::from_array( $minimal_data );

		$this->assertInstanceOf( IOT::class, $iot );
		$this->assertSame( 'Sensor Only', $iot->name );
		$this->assertSame( '', $iot->default_title );
		$this->assertSame( '', $iot->device_id );
		$this->assertSame( '', $iot->version );
		$this->assertSame( '', $iot->createtime );
		$this->assertSame( array(), $iot->additional_data );
	}

	/**
	 * @testdox It should handle completely empty array data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_handles_empty_array_data(): void {
		$iot = IOT::from_array( array() );

		$this->assertInstanceOf( IOT::class, $iot );
		$this->assertSame( '', $iot->name );
		$this->assertSame( '', $iot->default_title );
		$this->assertSame( '', $iot->device_id );
		$this->assertSame( '', $iot->version );
		$this->assertSame( '', $iot->createtime );
		$this->assertSame( array(), $iot->additional_data );
	}

	/**
	 * @testdox It should properly separate known fields from additional data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_separates_known_fields_from_additional_data(): void {
		$complex_data = array(
			'name'            => 'Complex Sensor',
			'default_title'   => 'WH31_CH3',
			'device_id'       => 'complex123',
			'version'         => '2.0.0',
			'createtime'      => '2023-01-20 15:45:00',
			'battery_level'   => '85%',
			'signal_strength' => 'excellent',
			'last_seen'       => '2023-01-20 16:00:00',
			'temperature'     => 23.5,
			'humidity'        => 65,
		);

		$iot = IOT::from_array( $complex_data );

		$this->assertInstanceOf( IOT::class, $iot );

		// Check known fields are set correctly
		$this->assertSame( 'Complex Sensor', $iot->name );
		$this->assertSame( 'WH31_CH3', $iot->default_title );
		$this->assertSame( 'complex123', $iot->device_id );
		$this->assertSame( '2.0.0', $iot->version );
		$this->assertSame( '2023-01-20 15:45:00', $iot->createtime );

		// Check additional data contains only non-known fields
		$expected_additional = array(
			'battery_level'   => '85%',
			'signal_strength' => 'excellent',
			'last_seen'       => '2023-01-20 16:00:00',
			'temperature'     => 23.5,
			'humidity'        => 65,
		);

		$this->assertSame( $expected_additional, $iot->additional_data );

		// Ensure known fields are NOT in additional data
		$this->assertArrayNotHasKey( 'name', $iot->additional_data );
		$this->assertArrayNotHasKey( 'default_title', $iot->additional_data );
		$this->assertArrayNotHasKey( 'device_id', $iot->additional_data );
		$this->assertArrayNotHasKey( 'version', $iot->additional_data );
		$this->assertArrayNotHasKey( 'createtime', $iot->additional_data );
	}

	/**
	 * @testdox It should handle array data with only additional fields.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_handles_only_additional_fields(): void {
		$additional_only_data = array(
			'custom_field1' => 'value1',
			'custom_field2' => 42,
			'custom_field3' => '1',
		);

		$iot = IOT::from_array( $additional_only_data );

		$this->assertInstanceOf( IOT::class, $iot );

		// All known fields should be empty
		$this->assertSame( '', $iot->name );
		$this->assertSame( '', $iot->default_title );
		$this->assertSame( '', $iot->device_id );
		$this->assertSame( '', $iot->version );
		$this->assertSame( '', $iot->createtime );

		// Additional data should contain all provided fields
		$this->assertSame( $additional_only_data, $iot->additional_data );
	}

	/**
	 * @testdox It should preserve data types in additional_data.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\IOT::from_array
	 */
	public function test_preserves_data_types_in_additional_data(): void {
		$typed_data = array(
			'name'       => 'Type Test Sensor',
			'string_val' => 'string',
			'int_val'    => 123,
			'float_val'  => 45.67,
			'bool_val'   => true,
			'array_val'  => array( 'nested', 'array' ),
			'null_val'   => null,
		);

		$iot = IOT::from_array( $typed_data );

		$this->assertInstanceOf( IOT::class, $iot );
		$this->assertSame( 'Type Test Sensor', $iot->name );

		// Check that data types are preserved in additional_data
		$this->assertIsString( $iot->additional_data['string_val'] );
		$this->assertSame( 'string', $iot->additional_data['string_val'] );

		$this->assertIsInt( $iot->additional_data['int_val'] );
		$this->assertSame( 123, $iot->additional_data['int_val'] );

		$this->assertIsFloat( $iot->additional_data['float_val'] );
		$this->assertSame( 45.67, $iot->additional_data['float_val'] );

		$this->assertIsBool( $iot->additional_data['bool_val'] );
		$this->assertTrue( $iot->additional_data['bool_val'] );

		$this->assertIsArray( $iot->additional_data['array_val'] );
		$this->assertSame( array( 'nested', 'array' ), $iot->additional_data['array_val'] );

		$this->assertNull( $iot->additional_data['null_val'] );
	}
}
