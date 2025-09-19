<?php

/**
 * The rainfall measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall as RainfallType;

/**
 * The rainfall measurements component.
 *
 * @view observation.rainfall
 */
class Rainfall extends Component {

	/**
	 * Rain rate component.
	 *
	 * @var Rain_Rate|null
	 */
	public $rain_rate = null;

	/**
	 * Daily rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $daily = null;

	/**
	 * Event rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $event = null;

	/**
	 * Hourly rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $hourly = null;

	/**
	 * Weekly rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $weekly = null;

	/**
	 * Monthly rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $monthly = null;

	/**
	 * Yearly rainfall component.
	 *
	 * @var RainfallType|null
	 */
	public $yearly = null;

	/**
	 * Creates an instance of the Rainfall Component.
	 *
	 * @param array<string, mixed> $measurements Array with rainfall measurement instances
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['rain_rate'] ) ) {
			$this->rain_rate = new Rain_Rate( $measurements['rain_rate'], _x( 'Rain Rate', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['daily'] ) ) {
			$this->daily = new RainfallType( $measurements['daily'], _x( 'Daily', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['event'] ) ) {
			$this->event = new RainfallType( $measurements['event'], _x( 'Event', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['hourly'] ) ) {
			$this->hourly = new RainfallType( $measurements['hourly'], _x( 'Hourly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['weekly'] ) ) {
			$this->weekly = new RainfallType( $measurements['weekly'], _x( 'Weekly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['monthly'] ) ) {
			$this->monthly = new RainfallType( $measurements['monthly'], _x( 'Monthly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}

		if ( isset( $measurements['yearly'] ) ) {
			$this->yearly = new RainfallType( $measurements['yearly'], _x( 'Yearly', 'rainfall measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
