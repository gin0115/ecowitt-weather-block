<?php

/**
 * The lightning measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Distance;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Count;

/**
 * The lightning measurements component.
 *
 * @view observation.lightning
 */
class Lightning extends Component {

	/**
	 * Distance component.
	 *
	 * @var Distance|null
	 */
	public $distance = null;

	/**
	 * Count component.
	 *
	 * @var Count|null
	 */
	public $count = null;

	/**
	 * Creates an instance of the Lightning Component.
	 *
	 * @param array<string, mixed> $measurements Array with lightning measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['distance'] ) ) {
			$this->distance = new Distance( $measurements['distance'], _x( 'Distance', 'lightning measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['count'] ) ) {
			$this->count = new Count( $measurements['count'], _x( 'Count', 'lightning measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
