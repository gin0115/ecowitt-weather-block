<?php

/**
 * The wind measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The wind measurements component.
 *
 * @view observation.wind
 */
class Wind extends Component {

	/**
	 * Wind speed component.
	 *
	 * @var Measurement_Type|null
	 */
	public $wind_speed = null;

	/**
	 * Wind gust component.
	 *
	 * @var Measurement_Type|null
	 */
	public $wind_gust = null;

	/**
	 * Wind direction component.
	 *
	 * @var Measurement_Type|null
	 */
	public $wind_direction = null;

	/**
	 * Creates an instance of the Wind Component.
	 *
	 * @param array<string, Base_Measurement> $measurements Array with wind measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['wind_speed'] ) ) {
			$this->wind_speed = Measurement_Type::for_type( $measurements['wind_speed'], _x( 'Wind Speed', 'wind measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['wind_gust'] ) ) {
			$this->wind_gust = Measurement_Type::for_type( $measurements['wind_gust'], _x( 'Wind Gust', 'wind measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['wind_direction'] ) ) {
			$this->wind_direction = Measurement_Type::for_type( $measurements['wind_direction'], _x( 'Wind Direction', 'wind measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
