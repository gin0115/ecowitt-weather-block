<?php

/**
 * The PM10 AQI Combo component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The PM10 AQI Combo component.
 *
 * @view observation.pm10_aqi_combo
 */
class PM10_AQI_Combo extends Component {

	/**
	 * Real time AQI measurement.
	 *
	 * @var Measurement_Type|null
	 */
	public $real_time_aqi = null;

	/**
	 * PM10 measurement.
	 *
	 * @var Measurement_Type|null
	 */
	public $pm10 = null;

	/**
	 * 24 hours AQI measurement.
	 *
	 * @var Measurement_Type|null
	 */
	public $pm10_24h_aqi = null;

	/**
	 * Creates an instance of the PM10 AQI Combo Component.
	 *
	 * @param array $measurements
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['real_time_aqi'] ) ) {
			$this->real_time_aqi = Measurement_Type::for_type( $measurements['real_time_aqi'], _x( 'Real Time AQI', 'pm10 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['pm10'] ) ) {
			$this->pm10 = Measurement_Type::for_type( $measurements['pm10'], _x( 'PM10', 'pm10 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['24_hours_aqi'] ) ) {
			$this->pm10_24h_aqi = Measurement_Type::for_type( $measurements['24_hours_aqi'], _x( '24h AQI', 'pm10 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
