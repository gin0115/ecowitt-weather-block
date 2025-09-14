<?php

/**
 * Measurement mapping configuration for converting DTOs to domain objects.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion;

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

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Configuration class for measurement type mappings from API groups/keys to domain classes.
 */
class Measurement_Mapping {

	/**
	 * Get all measurement mappings.
	 *
	 * @return array<string, array<string, class-string>> All measurement mappings by group.
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
	 * @return array<string, array<string, class-string>>
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
	 * @return array<string, class-string>
	 */
	public function outdoor(): array {
		return array(
			'temperature' => Temperature::class,
			'feels_like'  => Temperature::class,
			'app_temp'    => Temperature::class,
			'dew_point'   => Temperature::class,
			'humidity'    => Humidity::class,
		);
	}

	/**
	 * Get measurement mapping for indoor sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function indoor(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Humidity::class,
		);
	}

	/**
	 * Get measurement mapping for solar and UV index sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function solar_and_uvi(): array {
		return array(
			'solar' => Solar_Radiation::class,
			'uvi'   => UV_Index::class,
		);
	}

	/**
	 * Get measurement mapping for rainfall sensors.
	 *
	 * @return array<string, class-string>
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
	 * @return array<string, class-string>
	 */
	public function rainfall_piezo(): array {
		return $this->rainfall(); // Same as regular rainfall
	}

	/**
	 * Get measurement mapping for wind sensors.
	 *
	 * @return array<string, class-string>
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
	 * @return array<string, class-string>
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
	 * @return array<string, class-string>
	 */
	public function lightning(): array {
		return array(
			'distance' => Distance::class,
			'count'    => Count::class,
		);
	}

	/**
	 * Get measurement mapping for indoor CO2 sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function indoor_co2(): array {
		return array(
			'co2'              => CO2::class,
			'24_hours_average' => CO2::class,
		);
	}

	/**
	 * Get measurement mapping for CO2 AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function co2_aqi_combo(): array {
		return array(
			'co2'              => CO2::class,
			'24_hours_average' => CO2::class,
		);
	}

	/**
	 * Get measurement mapping for PM2.5 AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function pm25_aqi_combo(): array {
		return array(
			'real_time_aqi' => Air_Quality::class,
			'pm25'          => Air_Quality::class,
			'24_hours_aqi'  => Air_Quality::class,
		);
	}

	/**
	 * Get measurement mapping for PM10 AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function pm10_aqi_combo(): array {
		return array(
			'real_time_aqi' => Air_Quality::class,
			'pm10'          => Air_Quality::class,
			'24_hours_aqi'  => Air_Quality::class,
		);
	}

	/**
	 * Get measurement mapping for PM1 AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function pm1_aqi_combo(): array {
		return array(
			'real_time_aqi' => Air_Quality::class,
			'pm1'           => Air_Quality::class,
			'24_hours_aqi'  => Air_Quality::class,
		);
	}

	/**
	 * Get measurement mapping for PM4 AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function pm4_aqi_combo(): array {
		return array(
			'real_time_aqi' => Air_Quality::class,
			'pm4'           => Air_Quality::class,
			'24_hours_aqi'  => Air_Quality::class,
		);
	}

	/**
	 * Get measurement mapping for temperature/humidity AQI combo sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function t_rh_aqi_combo(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Humidity::class,
		);
	}

	/**
	 * Get measurement mapping for water leak sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function water_leak(): array {
		return array(
			'leak_ch1' => Percentage::class,
			'leak_ch2' => Percentage::class,
			'leak_ch3' => Percentage::class,
			'leak_ch4' => Percentage::class,
		);
	}

	/**
	 * Get measurement mapping for PM2.5 channel sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function pm25_channel(): array {
		return array(
			'real_time_aqi' => Air_Quality::class,
			'pm25'          => Air_Quality::class,
			'24_hours_aqi'  => Air_Quality::class,
		);
	}

	/**
	 * Get measurement mapping for temperature and humidity channel sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function temp_and_humidity_channel(): array {
		return array(
			'temperature' => Temperature::class,
			'humidity'    => Humidity::class,
		);
	}

	/**
	 * Get measurement mapping for soil moisture channel sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function soil_channel(): array {
		return array(
			'soilmoisture' => Soil_Moisture::class,
			'ad'           => Count::class, // A/D conversion value
		);
	}

	/**
	 * Get measurement mapping for temperature-only channel sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function temp_channel(): array {
		return array(
			'temperature' => Temperature::class,
		);
	}

	/**
	 * Get measurement mapping for leaf wetness channel sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function leaf_channel(): array {
		return array(
			'leaf_wetness' => Leaf_Wetness::class,
		);
	}

	/**
	 * Get measurement mapping for LDS (Level Detection Sensor) channels.
	 *
	 * @return array<string, class-string>
	 */
	public function lds_channel(): array {
		return array(
			'air_ch1'     => Distance::class,   // Air gap measurement
			'air_ch2'     => Distance::class,
			'air_ch3'     => Distance::class,
			'air_ch4'     => Distance::class,
			'depth_ch1'   => Distance::class,   // Depth measurement
			'depth_ch2'   => Distance::class,
			'depth_ch3'   => Distance::class,
			'depth_ch4'   => Distance::class,
			'ldsheat_ch1' => Count::class,      // LDS heat value
			'ldsheat_ch2' => Count::class,
			'ldsheat_ch3' => Count::class,
			'ldsheat_ch4' => Count::class,
		);
	}

