<?php

/**
 * The PM25 channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\AQI;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Gas;

/**
 * The PM25 channels measurements component.
 *
 * @view observation.pm25_channels
 */
class PM25_Channels extends Component {

	/**
	 * Array of PM25 channel measurements.
	 *
	 * @var array<array{real_time_aqi: AQI|null, pm25: Gas|null, 24_hours_aqi: AQI|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the PM25_Channels Component.
	 *
	 * @param array<string, array<string, mixed>> $measurements Array of PM25 channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			$channel = array(
				'real_time_aqi' => null,
				'pm25'          => null,
				'24_hours_aqi'  => null,
				'channel_name'  => $channel_key,
			);

			if ( isset( $channel_data['real_time_aqi'] ) ) {
				$channel['real_time_aqi'] = new AQI( $channel_data['real_time_aqi'], _x( 'Real Time AQI', 'pm25 channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data['pm25'] ) ) {
				$channel['pm25'] = new Gas( $channel_data['pm25'], _x( 'PM2.5', 'pm25 channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data['24_hours_aqi'] ) ) {
				$channel['24_hours_aqi'] = new AQI( $channel_data['24_hours_aqi'], _x( '24h AQI', 'pm25 channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
