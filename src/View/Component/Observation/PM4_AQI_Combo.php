<?php

/**
 * The PM4 AQI Combo component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Air_Quality;

/**
 * The PM4 AQI Combo component.
 *
 * @view observation.pm4_aqi_combo
 */
class PM4_AQI_Combo extends Component {

	/**
	 * Real time AQI measurement.
	 *
	 * @var Air_Quality|null
	 */
	public $real_time_aqi = null;

	/**
	 * PM4 measurement.
	 *
	 * @var Air_Quality|null
	 */
	public $pm4 = null;

	/**
	 * 24 hours AQI measurement.
	 *
	 * @var Air_Quality|null
	 */
	public $pm4_24h_aqi = null;

	/**
	 * Creates an instance of the PM4 AQI Combo Component.
	 *
	 * @param array $measurements
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['real_time_aqi'] ) ) {
			$this->real_time_aqi = new Air_Quality( $measurements['real_time_aqi'], _x( 'Real Time AQI', 'pm4 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['pm4'] ) ) {
			$this->pm4 = new Air_Quality( $measurements['pm4'], _x( 'PM4', 'pm4 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['24_hours_aqi'] ) ) {
			$this->pm4_24h_aqi = new Air_Quality( $measurements['24_hours_aqi'], _x( '24h AQI', 'pm4 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
