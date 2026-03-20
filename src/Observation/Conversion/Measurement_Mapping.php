<?php

/**
 * Measurement mapping configuration for converting DTOs to domain objects.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion;

use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Configuration class for measurement type mappings from API groups/keys to domain classes.
 *
 * Returns either a class-string (for types with unit constants) or a type constant string
 * (for simple types that use Base_Measurement directly).
 */
class Measurement_Mapping {

	/**
	 * Get all measurement mappings.
	 *
	 * @return array<string, array<string, string>> All measurement mappings by group.
	 */
	public function get_all(): array {
		$mappings = array(
			'outdoor'        => $this->outdoor(),
			'indoor'         => $this->indoor(),
			'solar_and_uvi'  => $this->solar_and_uvi(),
			'rainfall'       => $this->rainfall(),
			'rainfall_piezo' => $this->rainfall_piezo(),
			'wind'           => $this->wind(),
			'pressure'       => $this->pressure(),
			'lightning'      => $this->lightning(),
			'indoor_co2'     => $this->indoor_co2(),
			'co2_aqi_combo'  => $this->co2_aqi_combo(),
			'pm25_aqi_combo' => $this->pm25_aqi_combo(),
			'pm10_aqi_combo' => $this->pm10_aqi_combo(),
			'pm1_aqi_combo'  => $this->pm1_aqi_combo(),
			'pm4_aqi_combo'  => $this->pm4_aqi_combo(),
			't_rh_aqi_combo' => $this->t_rh_aqi_combo(),
			'water_leak'     => $this->water_leak(),
			'battery'        => $this->battery(),
		);

		// Add channel-based measurements dynamically
		$mappings = array_merge( $mappings, $this->get_channel_based_mappings() );

		return $mappings;
	}

	/**
	 * Get channel-based measurement mappings.
	 *
	 * @return array<string, array<string, string>>
	 */
	private function get_channel_based_mappings(): array {
		$mappings = array();

		// PM25 channels
		for ( $i = 1; $i <= 4; $i++ ) {
			$mappings[ "pm25_ch{$i}" ] = $this->pm25_channel();
		}

		// Temperature and humidity channels
		for ( $i = 1; $i <= 8; $i++ ) {
			$mappings[ "temp_and_humidity_ch{$i}" ] = $this->temp_and_humidity_channel();
		}

		// Soil moisture channels
		for ( $i = 1; $i <= 16; $i++ ) {
			$mappings[ "soil_ch{$i}" ] = $this->soil_channel();
		}

		// Temperature only channels
		for ( $i = 1; $i <= 8; $i++ ) {
			$mappings[ "temp_ch{$i}" ] = $this->temp_channel();
		}

		// Leaf wetness channels
		for ( $i = 1; $i <= 8; $i++ ) {
			$mappings[ "leaf_ch{$i}" ] = $this->leaf_channel();
		}

		// LDS (Level Detection Sensor) channels
		for ( $i = 1; $i <= 4; $i++ ) {
			$mappings[ "ch_lds{$i}" ] = $this->lds_channel();
		}

		return $mappings;
	}

	/**
	 * Get measurement mapping for outdoor sensors.
	 *
	 * @return array<string, string>
	 */
	public function outdoor(): array {
		return array(
			'temperature' => Temperature::class,
			'feels_like'  => Temperature::class,
			'app_temp'    => Temperature::class,
			'dew_point'   => Temperature::class,
			'humidity'    => Base_Measurement::TYPE_HUMIDITY,
		);
	}

