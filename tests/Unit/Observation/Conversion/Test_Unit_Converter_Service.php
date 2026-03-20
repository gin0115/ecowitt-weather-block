<?php

/**
 * Unit tests for Unit_Converter_Service.
 *
 * @package PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion;

use WP_UnitTestCase;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config_Interface;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Unit_Conversion_Exception;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;
use DateTime;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// @codeCoverageIgnoreEnd

/**
 * Test class for Unit_Converter_Service.
 *
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service
 */
class Test_Unit_Converter_Service extends WP_UnitTestCase {

	/**
	 * @testdox It should convert temperature from Celsius to Fahrenheit correctly.
	 */
	public function test_converts_temperature_celsius_to_fahrenheit(): void {
		// Mock config with simple temperature conversion (C to F: * 1.8 + 32)
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertInstanceOf( Temperature::class, $converted );
		$this->assertEquals( Temperature::UNIT_FAHRENHEIT, $converted->get_unit() );
		$this->assertEquals( '77', $converted->get_value() );
		$this->assertEquals( Base_Measurement::TYPE_TEMPERATURE, $converted->get_type() );
	}

	/**
	 * @testdox It should convert temperature from Fahrenheit to Celsius correctly.
	 */
	public function test_converts_temperature_fahrenheit_to_celsius(): void {
		// Mock config with precise temperature conversion (F to C: (F - 32) * 5/9)
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '77.0', Temperature::UNIT_FAHRENHEIT, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_CELSIUS );

