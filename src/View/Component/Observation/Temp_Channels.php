<?php

/**
 * The temperature channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The temperature channels measurements component.
 *
 * @view observation.temp_channels
 */
class Temp_Channels extends Component {

	/**
	 * Array of temperature channel measurements.
	 *
	 * @var array<array{temperature: Measurement_Type|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the Temp_Channels Component.
	 *
	 * @param array<string, array<string, Base_Measurement>> $measurements Array of temperature channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			$channel = array(
				'temperature'  => null,
				'channel_name' => $channel_key,
			);

			if ( isset( $channel_data['temperature'] ) ) {
				$channel['temperature'] = Measurement_Type::for_type( $channel_data['temperature'], _x( 'Temperature', 'temp channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
