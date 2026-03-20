<?php

/**
 * The temperature and humidity channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The temperature and humidity channels measurements component.
 *
 * @view observation.temp_and_humidity_channels
 */
class Temp_And_Humidity_Channels extends Component {

	/**
	 * Array of temperature and humidity channel measurements.
	 *
	 * @var array<array{temperature: Measurement_Type|null, humidity: Measurement_Type|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the Temp_And_Humidity_Channels Component.
	 *
	 * @param array<string, array<string, Base_Measurement>> $measurements Array of temperature and humidity channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			// Get the digit from the channel key.
			$digit = preg_replace( '/^temp_and_humidity_ch/', '', $channel_key );

			$channel = array(
				'temperature'  => null,
				'humidity'     => null,
				'channel_name' => sprintf(
					// translators: %s is the digit from the channel key.
					__( 'Channel %s', 'ecowitt-weather-block' ),
					$digit
				),
			);

			if ( isset( $channel_data['temperature'] ) ) {
				$channel['temperature'] = Measurement_Type::for_type( $channel_data['temperature'], _x( 'Temperature', 'temp and humidity channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data['humidity'] ) ) {
				$channel['humidity'] = Measurement_Type::for_type( $channel_data['humidity'], _x( 'Humidity', 'temp and humidity channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
