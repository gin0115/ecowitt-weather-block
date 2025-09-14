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
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Air_Quality;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Battery;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\CO2;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Count;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Distance;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Energy;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Flow_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Leaf_Wetness;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Percentage;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Power;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Soil_Moisture;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\UV_Index;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Voltage;
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
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Air_Quality
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Battery
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\CO2
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Count
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Distance
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Energy
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Flow_Rate
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Leaf_Wetness
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Percentage
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Power
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Soil_Moisture
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\UV_Index
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Voltage
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed
 */
class Test_Measurement_Classes extends WP_UnitTestCase {

	/**
	 * Data provider for all measurement classes
	 *
	 * @return array Array of [class_name, expected_type, expected_constants]
	 */
	public function measurement_classes_data_provider(): array {
		return array(
			// Air Quality - no unit constants
			array(
				Air_Quality::class,
				Base_Measurement::TYPE_AIR_QUALITY,
				array(),
			),
			// Battery - no unit constants
			array(
				Battery::class,
				Base_Measurement::TYPE_BATTERY,
				array(),
			),
			// CO2 - no unit constants
			array(
				CO2::class,
				Base_Measurement::TYPE_CO2,
				array(),
			),
			// Count - no unit constants
			array(
				Count::class,
				Base_Measurement::TYPE_COUNT,
				array(),
			),
			// Distance - no unit constants
			array(
				Distance::class,
				Base_Measurement::TYPE_DISTANCE,
				array(),
			),
			// Energy - no unit constants
			array(
				Energy::class,
				Base_Measurement::TYPE_ENERGY,
				array(),
			),
			// Flow Rate - no unit constants
			array(
				Flow_Rate::class,
				Base_Measurement::TYPE_FLOW_RATE,
				array(),
			),
			// Humidity - no unit constants
			array(
				Humidity::class,
				Base_Measurement::TYPE_HUMIDITY,
				array(),
			),
			// Leaf Wetness - no unit constants
			array(
				Leaf_Wetness::class,
				Base_Measurement::TYPE_LEAF_WETNESS,
				array(),
			),
			// Percentage - no unit constants
			array(
				Percentage::class,
				Base_Measurement::TYPE_PERCENTAGE,
				array(),
			),
			// Power - no unit constants
			array(
				Power::class,
				Base_Measurement::TYPE_POWER,
				array(),
			),
			// Pressure - has unit constants
			array(
				Pressure::class,
				Base_Measurement::TYPE_PRESSURE,
				array( 'UNIT_HPA', 'UNIT_INHG', 'UNIT_MMHG' ),
			),
			// Rainfall - has unit constants
			array(
				Rainfall::class,
				Base_Measurement::TYPE_RAINFALL,
				array( 'UNIT_MILLIMETERS', 'UNIT_INCHES' ),
			),
			// Rain Rate - has unit constants
			array(
				Rain_Rate::class,
				Base_Measurement::TYPE_RAIN_RATE,
				array( 'UNIT_INCHES_PER_HOUR', 'UNIT_MILLIMETERS_PER_HOUR' ),
			),
			// Soil Moisture - no unit constants
			array(
				Soil_Moisture::class,
				Base_Measurement::TYPE_SOIL_MOISTURE,
				array(),
			),
			// Solar Radiation - has unit constants
			array(
				Solar_Radiation::class,
				Base_Measurement::TYPE_SOLAR_RADIATION,
				array( 'UNIT_LUX', 'UNIT_FOOT_CANDLES', 'UNIT_WATTS_PER_SQUARE_METER' ),
			),
			// Temperature - has unit constants
			array(
				Temperature::class,
				Base_Measurement::TYPE_TEMPERATURE,
				array( 'UNIT_CELSIUS', 'UNIT_FAHRENHEIT' ),
			),
			// UV Index - no unit constants
			array(
				UV_Index::class,
				Base_Measurement::TYPE_UV_INDEX,
				array(),
			),
			// Voltage - no unit constants
			array(
				Voltage::class,
				Base_Measurement::TYPE_VOLTAGE,
				array(),
			),
			// Volume - has unit constants
			array(
				Volume::class,
				Base_Measurement::TYPE_VOLUME,
				array( 'UNIT_LITERS', 'UNIT_CUBIC_METERS', 'UNIT_GALLONS' ),
			),
			// Wind Direction - has unit constants
			array(
				Wind_Direction::class,
				Base_Measurement::TYPE_WIND_DIRECTION,
				array( 'UNIT_DEGREES', 'UNIT_CARDINAL' ),
			),
			// Wind Speed - has unit constants
			array(
				Wind_Speed::class,
				Base_Measurement::TYPE_WIND_SPEED,
				array( 'UNIT_METERS_PER_SECOND', 'UNIT_KILOMETERS_PER_HOUR', 'UNIT_KNOTS', 'UNIT_MILES_PER_HOUR', 'UNIT_BEAUFORT', 'UNIT_FEET_PER_MINUTE' ),
			),
		);
	}

	/**
	 * @testdox It should return correct type for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_return_correct_type( string $class_name, string $expected_type, array $expected_constants ): void {
		// Create a mock DTO for the measurement
		$measurement_dto = new Measurement( '1.0', 'test_unit', '1642248600' );
		// Create instance of the measurement class
		$measurement = new $class_name( $measurement_dto );

		// Test that get_type() returns the expected type
		$this->assertSame( $expected_type, $measurement->get_type() );
	}

	/**
	 * @testdox It should have all expected unit constants for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_have_expected_constants( string $class_name, string $expected_type, array $expected_constants ): void {
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
	public function test_measurement_classes_extend_base_measurement( string $class_name, string $expected_type, array $expected_constants ): void {
		// Test that the class extends Base_Measurement
		$this->assertTrue(
			is_subclass_of( $class_name, Base_Measurement::class ),
			"{$class_name} should extend Base_Measurement"
		);
	}

	/**
	 * @testdox It should be instantiable with Measurement DTO for all measurement classes
	 * @dataProvider measurement_classes_data_provider
	 */
	public function test_measurement_classes_are_instantiable( string $class_name, string $expected_type, array $expected_constants ): void {
		// Create a mock DTO for the measurement
		$measurement_dto = new Measurement( '1.0', 'test_unit', '1642248600' );

		// Test that the class can be instantiated
		$measurement = new $class_name( $measurement_dto );

		$this->assertInstanceOf( $class_name, $measurement );
		$this->assertInstanceOf( Base_Measurement::class, $measurement );
	}
}