		$this->assertInstanceOf( Temperature::class, $converted );
		$this->assertEquals( Temperature::UNIT_CELSIUS, $converted->get_unit() );
		$this->assertEquals( '25', $converted->get_value() );
		$this->assertEquals( Base_Measurement::TYPE_TEMPERATURE, $converted->get_type() );
	}

	/**
	 * @testdox It should return the same measurement when converting to the same unit.
	 */
	public function test_returns_same_measurement_when_converting_to_same_unit(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_CELSIUS );

		$this->assertSame( $temperature, $converted );
	}

	/**
	 * @testdox It should preserve timestamp when converting measurements.
	 */
	public function test_preserves_timestamp_when_converting(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$original_timestamp = '1642248600';
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, $original_timestamp );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertInstanceOf( DateTime::class, $converted->get_timestamp() );
		$this->assertEquals( $original_timestamp, $converted->get_timestamp()->format( 'U' ) );
	}

	/**
	 * @testdox It should handle measurements with no timestamp.
	 */
	public function test_handles_measurements_with_no_timestamp(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertNull( $converted->get_timestamp() );
	}

	/**
	 * @testdox It should throw exception for unsupported measurement type.
	 */
	public function test_throws_exception_for_unsupported_measurement_type(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [] ); // Empty config - no supported types

		$converter = new Unit_Converter_Service( $mock_config );
		$mock_measurement = $this->createMock( Base_Measurement::class );
		$mock_measurement->method( 'get_type' )->willReturn( 'unsupported_type' );
		$mock_measurement->method( 'get_unit' )->willReturn( 'unsupported_unit' );
		$mock_measurement->method( 'get_value' )->willReturn( '25.0' );
		$mock_measurement->method( 'get_timestamp' )->willReturn( new DateTime( '@1642248600' ) );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'No conversion configuration found for measurement type: unsupported_type' );

		$converter->convert( $mock_measurement, Temperature::UNIT_FAHRENHEIT );
	}

	/**
	 * @testdox It should throw exception for unsupported target unit.
	 */
	public function test_throws_exception_for_unsupported_target_unit(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'Unit \'invalid_unit\' is not supported for measurement type \'temperature\'' );

		$converter->convert( $temperature, 'invalid_unit' );
	}

	/**
	 * @testdox It should throw exception for missing conversion formula from unit.
	 */
	public function test_throws_exception_for_missing_from_conversion_formula(): void {
		// Create a mock config that's missing conversion formulas
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [], // Missing conversions
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => [ 'factor' => 0.555556, 'offset' => -17.7778 ],
					Temperature::UNIT_FAHRENHEIT => [ 'factor' => 1.0, 'offset' => 0.0 ],
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'No to_base conversion formula found for unit: C' );

		$converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );
	}

	/**
	 * @testdox It should throw exception for missing conversion formula to unit.
	 */
	public function test_throws_exception_for_missing_to_conversion_formula(): void {
		// Create a mock config that's missing conversion formulas
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [], // Missing conversions
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'No from_base conversion formula found for unit: F' );

		$converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );
	}

	/**
	 * @testdox It should sanitize target unit input.
	 */
	public function test_sanitizes_target_unit_input(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		// Test with potentially malicious input
		$malicious_unit = '<script>alert("xss")</script>' . Temperature::UNIT_FAHRENHEIT;
		
		$converted = $converter->convert( $temperature, $malicious_unit );

		$this->assertEquals( Temperature::UNIT_FAHRENHEIT, $converted->get_unit() );
	}

	/**
	 * @testdox It should handle decimal temperature values correctly.
	 */
	public function test_handles_decimal_temperature_values(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => round($value, 1),
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		// 25.5°C should equal 77.9°F
		$measurement_dto = new Measurement( '25.5', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertEquals( '77.9', $converted->get_value() );
	}

	/**
	 * @testdox It should handle negative temperature values correctly.
	 */
	public function test_handles_negative_temperature_values(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		// -10°C should equal 14°F
		$measurement_dto = new Measurement( '-10.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertEquals( '14', $converted->get_value() );
	}

	/**
	 * @testdox It should handle zero temperature values correctly.
	 */
	public function test_handles_zero_temperature_values(): void {
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		// 0°C should equal 32°F
		$measurement_dto = new Measurement( '0.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		$this->assertEquals( '32', $converted->get_value() );
	}

	/**
	 * @testdox It should throw exception when to_base_conversions formula is not callable
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service::convert_to_base
	 */
	public function test_throws_exception_when_to_base_conversion_formula_is_not_callable(): void {
		// Create a mock config with non-callable conversion formula
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => 'not_a_callable', // Non-callable value
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'No to_base conversion formula found for unit: C' );

		$converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );
	}

	/**
	 * @testdox It should throw exception when from_base_conversions formula is not callable
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service::convert_from_base
	 */
	public function test_throws_exception_when_from_base_conversion_formula_is_not_callable(): void {
		// Create a mock config with non-callable conversion formula
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => 'not_a_callable', // Non-callable value
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => fn($value) => (int) $value,
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '77.0', Temperature::UNIT_FAHRENHEIT, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->expectException( Unit_Conversion_Exception::class );
		$this->expectExceptionMessage( 'No from_base conversion formula found for unit: C' );

		$converter->convert( $temperature, Temperature::UNIT_CELSIUS );
	}

	/**
	 * @testdox It should return original value when format configuration is missing
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service::apply_formatting
	 */
	public function test_returns_original_value_when_format_configuration_is_missing(): void {
		// Create a mock config without format configuration
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				// Missing 'format' configuration
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		// Should return the original value without formatting
		$this->assertEquals( '77', $converted->get_value() );
	}

	/**
	 * @testdox It should return original value when format function is not callable
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service::apply_formatting
	 */
	public function test_returns_original_value_when_format_function_is_not_callable(): void {
		// Create a mock config with non-callable format function
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					Temperature::UNIT_FAHRENHEIT => 'not_a_callable', // Non-callable format
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		// Should return the original value without formatting (77.0 instead of 77)
		$this->assertEquals( '77', $converted->get_value() );
	}

	/**
	 * @testdox It should return original value when specific unit format is missing
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Unit_Converter_Service::apply_formatting
	 */
	public function test_returns_original_value_when_specific_unit_format_is_missing(): void {
		// Create a mock config with missing format for specific unit
		$mock_config = $this->createMock( Conversion_Config_Interface::class );
		$mock_config->method( 'get' )->willReturn( [
			Base_Measurement::TYPE_TEMPERATURE => [
				'base_unit' => Temperature::UNIT_FAHRENHEIT,
				'base_unit_id' => 2,
				'units' => [
					1 => Temperature::UNIT_CELSIUS,
					2 => Temperature::UNIT_FAHRENHEIT,
				],
				'to_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value * 1.8) + 32,
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'from_base_conversions' => [
					Temperature::UNIT_CELSIUS => fn($value) => ($value - 32) * (5/9),
					Temperature::UNIT_FAHRENHEIT => fn($value) => $value,
				],
				'format' => [
					Temperature::UNIT_CELSIUS => fn($value) => round($value, 1),
					// Missing format for Temperature::UNIT_FAHRENHEIT
				],
			],
		] );

		$converter = new Unit_Converter_Service( $mock_config );
		$measurement_dto = new Measurement( '25.0', Temperature::UNIT_CELSIUS, '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$converted = $converter->convert( $temperature, Temperature::UNIT_FAHRENHEIT );

		// Should return the original value without formatting
		$this->assertEquals( '77', $converted->get_value() );
	}
}
