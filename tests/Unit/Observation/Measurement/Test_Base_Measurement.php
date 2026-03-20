<?php

/**
 * Tests for the Base_Measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Base_Measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 */
class Test_Base_Measurement extends \WP_UnitTestCase {

	/**
	 * @testdox It should return the correct value from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::get_value
	 */
	public function test_can_get_value(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '1642248600' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertSame( '25.5', $measurement->get_value() );
	}

	/**
	 * @testdox It should return the correct unit from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::get_unit
	 */
	public function test_can_get_unit(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '1642248600' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertSame( 'C', $measurement->get_unit() );
	}

	/**
	 * @testdox It should return the correct timestamp as DateTime object.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::get_timestamp
	 */
	public function test_can_get_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '1642248600' );
		$measurement = new Temperature( $measurement_dto );
		$timestamp = $measurement->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle empty timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::__construct
	 */
	public function test_handles_empty_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * @testdox It should handle invalid timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::__construct
	 */
	public function test_handles_invalid_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', 'invalid_timestamp' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * Data provider for timestamp validation tests
	 * 
	 * @return array Array of [timestamp_string, expected_result, description]
	 */
	public function timestamp_validation_data_provider(): array {
		return array(
			// Valid timestamps
			array( '1642248600', 'DateTime', 'Valid Unix timestamp' ),
			array( '1642248600.123', 'DateTime', 'Valid Unix timestamp with decimals (truncated)' ),
			array( '2147483647', 'DateTime', 'Maximum 32-bit Unix timestamp' ),
			array( '1000000000', 'DateTime', 'Large valid timestamp' ),
			array( '1e6', 'DateTime', 'Scientific notation (1000000)' ),
			
			// Invalid timestamps that should return null
			array( '', null, 'Empty string' ),
			array( '0', null, 'Zero timestamp' ),
			array( '-1', null, 'Negative timestamp' ),
			array( '-100', null, 'Large negative timestamp' ),
			array( 'invalid', null, 'Non-numeric string' ),
			array( 'abc123', null, 'Mixed alphanumeric string' ),
			array( '12.34.56', null, 'Multiple decimal points' ),
			array( '0x123', null, 'Hexadecimal string' ),
			array( 'null', null, 'String "null"' ),
			array( 'false', null, 'String "false"' ),
			array( 'true', null, 'String "true"' ),
			array( ' ', null, 'Whitespace only' ),
			array( '   ', null, 'Multiple spaces' ),
			array( "\t", null, 'Tab character' ),
			array( "\n", null, 'Newline character' ),
		);
	}

	/**
	 * @testdox It should handle all timestamp validation scenarios correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 * @dataProvider timestamp_validation_data_provider
	 */
	public function test_timestamp_validation_scenarios( string $timestamp_string, $expected_result, string $description ): void {
		$measurement_dto = new Measurement( '25.5', 'C', $timestamp_string );
		$measurement = new Temperature( $measurement_dto );
		$result = $measurement->get_timestamp();

		if ( $expected_result === 'DateTime' ) {
			$this->assertInstanceOf( DateTime::class, $result, $description );
			
			// For decimal timestamps, the method truncates to integer
			$expected_timestamp = is_numeric( $timestamp_string ) ? (string) (int) $timestamp_string : $timestamp_string;
			$this->assertSame( $expected_timestamp, $result->format( 'U' ), $description );
		} else {
			$this->assertNull( $result, $description );
		}
	}

	/**
	 * @testdox It should handle zero timestamp correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_zero_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '0' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * @testdox It should handle negative timestamp correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_negative_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '-100' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * @testdox It should handle non-numeric timestamp correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_non_numeric_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', 'not_a_number' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * @testdox It should handle timestamp with decimals correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_timestamp_with_decimals(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '1642248600.123' );
		$measurement = new Temperature( $measurement_dto );
		$timestamp = $measurement->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		// The decimal part should be truncated to integer
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle very large timestamp correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_very_large_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '2147483647' ); // Max 32-bit timestamp
		$measurement = new Temperature( $measurement_dto );
		$timestamp = $measurement->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '2147483647', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle whitespace-only timestamp correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement::parse_timestamp
	 */
	public function test_handles_whitespace_only_timestamp(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '   ' );
		$measurement = new Temperature( $measurement_dto );

		$this->assertNull( $measurement->get_timestamp() );
	}

	/**
	 * @testdox It should provide access to measurement type constants.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement
	 */
	public function test_provides_measurement_type_constants(): void {
		$this->assertSame( 'temperature', Base_Measurement::TYPE_TEMPERATURE );
		$this->assertSame( 'humidity', Base_Measurement::TYPE_HUMIDITY );
		$this->assertSame( 'pressure', Base_Measurement::TYPE_PRESSURE );
		$this->assertSame( 'wind_speed', Base_Measurement::TYPE_WIND_SPEED );
		$this->assertSame( 'wind_direction', Base_Measurement::TYPE_WIND_DIRECTION );
		$this->assertSame( 'rainfall', Base_Measurement::TYPE_RAINFALL );
		$this->assertSame( 'solar_radiation', Base_Measurement::TYPE_SOLAR_RADIATION );
		$this->assertSame( 'uv_index', Base_Measurement::TYPE_UV_INDEX );
		$this->assertSame( 'distance', Base_Measurement::TYPE_DISTANCE );
		$this->assertSame( 'air_quality', Base_Measurement::TYPE_AIR_QUALITY );
		$this->assertSame( 'co2', Base_Measurement::TYPE_CO2 );
		$this->assertSame( 'soil_moisture', Base_Measurement::TYPE_SOIL_MOISTURE );
		$this->assertSame( 'leaf_wetness', Base_Measurement::TYPE_LEAF_WETNESS );
		$this->assertSame( 'battery', Base_Measurement::TYPE_BATTERY );
		$this->assertSame( 'voltage', Base_Measurement::TYPE_VOLTAGE );
		$this->assertSame( 'power', Base_Measurement::TYPE_POWER );
		$this->assertSame( 'energy', Base_Measurement::TYPE_ENERGY );
		$this->assertSame( 'flow_rate', Base_Measurement::TYPE_FLOW_RATE );
		$this->assertSame( 'volume', Base_Measurement::TYPE_VOLUME );
		$this->assertSame( 'percentage', Base_Measurement::TYPE_PERCENTAGE );
		$this->assertSame( 'count', Base_Measurement::TYPE_COUNT );
	}

}
