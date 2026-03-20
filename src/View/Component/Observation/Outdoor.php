<?php

/**
 * The outdoor measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The outdoor measurements component.
 *
 * @view observation.outdoor
 */
class Outdoor extends Component {

	/**
	 * Temperature component.
	 *
	 * @var Measurement_Type|null
	 */
	public $temperature = null;

	/**
	 * Feels like temperature component.
	 *
	 * @var Measurement_Type|null
	 */
	public $feels_like = null;

	/**
	 * Apparent temperature component.
	 *
	 * @var Measurement_Type|null
	 */
	public $app_temp = null;

	/**
	 * Dew point temperature component.
	 *
	 * @var Measurement_Type|null
	 */
	public $dew_point = null;

	/**
	 * Humidity component.
	 *
	 * @var Measurement_Type|null
	 */
	public $humidity = null;

	/**
	 * Creates an instance of the Outdoor Component.
	 *
	 * @param array<string, Base_Measurement> $outdoor_measurements Array with outdoor measurement instances
	 */
	public function __construct( array $outdoor_measurements = array() ) {
		if ( isset( $outdoor_measurements['temperature'] ) ) {
			$this->temperature = Measurement_Type::for_type( $outdoor_measurements['temperature'], _x( 'Temperature', 'outdoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $outdoor_measurements['feels_like'] ) ) {
			$this->feels_like = Measurement_Type::for_type( $outdoor_measurements['feels_like'], _x( 'Feels Like', 'outdoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $outdoor_measurements['app_temp'] ) ) {
			$this->app_temp = Measurement_Type::for_type( $outdoor_measurements['app_temp'], _x( 'Perceived', 'outdoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $outdoor_measurements['dew_point'] ) ) {
			$this->dew_point = Measurement_Type::for_type( $outdoor_measurements['dew_point'], _x( 'Dew Point', 'outdoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $outdoor_measurements['humidity'] ) ) {
			$this->humidity = Measurement_Type::for_type( $outdoor_measurements['humidity'], _x( 'Humidity', 'outdoor measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
