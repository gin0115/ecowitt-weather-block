<?php

/**
 * History Observation DTO for Ecowitt API v3 history responses.
 *
 * Unlike the real-time Observation (single value per field),
 * this holds time-series arrays of Base_Measurement per field.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * History Observation DTO class for Ecowitt API v3.
 */
class History_Observation {

	/**
	 * All observation groups.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $observations;

	/**
	 * Indoor observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $indoor;

	/**
	 * Outdoor observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $outdoor;

	/**
	 * Solar and UV observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $solar_and_uvi;

	/**
	 * Rainfall observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $rainfall;

	/**
	 * Piezo rainfall observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $rainfall_piezo;

	/**
	 * Wind observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $wind;

	/**
	 * Pressure observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $pressure;

	/**
	 * Lightning observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $lightning;

	/**
	 * Indoor CO2 observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $indoor_co2;

	/**
	 * CO2 AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $co2_aqi_combo;

	/**
	 * PM2.5 AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $pm25_aqi_combo;

	/**
	 * PM10 AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $pm10_aqi_combo;

	/**
	 * PM1 AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $pm1_aqi_combo;

	/**
	 * PM4 AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $pm4_aqi_combo;

	/**
	 * Temperature and humidity AQI combo observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $t_rh_aqi_combo;

	/**
	 * Water leak observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $water_leak;

	/**
	 * PM2.5 channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $pm25_channels;

	/**
	 * Temperature and humidity channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $temp_and_humidity_channels;

	/**
	 * Soil channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $soil_channels;

	/**
	 * Temperature channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $temp_channels;

	/**
	 * Leaf channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $leaf_channels;

	/**
	 * Battery observations.
	 *
	 * @var array<string, Base_Measurement[]>
	 */
	public array $battery;

	/**
	 * LDS channel observations.
	 *
	 * @var array<string, array<string, Base_Measurement[]>>
	 */
	public array $lds_channels;

	/**
	 * Constructor.
	 *
	 * @param array<string, array<string, Base_Measurement[]>> $observations All observation groups.
	 */
	public function __construct( array $observations ) {
		$this->observations               = $observations;
		$this->indoor                     = $observations['indoor'] ?? array();
		$this->outdoor                    = $observations['outdoor'] ?? array();
		$this->solar_and_uvi              = $observations['solar_and_uvi'] ?? array();
		$this->rainfall                   = $observations['rainfall'] ?? array();
		$this->rainfall_piezo             = $observations['rainfall_piezo'] ?? array();
		$this->wind                       = $observations['wind'] ?? array();
		$this->pressure                   = $observations['pressure'] ?? array();
		$this->lightning                  = $observations['lightning'] ?? array();
		$this->indoor_co2                 = $observations['indoor_co2'] ?? array();
		$this->co2_aqi_combo              = $observations['co2_aqi_combo'] ?? array();
		$this->pm25_aqi_combo             = $observations['pm25_aqi_combo'] ?? array();
		$this->pm10_aqi_combo             = $observations['pm10_aqi_combo'] ?? array();
		$this->pm1_aqi_combo              = $observations['pm1_aqi_combo'] ?? array();
		$this->pm4_aqi_combo              = $observations['pm4_aqi_combo'] ?? array();
		$this->t_rh_aqi_combo             = $observations['t_rh_aqi_combo'] ?? array();
		$this->water_leak                 = $observations['water_leak'] ?? array();
		$this->pm25_channels              = $observations['pm25_channels'] ?? array(); // @phpstan-ignore assign.propertyType
		$this->temp_and_humidity_channels = $this->map_temp_and_humidity_channels( $observations );
		$this->soil_channels              = $observations['soil_channels'] ?? array(); // @phpstan-ignore assign.propertyType
		$this->temp_channels              = $observations['temp_channels'] ?? array(); // @phpstan-ignore assign.propertyType
		$this->leaf_channels              = $observations['leaf_channels'] ?? array(); // @phpstan-ignore assign.propertyType
		$this->battery                    = $observations['battery'] ?? array();
		$this->lds_channels               = $observations['lds_channels'] ?? array(); // @phpstan-ignore assign.propertyType
	}

	/**
	 * Map the temp and humidity channels.
	 *
	 * @param array<string, array<string, Base_Measurement[]>> $observations Observations.
	 * @return array<string, array<string, Base_Measurement[]>> Temp and humidity channels.
	 */
	private function map_temp_and_humidity_channels( array $observations ): array {
		$temp_and_humidity_channels = array();
		foreach ( $observations as $key => $observation ) {
			if ( strpos( $key, 'temp_and_humidity_ch' ) === 0 ) {
				$temp_and_humidity_channels[ $key ] = $observation;
			}
		}
		return $temp_and_humidity_channels;
	}

	/**
	 * Create History_Observation from data array.
	 *
	 * @param array<string, array<string, Base_Measurement[]>> $data Observation data.
	 * @return History_Observation Instance.
	 */
	public static function from_array( array $data ): History_Observation {
		return new self( $data );
	}
}
