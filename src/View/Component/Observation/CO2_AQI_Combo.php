<?php

/**
 * The CO2 AQI Combo component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\CO2;

/**
 * The CO2 AQI Combo component.
 *
 * @view observation.co2_aqi_combo
 */
class CO2_AQI_Combo extends Component {

	/**
	 * CO2 measurement.
	 *
	 * @var CO2|null
	 */
	public $co2 = null;

	/**
	 * 24 hours average CO2 measurement.
	 *
	 * @var CO2|null
	 */
	public $co2_24h_average = null;

	/**
	 * Creates an instance of the CO2 AQI Combo Component.
	 *
	 * @param array $measurements
	 */
	public function __construct( array $measurements = array() ) {
		if ( isset( $measurements['co2'] ) ) {
			$this->co2 = new CO2( $measurements['co2'], _x( 'CO2', 'co2 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
		if ( isset( $measurements['24_hours_average'] ) ) {
			$this->co2_24h_average = new CO2( $measurements['24_hours_average'], _x( '24h Average', 'co2 aqi combo measurement label', 'ecowitt-weather-block' ) );
		}
	}
}
