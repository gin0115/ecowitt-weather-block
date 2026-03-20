<?php

/**
 * The PM25 AQI combo measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The PM25 AQI combo measurements component.
 *
 * @view observation.pm25_aqi_combo
 */
class PM25_AQI_Combo extends Component {

	/**
	 * Real time AQI component.
	 *
	 * @var Measurement_Type|null
	 */
	public $real_time_aqi = null;

	/**
	 * PM25 component.
	 *
	 * @var Measurement_Type|null
	 */
	public $pm25 = null;

	/**
	 * 24 hours AQI component.
	 *
	 * @var Measurement_Type|null
	 */
	public $pm25_24h_aqi = null;

	/**
	 * Creates an instance of the PM25_AQI_Combo Component.
	 *
	 * @param array<string, mixed> $measurements Array with PM25 AQI combo measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['real_time_aqi'] ) ) {
			$this->real_time_aqi = Measurement_Type::for_type( $measurements['real_time_aqi'], _x( 'Real Time AQI', 'pm25 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['pm25'] ) ) {
			$this->pm25 = Measurement_Type::for_type( $measurements['pm25'], _x( 'PM2.5', 'pm25 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['24_hours_aqi'] ) ) {
			$this->pm25_24h_aqi = Measurement_Type::for_type( $measurements['24_hours_aqi'], _x( '24h AQI', 'pm25 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
