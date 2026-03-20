<?php

/**
 * The rainfall measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The rainfall measurements component.
 *
 * @view observation.rainfall
 */
class Rainfall extends Component {

	/**
	 * Rain rate component.
	 *
	 * @var Measurement_Type|null
	 */
	public $rain_rate = null;

	/**
	 * Daily rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $daily = null;

	/**
	 * Event rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $event = null;

	/**
	 * Hourly rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $hourly = null;

	/**
	 * Weekly rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $weekly = null;

	/**
	 * Monthly rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $monthly = null;

	/**
	 * Yearly rainfall component.
	 *
	 * @var Measurement_Type|null
	 */
	public $yearly = null;

	/**
	 * Creates an instance of the Rainfall Component.
	 *
	 * @param array<string, mixed> $measurements Array with rainfall measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['rain_rate'] ) ) {
			$this->rain_rate = Measurement_Type::for_type( $measurements['rain_rate'], _x( 'Rain Rate', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['daily'] ) ) {
			$this->daily = Measurement_Type::for_type( $measurements['daily'], _x( 'Daily', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['event'] ) ) {
			$this->event = Measurement_Type::for_type( $measurements['event'], _x( 'Event', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['hourly'] ) ) {
			$this->hourly = Measurement_Type::for_type( $measurements['hourly'], _x( 'Hourly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['weekly'] ) ) {
			$this->weekly = Measurement_Type::for_type( $measurements['weekly'], _x( 'Weekly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['monthly'] ) ) {
			$this->monthly = Measurement_Type::for_type( $measurements['monthly'], _x( 'Monthly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['yearly'] ) ) {
			$this->yearly = Measurement_Type::for_type( $measurements['yearly'], _x( 'Yearly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
