<?php

/**
 * The indoor measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Humidity;

/**
 * The indoor measurements component.
 *
 * @view observation.indoor
 */
class Indoor extends Component {

	/**
	 * Temperature component.
	 *
	 * @var Temperature|null
	 */
	public $temperature = null;

	/**
	 * Humidity component.
	 *
	 * @var Humidity|null
	 */
	public $humidity = null;

	/**
	 * Creates an instance of the Indoor Component.
	 *
	 * @param array<string, mixed> $measurements Array with 'temperature' and 'humidity' measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['temperature'] ) ) {
			$this->temperature = new Temperature( $measurements['temperature'], _x( 'Temperature', 'indoor measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['humidity'] ) ) {
			$this->humidity = new Humidity( $measurements['humidity'], _x( 'Humidity', 'indoor measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