	/**
	 * Get measurement mapping for indoor sensors.
	 *
	 * @return array<string, string>
	 */
	public function indoor(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Base_Measurement::TYPE_HUMIDITY,
		);
	}

	/**
	 * Get measurement mapping for solar and UV index sensors.
	 *
	 * @return array<string, string>
	 */
	public function solar_and_uvi(): array {
		return array(
			'solar' => Solar_Radiation::class,
			'uvi'   => Base_Measurement::TYPE_UV_INDEX,
		);
	}

	/**
	 * Get measurement mapping for rainfall sensors.
	 *
	 * @return array<string, string>
	 */
	public function rainfall(): array {
		return array(
			'rain_rate' => Rain_Rate::class,
			'daily'     => Rainfall::class,
			'event'     => Rainfall::class,
			'hourly'    => Rainfall::class,
			'weekly'    => Rainfall::class,
			'monthly'   => Rainfall::class,
			'yearly'    => Rainfall::class,
		);
	}

	/**
	 * Get measurement mapping for piezo rainfall sensors.
	 *
	 * @return array<string, string>
	 */
	public function rainfall_piezo(): array {
		return $this->rainfall();
	}

	/**
	 * Get measurement mapping for wind sensors.
	 *
	 * @return array<string, string>
	 */
	public function wind(): array {
		return array(
			'wind_speed'     => Wind_Speed::class,
			'wind_gust'      => Wind_Speed::class,
			'wind_direction' => Wind_Direction::class,
		);
	}

	/**
	 * Get measurement mapping for pressure sensors.
	 *
	 * @return array<string, string>
	 */
	public function pressure(): array {
		return array(
			'relative' => Pressure::class,
			'absolute' => Pressure::class,
		);
	}

	/**
	 * Get measurement mapping for lightning sensors.
	 *
	 * @return array<string, string>
	 */
	public function lightning(): array {
		return array(
			'distance' => Base_Measurement::TYPE_DISTANCE,
			'count'    => Base_Measurement::TYPE_COUNT,
		);
	}

	/**
	 * Get measurement mapping for indoor CO2 sensors.
	 *
	 * @return array<string, string>
	 */
	public function indoor_co2(): array {
		return array(
			'co2'              => Base_Measurement::TYPE_CO2,
			'24_hours_average' => Base_Measurement::TYPE_CO2,
		);
	}

	/**
	 * Get measurement mapping for CO2 AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function co2_aqi_combo(): array {
		return array(
			'co2'              => Base_Measurement::TYPE_CO2,
			'24_hours_average' => Base_Measurement::TYPE_CO2,
		);
	}

	/**
	 * Get measurement mapping for PM2.5 AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function pm25_aqi_combo(): array {
		return array(
			'real_time_aqi' => Base_Measurement::TYPE_AIR_QUALITY,
			'pm25'          => Base_Measurement::TYPE_AIR_QUALITY,
			'24_hours_aqi'  => Base_Measurement::TYPE_AIR_QUALITY,
		);
	}

	/**
	 * Get measurement mapping for PM10 AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function pm10_aqi_combo(): array {
		return array(
			'real_time_aqi' => Base_Measurement::TYPE_AIR_QUALITY,
			'pm10'          => Base_Measurement::TYPE_AIR_QUALITY,
			'24_hours_aqi'  => Base_Measurement::TYPE_AIR_QUALITY,
		);
	}

	/**
	 * Get measurement mapping for PM1 AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function pm1_aqi_combo(): array {
		return array(
			'real_time_aqi' => Base_Measurement::TYPE_AIR_QUALITY,
			'pm1'           => Base_Measurement::TYPE_AIR_QUALITY,
			'24_hours_aqi'  => Base_Measurement::TYPE_AIR_QUALITY,
		);
	}

	/**
	 * Get measurement mapping for PM4 AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function pm4_aqi_combo(): array {
		return array(
			'real_time_aqi' => Base_Measurement::TYPE_AIR_QUALITY,
			'pm4'           => Base_Measurement::TYPE_AIR_QUALITY,
			'24_hours_aqi'  => Base_Measurement::TYPE_AIR_QUALITY,
		);
	}

	/**
	 * Get measurement mapping for temperature/humidity AQI combo sensors.
	 *
	 * @return array<string, string>
	 */
	public function t_rh_aqi_combo(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Base_Measurement::TYPE_HUMIDITY,
		);
	}

	/**
	 * Get measurement mapping for water leak sensors.
	 *
	 * @return array<string, string>
	 */
	public function water_leak(): array {
		return array(
			'leak_ch1' => Base_Measurement::TYPE_PERCENTAGE,
			'leak_ch2' => Base_Measurement::TYPE_PERCENTAGE,
			'leak_ch3' => Base_Measurement::TYPE_PERCENTAGE,
			'leak_ch4' => Base_Measurement::TYPE_PERCENTAGE,
		);
	}

	/**
	 * Get measurement mapping for PM2.5 channel sensors.
	 *
	 * @return array<string, string>
	 */
	public function pm25_channel(): array {
		return array(
			'real_time_aqi' => Base_Measurement::TYPE_AIR_QUALITY,
			'pm25'          => Base_Measurement::TYPE_AIR_QUALITY,
			'24_hours_aqi'  => Base_Measurement::TYPE_AIR_QUALITY,
		);
	}

	/**
	 * Get measurement mapping for temperature and humidity channel sensors.
	 *
	 * @return array<string, string>
	 */
	public function temp_and_humidity_channel(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Base_Measurement::TYPE_HUMIDITY,
		);
	}

	/**
	 * Get measurement mapping for soil moisture channel sensors.
	 *
	 * @return array<string, string>
	 */
	public function soil_channel(): array {
		return array(
			'soilmoisture' => Base_Measurement::TYPE_SOIL_MOISTURE,
			'ad'           => Base_Measurement::TYPE_COUNT,
		);
	}

	/**
	 * Get measurement mapping for temperature-only channel sensors.
	 *
	 * @return array<string, string>
	 */
	public function temp_channel(): array {
		return array(
			'temperature' => Temperature::class,
		);
	}

	/**
	 * Get measurement mapping for leaf wetness channel sensors.
	 *
	 * @return array<string, string>
	 */
	public function leaf_channel(): array {
		return array(
			'leaf_wetness' => Base_Measurement::TYPE_LEAF_WETNESS,
		);
	}

	/**
	 * Get measurement mapping for LDS (Level Detection Sensor) channels.
	 *
	 * @return array<string, string>
	 */
	public function lds_channel(): array {
		return array(
			'air_ch1'     => Base_Measurement::TYPE_DISTANCE,
			'air_ch2'     => Base_Measurement::TYPE_DISTANCE,
			'air_ch3'     => Base_Measurement::TYPE_DISTANCE,
			'air_ch4'     => Base_Measurement::TYPE_DISTANCE,
			'depth_ch1'   => Base_Measurement::TYPE_DISTANCE,
			'depth_ch2'   => Base_Measurement::TYPE_DISTANCE,
			'depth_ch3'   => Base_Measurement::TYPE_DISTANCE,
			'depth_ch4'   => Base_Measurement::TYPE_DISTANCE,
			'ldsheat_ch1' => Base_Measurement::TYPE_COUNT,
			'ldsheat_ch2' => Base_Measurement::TYPE_COUNT,
			'ldsheat_ch3' => Base_Measurement::TYPE_COUNT,
			'ldsheat_ch4' => Base_Measurement::TYPE_COUNT,
		);
	}

	/**
	 * Get measurement mapping for battery sensors.
	 *
	 * @return array<string, string>
	 */
	public function battery(): array {
		return array(
			'sensor_array'             => Base_Measurement::TYPE_BATTERY,
			't_rh_p_sensor'            => Base_Measurement::TYPE_BATTERY,
			'ws1900_console'           => Base_Measurement::TYPE_VOLTAGE,
			'ws1800_console'           => Base_Measurement::TYPE_VOLTAGE,
			'ws6006_console'           => Base_Measurement::TYPE_PERCENTAGE,
			'console'                  => Base_Measurement::TYPE_VOLTAGE,
			'outdoor_t_rh_sensor'      => Base_Measurement::TYPE_BATTERY,
			'wind_sensor'              => Base_Measurement::TYPE_VOLTAGE,
			'ws90_sensor_battery'      => Base_Measurement::TYPE_VOLTAGE,
			'ws80_sensor'              => Base_Measurement::TYPE_VOLTAGE,
			'rainfall_sensor'          => Base_Measurement::TYPE_VOLTAGE,
			'ws65_67_69_sensor'        => Base_Measurement::TYPE_BATTERY,
			'lightning_sensor'         => Base_Measurement::TYPE_BATTERY,
			'aqi_combo_sensor'         => Base_Measurement::TYPE_BATTERY,
			'water_leak_sensor_ch1'    => Base_Measurement::TYPE_BATTERY,
			'water_leak_sensor_ch2'    => Base_Measurement::TYPE_BATTERY,
			'water_leak_sensor_ch3'    => Base_Measurement::TYPE_BATTERY,
			'water_leak_sensor_ch4'    => Base_Measurement::TYPE_BATTERY,
			'pm25_sensor_ch1'          => Base_Measurement::TYPE_BATTERY,
			'pm25_sensor_ch2'          => Base_Measurement::TYPE_BATTERY,
			'pm25_sensor_ch3'          => Base_Measurement::TYPE_BATTERY,
			'pm25_sensor_ch4'          => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch1' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch2' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch3' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch4' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch5' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch6' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch7' => Base_Measurement::TYPE_BATTERY,
			'temp_humidity_sensor_ch8' => Base_Measurement::TYPE_BATTERY,
			'soilmoisture_sensor_ch1'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch2'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch3'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch4'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch5'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch6'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch7'  => Base_Measurement::TYPE_VOLTAGE,
			'soilmoisture_sensor_ch8'  => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch1'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch2'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch3'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch4'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch5'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch6'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch7'   => Base_Measurement::TYPE_VOLTAGE,
			'temperature_sensor_ch8'   => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch1'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch2'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch3'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch4'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch5'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch6'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch7'  => Base_Measurement::TYPE_VOLTAGE,
			'leaf_wetness_sensor_ch8'  => Base_Measurement::TYPE_VOLTAGE,
			'ldsbatt_1'                => Base_Measurement::TYPE_VOLTAGE,
			'ldsbatt_2'                => Base_Measurement::TYPE_VOLTAGE,
			'ldsbatt_3'                => Base_Measurement::TYPE_VOLTAGE,
			'ldsbatt_4'                => Base_Measurement::TYPE_VOLTAGE,
		);
	}

	/**
	 * Get measurement mapping for a specific group.
	 *
	 * @param string $group The measurement group name.
	 * @return array<string, string>|null Mapping array or null if group not found.
	 */
	public function get_group( string $group ): ?array {
		$method = str_replace( '-', '_', $group );

		// If the method ends in _ch{digit} rename to _channel
		if ( preg_match( '/_ch\d$/', $method ) ) {
			$method = (string) preg_replace( '/_ch\d$/', '_channel', $method );
		}

		if ( method_exists( $this, $method ) ) {
			return $this->$method();
		}

		return null;
	}

	/**
	 * Get measurement class or type for a specific group and key.
	 *
	 * Returns either a class-string (for types with unit constants like Temperature)
	 * or a type constant string (for simple types like 'humidity').
	 *
	 * @param string $group The measurement group name.
	 * @param string $key The measurement key.
	 * @return string|null The measurement class or type, or null if not found.
	 */
	public function get_measurement_class( string $group, string $key ): ?string {
		$group_mapping = $this->get_group( $group );

		return $group_mapping[ $key ] ?? null;
	}
}