	/**
	 * Get measurement mapping for battery sensors.
	 *
	 * @return array<string, class-string>
	 */
	public function battery(): array {
		// Battery measurements can be voltage, percentage, or just status
		// We'll use appropriate classes based on typical units
		return array(
			'sensor_array'             => Battery::class,
			't_rh_p_sensor'            => Battery::class,
			'ws1900_console'           => Voltage::class,
			'ws1800_console'           => Voltage::class,
			'ws6006_console'           => Percentage::class,
			'console'                  => Voltage::class,
			'outdoor_t_rh_sensor'      => Battery::class,
			'wind_sensor'              => Voltage::class,
			'ws90_sensor_battery'      => Voltage::class,
			'ws80_sensor'              => Voltage::class,
			'rainfall_sensor'          => Voltage::class,
			'ws65_67_69_sensor'        => Battery::class,
			'lightning_sensor'         => Battery::class,
			'aqi_combo_sensor'         => Battery::class,
			'water_leak_sensor_ch1'    => Battery::class,
			'water_leak_sensor_ch2'    => Battery::class,
			'water_leak_sensor_ch3'    => Battery::class,
			'water_leak_sensor_ch4'    => Battery::class,
			'pm25_sensor_ch1'          => Battery::class,
			'pm25_sensor_ch2'          => Battery::class,
			'pm25_sensor_ch3'          => Battery::class,
			'pm25_sensor_ch4'          => Battery::class,
			'temp_humidity_sensor_ch1' => Battery::class,
			'temp_humidity_sensor_ch2' => Battery::class,
			'temp_humidity_sensor_ch3' => Battery::class,
			'temp_humidity_sensor_ch4' => Battery::class,
			'temp_humidity_sensor_ch5' => Battery::class,
			'temp_humidity_sensor_ch6' => Battery::class,
			'temp_humidity_sensor_ch7' => Battery::class,
			'temp_humidity_sensor_ch8' => Battery::class,
			'soilmoisture_sensor_ch1'  => Voltage::class,
			'soilmoisture_sensor_ch2'  => Voltage::class,
			'soilmoisture_sensor_ch3'  => Voltage::class,
			'soilmoisture_sensor_ch4'  => Voltage::class,
			'soilmoisture_sensor_ch5'  => Voltage::class,
			'soilmoisture_sensor_ch6'  => Voltage::class,
			'soilmoisture_sensor_ch7'  => Voltage::class,
			'soilmoisture_sensor_ch8'  => Voltage::class,
			'temperature_sensor_ch1'   => Voltage::class,
			'temperature_sensor_ch2'   => Voltage::class,
			'temperature_sensor_ch3'   => Voltage::class,
			'temperature_sensor_ch4'   => Voltage::class,
			'temperature_sensor_ch5'   => Voltage::class,
			'temperature_sensor_ch6'   => Voltage::class,
			'temperature_sensor_ch7'   => Voltage::class,
			'temperature_sensor_ch8'   => Voltage::class,
			'leaf_wetness_sensor_ch1'  => Voltage::class,
			'leaf_wetness_sensor_ch2'  => Voltage::class,
			'leaf_wetness_sensor_ch3'  => Voltage::class,
			'leaf_wetness_sensor_ch4'  => Voltage::class,
			'leaf_wetness_sensor_ch5'  => Voltage::class,
			'leaf_wetness_sensor_ch6'  => Voltage::class,
			'leaf_wetness_sensor_ch7'  => Voltage::class,
			'leaf_wetness_sensor_ch8'  => Voltage::class,
			'ldsbatt_1'                => Voltage::class,
			'ldsbatt_2'                => Voltage::class,
			'ldsbatt_3'                => Voltage::class,
			'ldsbatt_4'                => Voltage::class,
		);
	}

	/**
	 * Get measurement mapping for a specific group.
	 *
	 * @param string $group The measurement group name.
	 * @return array<string, class-string>|null Mapping array or null if group not found.
	 */
	public function get_group( string $group ): ?array {
		$method = str_replace( '-', '_', $group );
		if ( method_exists( $this, $method ) ) {
			return $this->$method();
		}

		return null;
	}

	/**
	 * Get measurement class for a specific group and key.
	 *
	 * @param string $group The measurement group name.
	 * @param string $key The measurement key.
	 * @return class-string|null The measurement class or null if not found.
	 */
	public function get_measurement_class( string $group, string $key ): ?string {
		$group_mapping = $this->get_group( $group );
		return $group_mapping[ $key ] ?? null;
	}
}
