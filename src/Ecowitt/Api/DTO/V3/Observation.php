<?php

/**
 * Observation DTO for Ecowitt API v3 responses.
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
 * Observation DTO class for Ecowitt API v3.
 *
 * TODO: Implement observation properties based on API response structure
 */
class Observation {

	/**
	 * Group of observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $observations;

	/**
	 * Indoor observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $indoor;

	/**
	 * Outdoor observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $outdoor;

	/**
	 * Solar and UV observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $solar_and_uvi;

	/**
	 * Rainfall observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $rainfall;

	/**
	 * Piezo rainfall observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $rainfall_piezo;

	/**
	 * Wind observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $wind;

	/**
	 * Pressure observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $pressure;

	/**
	 * Lightning observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $lightning;

	/**
	 * Indoor CO2 observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $indoor_co2;

	/**
	 * CO2 AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $co2_aqi_combo;

	/**
	 * PM2.5 AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $pm25_aqi_combo;

	/**
	 * PM10 AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $pm10_aqi_combo;

	/**
	 * PM1 AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $pm1_aqi_combo;

	/**
	 * PM4 AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $pm4_aqi_combo;

	/**
	 * Temperature and humidity AQI combo observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $t_rh_aqi_combo;

	/**
	 * Water leak observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $water_leak;

	/**
	 * PM2.5 channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $pm25_channels;

	/**
	 * Temperature and humidity channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $temp_and_humidity_channels;

	/**
	 * Soil channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $soil_channels;

	/**
	 * Temperature channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $temp_channels;

	/**
	 * Leaf channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $leaf_channels;

	/**
	 * Battery observations.
	 *
	 * @var array<string, Measurement>
	 */
	public array $battery;

	/**
	 * LDS channel observations.
	 *
	 * @var array<string, array<string, Measurement>>
	 */
	public array $lds_channels;

	/**
	 * Placeholder constructor.
	 */
	public function __construct( array $observations ) {
		$this->observations = $observations;
// adie($observations);
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
		$this->pm25_channels              = $observations['pm25_channels'] ?? array();
		$this->temp_and_humidity_channels = $this->map_temp_and_humidity_channels($observations);
		$this->soil_channels              = $observations['soil_channels'] ?? array();
		$this->temp_channels              = $observations['temp_channels'] ?? array();
		$this->leaf_channels              = $observations['leaf_channels'] ?? array();
		$this->battery                    = $observations['battery'] ?? array();
		$this->lds_channels               = $observations['lds_channels'] ?? array();
	}

	/**
	 * Map the temp and humidity channels.
	 *
	 * @param array<string, Measurement> $observations Observations.
	 * @return array<string, array<string, Measurement>> Temp and humidity channels.
	 */
	private function map_temp_and_humidity_channels( array $observations ): array {
		// Look for any keys that start with temp_and_humidity_ch{d} and return the array of measurements.
		$temp_and_humidity_channels = array();
		foreach ($observations as $key => $observation) {
			if (strpos($key, 'temp_and_humidity_ch') === 0) {
				$temp_and_humidity_channels[$key] = $observation;
			}
		}
		return $temp_and_humidity_channels;
	}

	/**
	 * Create Observation from API v3 response array.
	 *
	 * @param array<string, mixed> $data Observation data from API v3 response.
	 * @return Observation Observation instance.
	 */
	public static function from_array( array $data ): Observation {
		// TODO: Implement observation creation from array
		return new self( $data );
	}
}
