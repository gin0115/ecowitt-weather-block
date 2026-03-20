<?php

/**
 * Conversion configuration data.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion;

use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Configuration class for unit conversion data.
 */
class Conversion_Config implements Conversion_Config_Interface {

	/**
	 * Get the conversion configuration data.
	 *
	 * @return array<string, array{
	 *     base_unit: string,
	 *     base_unit_id: int,
	 *     units: array<int, string>,
	 *     to_base_conversions: array<string, callable>,
	 *     from_base_conversions: array<string, callable>,
	 *     format: array<string, callable>
	 * }> Configuration data for all measurement types.
	 */
	public function get(): array {
			return array(
				Base_Measurement::TYPE_TEMPERATURE     => array(
					'base_unit'             => Temperature::UNIT_FAHRENHEIT,
					'base_unit_id'          => 2,
					'units'                 => array(
						1 => Temperature::UNIT_CELSIUS,
						2 => Temperature::UNIT_FAHRENHEIT,
					),
					'to_base_conversions'   => array(
						Temperature::UNIT_CELSIUS    => fn( $value ) => ( $value * 1.8 ) + 32,
						Temperature::UNIT_FAHRENHEIT => fn( $value ) => $value,
					),
					'from_base_conversions' => array(
						Temperature::UNIT_CELSIUS    => fn( $value ) => ( $value - 32 ) * ( 5 / 9 ),
						Temperature::UNIT_FAHRENHEIT => fn( $value ) => $value,
					),
					'format'                => array(
						Temperature::UNIT_CELSIUS    => fn( $value ) => round( $value, 1 ),
						Temperature::UNIT_FAHRENHEIT => fn( $value ) => (int) $value,
					),
				),
				Base_Measurement::TYPE_PRESSURE        => array(
					'base_unit'             => Pressure::UNIT_INHG,
					'base_unit_id'          => 4,
					'units'                 => array(
						3 => Pressure::UNIT_HPA,
						4 => Pressure::UNIT_INHG,
						5 => Pressure::UNIT_MMHG,
					),
					'to_base_conversions'   => array(
						Pressure::UNIT_HPA  => fn( $value ) => $value * 0.02953,
						Pressure::UNIT_INHG => fn( $value ) => $value,
						Pressure::UNIT_MMHG => fn( $value ) => $value * 0.03937,
					),
					'from_base_conversions' => array(
						Pressure::UNIT_HPA  => fn( $value ) => $value * 33.8639,
						Pressure::UNIT_INHG => fn( $value ) => $value,
						Pressure::UNIT_MMHG => fn( $value ) => $value * 25.4,
					),
					'format'                => array(
						Pressure::UNIT_HPA  => fn( $value ) => round( $value, 2 ),
						Pressure::UNIT_INHG => fn( $value ) => round( $value, 2 ),
						Pressure::UNIT_MMHG => fn( $value ) => round( $value, 1 ),
					),
				),
				Base_Measurement::TYPE_WIND_SPEED      => array(
					'base_unit'             => Wind_Speed::UNIT_MILES_PER_HOUR,
					'base_unit_id'          => 9,
					'units'                 => array(
						6  => Wind_Speed::UNIT_METERS_PER_SECOND,
						7  => Wind_Speed::UNIT_KILOMETERS_PER_HOUR,
						8  => Wind_Speed::UNIT_KNOTS,
						9  => Wind_Speed::UNIT_MILES_PER_HOUR,
						10 => Wind_Speed::UNIT_BEAUFORT,
						11 => Wind_Speed::UNIT_FEET_PER_MINUTE,
					),
					'to_base_conversions'   => array(
						Wind_Speed::UNIT_METERS_PER_SECOND => fn( $value ) => $value * 2.23694,
						Wind_Speed::UNIT_KILOMETERS_PER_HOUR => fn( $value ) => $value * 0.621371,
						Wind_Speed::UNIT_KNOTS             => fn( $value ) => $value * 1.15078,
						Wind_Speed::UNIT_MILES_PER_HOUR    => fn( $value ) => $value,
						Wind_Speed::UNIT_BEAUFORT          => fn( $value ) => $value,
						Wind_Speed::UNIT_FEET_PER_MINUTE   => fn( $value ) => $value * 0.01136,
					),
					'from_base_conversions' => array(
						Wind_Speed::UNIT_METERS_PER_SECOND => fn( $value ) => $value * 0.44704,
						Wind_Speed::UNIT_KILOMETERS_PER_HOUR => fn( $value ) => $value * 1.60934,
						Wind_Speed::UNIT_KNOTS             => fn( $value ) => $value * 0.868976,
						Wind_Speed::UNIT_MILES_PER_HOUR    => fn( $value ) => $value,
						Wind_Speed::UNIT_BEAUFORT          => fn( $value ) => $value,
						Wind_Speed::UNIT_FEET_PER_MINUTE   => fn( $value ) => $value * 88.0,
					),
					'format'                => array(
						Wind_Speed::UNIT_METERS_PER_SECOND => fn( $value ) => round( $value, 1 ),
						Wind_Speed::UNIT_KILOMETERS_PER_HOUR => fn( $value ) => (int) $value,
						Wind_Speed::UNIT_KNOTS             => fn( $value ) => round( $value, 1 ),
						Wind_Speed::UNIT_MILES_PER_HOUR    => fn( $value ) => (int) $value,
						Wind_Speed::UNIT_BEAUFORT          => fn( $value ) => (int) $value,
						Wind_Speed::UNIT_FEET_PER_MINUTE   => fn( $value ) => (int) $value,
					),
				),
				Base_Measurement::TYPE_RAINFALL        => array(
					'base_unit'             => Rainfall::UNIT_INCHES,
					'base_unit_id'          => 13,
					'units'                 => array(
						12 => Rainfall::UNIT_MILLIMETERS,
						13 => Rainfall::UNIT_INCHES,
					),
					'to_base_conversions'   => array(
						Rainfall::UNIT_MILLIMETERS => fn( $value ) => $value * 0.0393701,
						Rainfall::UNIT_INCHES      => fn( $value ) => $value,
					),
					'from_base_conversions' => array(
						Rainfall::UNIT_MILLIMETERS => fn( $value ) => $value * 25.4,
						Rainfall::UNIT_INCHES      => fn( $value ) => $value,
					),
					'format'                => array(
						Rainfall::UNIT_MILLIMETERS => fn( $value ) => round( $value, 1 ),
						Rainfall::UNIT_INCHES      => fn( $value ) => round( $value, 2 ),
					),
				),
				Base_Measurement::TYPE_RAIN_RATE       => array(
					'base_unit'             => Rain_Rate::UNIT_INCHES_PER_HOUR,
					'base_unit_id'          => 28,
					'units'                 => array(
						27 => Rain_Rate::UNIT_MILLIMETERS_PER_HOUR,
						28 => Rain_Rate::UNIT_INCHES_PER_HOUR,
					),
					'to_base_conversions'   => array(
						Rain_Rate::UNIT_MILLIMETERS_PER_HOUR => fn( $value ) => $value * 0.0393701,
						Rain_Rate::UNIT_INCHES_PER_HOUR => fn( $value ) => $value,
					),
					'from_base_conversions' => array(
						Rain_Rate::UNIT_MILLIMETERS_PER_HOUR => fn( $value ) => $value * 25.4,
						Rain_Rate::UNIT_INCHES_PER_HOUR => fn( $value ) => $value,
					),
					'format'                => array(
						Rain_Rate::UNIT_MILLIMETERS_PER_HOUR => fn( $value ) => round( $value, 1 ),
						Rain_Rate::UNIT_INCHES_PER_HOUR => fn( $value ) => round( $value, 2 ),
					),
				),
				Base_Measurement::TYPE_SOLAR_RADIATION => array(
					'base_unit'             => Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER,
					'base_unit_id'          => 16,
					'units'                 => array(
						14 => Solar_Radiation::UNIT_LUX,
						15 => Solar_Radiation::UNIT_FOOT_CANDLES,
						16 => Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER,
					),
					'to_base_conversions'   => array(
						Solar_Radiation::UNIT_LUX          => fn( $value ) => $value * 0.0079,
						Solar_Radiation::UNIT_FOOT_CANDLES => fn( $value ) => $value * 0.0929,
						Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER => fn( $value ) => $value,
					),
					'from_base_conversions' => array(
						Solar_Radiation::UNIT_LUX          => fn( $value ) => $value * 126.58,
						Solar_Radiation::UNIT_FOOT_CANDLES => fn( $value ) => $value * 10.764,
						Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER => fn( $value ) => $value,
					),
					'format'                => array(
						Solar_Radiation::UNIT_LUX          => fn( $value ) => (int) $value,
						Solar_Radiation::UNIT_FOOT_CANDLES => fn( $value ) => round( $value, 1 ),
						Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER => fn( $value ) => round( $value, 1 ),
					),
				),
				Base_Measurement::TYPE_VOLUME          => array(
					'base_unit'             => Volume::UNIT_LITERS,
					'base_unit_id'          => 24,
					'units'                 => array(
						24 => Volume::UNIT_LITERS,
						25 => Volume::UNIT_CUBIC_METERS,
						26 => Volume::UNIT_GALLONS,
					),
					'to_base_conversions'   => array(
						Volume::UNIT_LITERS       => fn( $value ) => $value,
						Volume::UNIT_CUBIC_METERS => fn( $value ) => $value * 1000.0,
						Volume::UNIT_GALLONS      => fn( $value ) => $value * 0.264172,
					),
					'from_base_conversions' => array(
						Volume::UNIT_LITERS       => fn( $value ) => $value,
						Volume::UNIT_CUBIC_METERS => fn( $value ) => $value * 0.001,
						Volume::UNIT_GALLONS      => fn( $value ) => $value * 3.78541,
					),
					'format'                => array(
						Volume::UNIT_LITERS       => fn( $value ) => round( $value, 1 ),
						Volume::UNIT_CUBIC_METERS => fn( $value ) => round( $value, 3 ),
						Volume::UNIT_GALLONS      => fn( $value ) => round( $value, 2 ),
					),
				),
				Base_Measurement::TYPE_WIND_DIRECTION  => array(
					'base_unit'             => 'deg',
					'base_unit_id'          => 1,
					'units'                 => array(
						1 => 'deg',
						2 => 'cardinal',
					),
					'to_base_conversions'   => array(
						'deg'      => fn( $value ) => $value,
						'cardinal' => array( $this, 'compass_to_degrees' ),
					),
					'from_base_conversions' => array(
						'deg'      => fn( $value ) => $value,
						'cardinal' => array( $this, 'degrees_to_compass' ),
					),
					'format'                => array(
						'deg'      => fn( $value ) => (int) $value,
						'cardinal' => fn( $value ) => $value,
					),
				),
			);
	}

