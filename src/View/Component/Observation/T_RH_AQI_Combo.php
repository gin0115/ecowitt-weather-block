<?php

/**
 * The Temperature and Humidity AQI Combo component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The Temperature and Humidity AQI Combo component.
 *
 * @view observation.t_rh_aqi_combo
 */
class T_RH_AQI_Combo extends Component {

	/**
	 * Temperature measurement.
	 *
	 * @var Measurement_Type|null
	 */
	public $temperature = null;

	/**
	 * Humidity measurement.
	 *
	 * @var Measurement_Type|null
	 */
	public $humidity = null;

	/**
	 * Creates an instance of the Temperature and Humidity AQI Combo Component.
	 *
	 * @param array<string, Base_Measurement> $measurements
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['temperature'] ) ) {
			$this->temperature = Measurement_Type::for_type( $measurements['temperature'], _x( 'Temperature', 't rh aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['humidity'] ) ) {
			$this->humidity = Measurement_Type::for_type( $measurements['humidity'], _x( 'Humidity', 't rh aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
