<?php

/**
 * Unit tests for all Measurement classes.
 *
 * @package PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use WP_UnitTestCase;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Test class for all Measurement classes.
 *
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed
 */
class Test_Measurement_Classes extends WP_UnitTestCase {

	/**
	 * Data provider for all measurement classes
	 *
	 * @return array Array of [class_name, expected_type, expected_constants, constructor_type_arg]
	 */
	public function measurement_classes_data_provider(): array {
		return array(
			// Air Quality - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_AIR_QUALITY,
				array(),
				Base_Measurement::TYPE_AIR_QUALITY,
			),
			// Battery - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_BATTERY,
				array(),
				Base_Measurement::TYPE_BATTERY,
			),
			// CO2 - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_CO2,
				array(),
				Base_Measurement::TYPE_CO2,
			),
			// Count - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_COUNT,
				array(),
				Base_Measurement::TYPE_COUNT,
			),
			// Distance - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_DISTANCE,
				array(),
				Base_Measurement::TYPE_DISTANCE,
			),
			// Energy - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_ENERGY,
				array(),
				Base_Measurement::TYPE_ENERGY,
			),
			// Flow Rate - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_FLOW_RATE,
				array(),
				Base_Measurement::TYPE_FLOW_RATE,
			),
			// Humidity - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_HUMIDITY,
				array(),
				Base_Measurement::TYPE_HUMIDITY,
			),
			// Leaf Wetness - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_LEAF_WETNESS,
				array(),
				Base_Measurement::TYPE_LEAF_WETNESS,
			),
			// Percentage - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_PERCENTAGE,
				array(),
				Base_Measurement::TYPE_PERCENTAGE,
			),
			// Power - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_POWER,
				array(),
				Base_Measurement::TYPE_POWER,
			),
			// Pressure - has unit constants (subclass)
			array(
				Pressure::class,
				Base_Measurement::TYPE_PRESSURE,
				array( 'UNIT_HPA', 'UNIT_INHG', 'UNIT_MMHG' ),
				null,
			),
			// Rainfall - has unit constants (subclass)
			array(
				Rainfall::class,
				Base_Measurement::TYPE_RAINFALL,
				array( 'UNIT_MILLIMETERS', 'UNIT_INCHES' ),
				null,
			),
			// Rain Rate - has unit constants (subclass)
			array(
				Rain_Rate::class,
				Base_Measurement::TYPE_RAIN_RATE,
				array( 'UNIT_INCHES_PER_HOUR', 'UNIT_MILLIMETERS_PER_HOUR' ),
				null,
			),
			// Soil Moisture - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_SOIL_MOISTURE,
				array(),
				Base_Measurement::TYPE_SOIL_MOISTURE,
			),
			// Solar Radiation - has unit constants (subclass)
			array(
				Solar_Radiation::class,
				Base_Measurement::TYPE_SOLAR_RADIATION,
				array( 'UNIT_LUX', 'UNIT_FOOT_CANDLES', 'UNIT_WATTS_PER_SQUARE_METER' ),
				null,
			),
			// Temperature - has unit constants (subclass)
			array(
				Temperature::class,
				Base_Measurement::TYPE_TEMPERATURE,
				array( 'UNIT_CELSIUS', 'UNIT_FAHRENHEIT' ),
				null,
			),
			// UV Index - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_UV_INDEX,
				array(),
				Base_Measurement::TYPE_UV_INDEX,
			),
			// Voltage - handled by Base_Measurement with type parameter
			array(
				Base_Measurement::class,
				Base_Measurement::TYPE_VOLTAGE,
				array(),
				Base_Measurement::TYPE_VOLTAGE,
			),
			// Volume - has unit constants (subclass)
			array(
				Volume::class,
				Base_Measurement::TYPE_VOLUME,
				array( 'UNIT_LITERS', 'UNIT_CUBIC_METERS', 'UNIT_GALLONS' ),
				null,
			),
			// Wind Direction - has unit constants (subclass)
			array(
				Wind_Direction::class,
				Base_Measurement::TYPE_WIND_DIRECTION,
				array( 'UNIT_DEGREES', 'UNIT_CARDINAL' ),
				null,
			),
			// Wind Speed - has unit constants (subclass)
			array(
				Wind_Speed::class,
				Base_Measurement::TYPE_WIND_SPEED,
				array( 'UNIT_METERS_PER_SECOND', 'UNIT_KILOMETERS_PER_HOUR', 'UNIT_KNOTS', 'UNIT_MILES_PER_HOUR', 'UNIT_BEAUFORT', 'UNIT_FEET_PER_MINUTE' ),
				null,
			),
		);
	}

	/**
	 * @testdox It should return correct type for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_return_correct_type( string $class_name, string $expected_type, array $expected_constants, ?string $constructor_type = null ): void {
		// Create a mock DTO for the measurement
		$measurement_dto = new Measurement( '1.0', 'test_unit', '1642248600' );

		// Create instance of the measurement class
		if ( $constructor_type !== null ) {
			$measurement = new $class_name( $measurement_dto, $constructor_type );
		} else {
			$measurement = new $class_name( $measurement_dto );
		}

		// Test that get_type() returns the expected type
		$this->assertSame( $expected_type, $measurement->get_type() );
	}

	/**
	 * @testdox It should have all expected unit constants for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_have_expected_constants( string $class_name, string $expected_type, array $expected_constants, ?string $constructor_type = null ): void {
		// Test that all expected constants exist
		foreach ( $expected_constants as $constant_name ) {
			$this->assertTrue(
				defined( $class_name . '::' . $constant_name ),
				"Constant {$constant_name} should exist in {$class_name}"
			);
		}

		// If no constants are expected, verify that no UNIT_ constants exist
		if ( empty( $expected_constants ) ) {
			$reflection = new \ReflectionClass( $class_name );
			$constants  = $reflection->getConstants();

			foreach ( $constants as $constant_name => $constant_value ) {
				$this->assertFalse(
					str_starts_with( $constant_name, 'UNIT_' ),
					"{$class_name} should not have any UNIT_ constants, but found {$constant_name}"
				);
			}
		}
	}

	/**
	 * @testdox It should extend Base_Measurement for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_extend_base_measurement( string $class_name, string $expected_type, array $expected_constants, ?string $constructor_type = null ): void {
		if ( $class_name === Base_Measurement::class ) {
			$this->assertTrue( true, 'Base_Measurement is the base class itself' );
		} else {
			// Test that the class extends Base_Measurement
			$this->assertTrue(
				is_subclass_of( $class_name, Base_Measurement::class ),
				"{$class_name} should extend Base_Measurement"
			);
		}
	}

	/**
	 * @testdox It should be instantiable with Measurement DTO for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_are_instantiable( string $class_name, string $expected_type, array $expected_constants, ?string $constructor_type = null ): void {
		// Create a mock DTO for the measurement
		$measurement_dto = new Measurement( '1.0', 'test_unit', '1642248600' );

		// Test that the class can be instantiated
		if ( $constructor_type !== null ) {
			$measurement = new $class_name( $measurement_dto, $constructor_type );
		} else {
			$measurement = new $class_name( $measurement_dto );
		}

		$this->assertInstanceOf( $class_name, $measurement );
		$this->assertInstanceOf( Base_Measurement::class, $measurement );
	}
}
