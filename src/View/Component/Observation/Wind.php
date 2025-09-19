<?php

/**
 * The wind measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Wind_Speed;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Wind_Direction;

/**
 * The wind measurements component.
 *
 * @view observation.wind
 */
class Wind extends Component {

	/**
	 * Wind speed component.
	 *
	 * @var Wind_Speed|null
	 */
	public $wind_speed = null;

	/**
	 * Wind gust component.
	 *
	 * @var Wind_Speed|null
	 */
	public $wind_gust = null;

	/**
	 * Wind direction component.
	 *
	 * @var Wind_Direction|null
	 */
	public $wind_direction = null;

	/**
	 * Creates an instance of the Wind Component.
	 *
	 * @param array<string, mixed> $measurements Array with wind measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['wind_speed'] ) ) {
			$this->wind_speed = new Wind_Speed( $measurements['wind_speed'], _x( 'Wind Speed', 'wind measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['wind_gust'] ) ) {
			$this->wind_gust = new Wind_Speed( $measurements['wind_gust'], _x( 'Wind Gust', 'wind measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['wind_direction'] ) ) {
			$this->wind_direction = new Wind_Direction( $measurements['wind_direction'], _x( 'Wind Direction', 'wind measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
