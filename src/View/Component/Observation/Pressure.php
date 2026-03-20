<?php

/**
 * The pressure measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The pressure measurements component.
 *
 * @view observation.pressure
 */
class Pressure extends Component {

	/**
	 * Relative pressure component.
	 *
	 * @var Measurement_Type|null
	 */
	public $relative = null;

	/**
	 * Absolute pressure component.
	 *
	 * @var Measurement_Type|null
	 */
	public $absolute = null;

	/**
	 * Creates an instance of the Pressure Component.
	 *
	 * @param array<string, mixed> $measurements Array with pressure measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['relative'] ) ) {
			$this->relative = Measurement_Type::for_type( $measurements['relative'], _x( 'Relative', 'pressure measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['absolute'] ) ) {
			$this->absolute = Measurement_Type::for_type( $measurements['absolute'], _x( 'Absolute', 'pressure measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
