<?php

/**
 * The indoor measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The indoor measurements component.
 *
 * @view observation.indoor
 */
class Indoor extends Component {

	/**
	 * Temperature component.
	 *
	 * @var Measurement_Type|null
	 */
	public $temperature = null;

	/**
	 * Humidity component.
	 *
	 * @var Measurement_Type|null
	 */
	public $humidity = null;

	/**
	 * Creates an instance of the Indoor Component.
	 *
	 * @param array<string, Base_Measurement> $measurements Array with 'temperature' and 'humidity' measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['temperature'] ) ) {
			$this->temperature = Measurement_Type::for_type( $measurements['temperature'], _x( 'Temperature', 'indoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['humidity'] ) ) {
			$this->humidity = Measurement_Type::for_type( $measurements['humidity'], _x( 'Humidity', 'indoor measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
