<?php

/**
 * The battery measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Voltage;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Percentage;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Battery as BatteryDTO;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Voltage as VoltageDTO;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Percentage as PercentageDTO;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Battery as Battery_Type;

/**
 * The battery measurements component.
 *
 * @view observation.battery
 */
class Battery extends Component {

	/**
	 * Battery measurements array.
	 *
	 * @var array<Battery_Type|Voltage|Percentage>
	 */
	public $battery_measurements = array();

	/**
	 * Creates an instance of the Battery Component.
	 *
	 * @param array<string, mixed> $measurements Array with battery measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $key => $measurement ) {
			$label = $this->get_battery_label( $key );
			$type  = $this->get_battery_type( $measurement );

			if ( $type ) {
				// Pass the raw measurement data to the Type component
				$this->battery_measurements[] = new $type( $measurement, $label );
			}
		}
	}

	/**
	 * Get the appropriate label for a battery measurement.
	 *
	 * @param string $key Measurement key
	 * @return string Label
	 */
	private function get_battery_label( string $key ): string {
		$labels = array(
			't_rh_p_sensor'            => 'T RH P Sensor',
			'ws1900_console'           => 'WS1900 Console',
			'ws1800_console'           => 'WS1800 Console',
			'ws6006_console'           => 'WS6006 Console',
			'console'                  => 'Console',
			'outdoor_t_rh_sensor'      => 'Outdoor T RH Sensor',
			'wind_sensor'              => 'Wind Sensor',
			'ws90_sensor_battery'      => 'WS90 Sensor Battery',
			'ws80_sensor'              => 'WS80 Sensor',
			'rainfall_sensor'          => 'Rainfall Sensor',
			'ws65_67_69_sensor'        => 'WS65/67/69 Sensor',
			'lightning_sensor'         => 'Lightning Sensor',
			'aqi_combo_sensor'         => 'AQI Combo Sensor',
			'water_leak_sensor_ch1'    => 'Water Leak Ch1',
			'water_leak_sensor_ch2'    => 'Water Leak Ch2',
			'water_leak_sensor_ch3'    => 'Water Leak Ch3',
			'water_leak_sensor_ch4'    => 'Water Leak Ch4',
			'pm25_sensor_ch1'          => 'PM2.5 Sensor Ch1',
			'pm25_sensor_ch2'          => 'PM2.5 Sensor Ch2',
			'pm25_sensor_ch3'          => 'PM2.5 Sensor Ch3',
			'pm25_sensor_ch4'          => 'PM2.5 Sensor Ch4',
			'temp_humidity_sensor_ch1' => 'Temp Humidity Ch1',
			'temp_humidity_sensor_ch2' => 'Temp Humidity Ch2',
			'temp_humidity_sensor_ch3' => 'Temp Humidity Ch3',
			'temp_humidity_sensor_ch4' => 'Temp Humidity Ch4',
			'temp_humidity_sensor_ch5' => 'Temp Humidity Ch5',
			'temp_humidity_sensor_ch6' => 'Temp Humidity Ch6',
			'temp_humidity_sensor_ch7' => 'Temp Humidity Ch7',
			'temp_humidity_sensor_ch8' => 'Temp Humidity Ch8',
			'soilmoisture_sensor_ch1'  => 'Soil Moisture Ch1',
			'soilmoisture_sensor_ch2'  => 'Soil Moisture Ch2',
			'soilmoisture_sensor_ch3'  => 'Soil Moisture Ch3',
			'soilmoisture_sensor_ch4'  => 'Soil Moisture Ch4',
			'soilmoisture_sensor_ch5'  => 'Soil Moisture Ch5',
			'soilmoisture_sensor_ch6'  => 'Soil Moisture Ch6',
			'soilmoisture_sensor_ch7'  => 'Soil Moisture Ch7',
			'soilmoisture_sensor_ch8'  => 'Soil Moisture Ch8',
			'temperature_sensor_ch1'   => 'Temperature Ch1',
			'temperature_sensor_ch2'   => 'Temperature Ch2',
			'temperature_sensor_ch3'   => 'Temperature Ch3',
			'temperature_sensor_ch4'   => 'Temperature Ch4',
			'temperature_sensor_ch5'   => 'Temperature Ch5',
			'temperature_sensor_ch6'   => 'Temperature Ch6',
			'temperature_sensor_ch7'   => 'Temperature Ch7',
			'temperature_sensor_ch8'   => 'Temperature Ch8',
			'leaf_wetness_sensor_ch1'  => 'Leaf Wetness Ch1',
			'leaf_wetness_sensor_ch2'  => 'Leaf Wetness Ch2',
			'leaf_wetness_sensor_ch3'  => 'Leaf Wetness Ch3',
			'leaf_wetness_sensor_ch4'  => 'Leaf Wetness Ch4',
			'leaf_wetness_sensor_ch5'  => 'Leaf Wetness Ch5',
			'leaf_wetness_sensor_ch6'  => 'Leaf Wetness Ch6',
			'leaf_wetness_sensor_ch7'  => 'Leaf Wetness Ch7',
			'leaf_wetness_sensor_ch8'  => 'Leaf Wetness Ch8',
			'ldsbatt_1'                => 'LDS Battery 1',
			'ldsbatt_2'                => 'LDS Battery 2',
			'ldsbatt_3'                => 'LDS Battery 3',
			'ldsbatt_4'                => 'LDS Battery 4',
		);

		return $labels[ $key ] ?? ucwords( str_replace( '_', ' ', $key ) );
	}

	/**
	 * Get the appropriate type class for a battery measurement.
	 *
	 * @param \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement $measurement Measurement data
	 * @return string|null Type class name
	 */
	private function get_battery_type( \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement $measurement ): ?string {

		switch ( get_class( $measurement ) ) {
			case BatteryDTO::class:
				return Battery_Type::class;
			case VoltageDTO::class:
				return Voltage::class;
			case PercentageDTO::class:
				return Percentage::class;
			default:
				return null;
		}
	}
}
