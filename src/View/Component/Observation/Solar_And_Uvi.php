<?php

/**
 * The solar radiation and UV index measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The solar radiation and UV index measurements component.
 *
 * @view observation.solar_and_uvi
 */
class Solar_And_Uvi extends Component {

	/**
	 * Solar radiation component.
	 *
	 * @var Measurement_Type|null
	 */
	public $solar = null;

	/**
	 * UV index component.
	 *
	 * @var Measurement_Type|null
	 */
	public $uvi = null;

	/**
	 * Creates an instance of the Solar_And_Uvi Component.
	 *
	 * @param array<string, mixed> $measurements Array with 'solar' and 'uvi' measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['solar'] ) ) {
			$this->solar = Measurement_Type::for_type( $measurements['solar'], _x( 'Solar Radiation', 'solar measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['uvi'] ) ) {
			$this->uvi = Measurement_Type::for_type( $measurements['uvi'], _x( 'UV Index', 'uvi measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