	/**
	 * Convert degrees to compass direction.
	 *
	 * @param float $degrees The degree value (0-360).
	 * @return string The compass direction.
	 */
	public function degrees_to_compass( $degrees ): string {
		$degrees    = (float) $degrees;
		$directions = array(
			'N',
			'NNE',
			'NE',
			'ENE',
			'E',
			'ESE',
			'SE',
			'SSE',
			'S',
			'SSW',
			'SW',
			'WSW',
			'W',
			'WNW',
			'NW',
			'NNW',
		);

		// Convert negative degrees to positive using absint()
		$degrees = absint( $degrees );

		$index = round( $degrees / 22.5 ) % 16;
		return $directions[ $index ];
	}

	/**
	 * Convert compass direction to degrees.
	 *
	 * @param string $compass The compass direction.
	 * @return float The degree value.
	 */
	public function compass_to_degrees( string $compass ): float {
		$directions = array(
			'N'   => 0,
			'NNE' => 22.5,
			'NE'  => 45,
			'ENE' => 67.5,
			'E'   => 90,
			'ESE' => 112.5,
			'SE'  => 135,
			'SSE' => 157.5,
			'S'   => 180,
			'SSW' => 202.5,
			'SW'  => 225,
			'WSW' => 247.5,
			'W'   => 270,
			'WNW' => 292.5,
			'NW'  => 315,
			'NNW' => 337.5,
		);

		return $directions[ $compass ] ?? 0.0;
	}
}
