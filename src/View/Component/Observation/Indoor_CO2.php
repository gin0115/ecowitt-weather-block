<?php

/**
 * The indoor CO2 measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\CO2;

/**
 * The indoor CO2 measurements component.
 *
 * @view observation.indoor_co2
 */
class Indoor_CO2 extends Component {

	/**
	 * CO2 component.
	 *
	 * @var CO2|null
	 */
	public $co2 = null;

	/**
	 * 24 hours average component.
	 *
	 * @var CO2|null
	 */
	public $co2_24h_average = null;

	/**
	 * Creates an instance of the Indoor_CO2 Component.
	 *
	 * @param array<string, mixed> $measurements Array with indoor CO2 measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['co2'] ) ) {
			$this->co2 = new CO2( $measurements['co2'], _x( 'CO2', 'indoor co2 measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['24_hours_average'] ) ) {
			$this->co2_24h_average = new CO2( $measurements['24_hours_average'], _x( '24h Average', 'indoor co2 measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
