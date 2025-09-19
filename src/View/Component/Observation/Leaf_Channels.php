<?php

/**
 * The leaf wetness channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Leaf_Wetness;

/**
 * The leaf wetness channels measurements component.
 *
 * @view observation.leaf_channels
 */
class Leaf_Channels extends Component {

	/**
	 * Array of leaf wetness channel measurements.
	 *
	 * @var array<array{leaf_wetness: Leaf_Wetness|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the Leaf_Channels Component.
	 *
	 * @param array<string, array<string, mixed>> $measurements Array of leaf wetness channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			$channel = array(
				'leaf_wetness' => null,
				'channel_name' => $channel_key,
			);

			if ( isset( $channel_data['leaf_wetness'] ) ) {
				$channel['leaf_wetness'] = new Leaf_Wetness( $channel_data['leaf_wetness'], _x( 'Leaf Wetness', 'leaf channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
