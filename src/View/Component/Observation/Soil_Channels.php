<?php

/**
 * The soil channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

/**
 * The soil channels measurements component.
 *
 * @view observation.soil_channels
 */
class Soil_Channels extends Component {

	/**
	 * Array of soil channel measurements.
	 *
	 * @var array<array{soilmoisture: Measurement_Type|null, ad: Measurement_Type|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the Soil_Channels Component.
	 *
	 * @param array<string, array<string, Base_Measurement>> $measurements Array of soil channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			$channel = array(
				'soilmoisture' => null,
				'ad'           => null,
				'channel_name' => $channel_key,
			);

			if ( isset( $channel_data['soilmoisture'] ) ) {
				$channel['soilmoisture'] = Measurement_Type::for_type( $channel_data['soilmoisture'], _x( 'Soil Moisture', 'soil channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data['ad'] ) ) {
				$channel['ad'] = Measurement_Type::for_type( $channel_data['ad'], _x( 'AD', 'soil channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
