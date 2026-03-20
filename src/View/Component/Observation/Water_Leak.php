<?php

/**
 * The water leak measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The water leak measurements component.
 *
 * @view observation.water_leak
 */
class Water_Leak extends Component {

	/**
	 * Leak channel 1 component.
	 *
	 * @var Measurement_Type|null
	 */
	public $leak_ch1 = null;

	/**
	 * Leak channel 2 component.
	 *
	 * @var Measurement_Type|null
	 */
	public $leak_ch2 = null;

	/**
	 * Leak channel 3 component.
	 *
	 * @var Measurement_Type|null
	 */
	public $leak_ch3 = null;

	/**
	 * Leak channel 4 component.
	 *
	 * @var Measurement_Type|null
	 */
	public $leak_ch4 = null;

	/**
	 * Creates an instance of the Water_Leak Component.
	 *
	 * @param array<string, mixed> $measurements Array with water leak measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['leak_ch1'] ) ) {
			$this->leak_ch1 = Measurement_Type::for_type( $measurements['leak_ch1'], _x( 'Channel 1', 'water leak measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['leak_ch2'] ) ) {
			$this->leak_ch2 = Measurement_Type::for_type( $measurements['leak_ch2'], _x( 'Channel 2', 'water leak measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['leak_ch3'] ) ) {
			$this->leak_ch3 = Measurement_Type::for_type( $measurements['leak_ch3'], _x( 'Channel 3', 'water leak measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['leak_ch4'] ) ) {
			$this->leak_ch4 = Measurement_Type::for_type( $measurements['leak_ch4'], _x( 'Channel 4', 'water leak measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
