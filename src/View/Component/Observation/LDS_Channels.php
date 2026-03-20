<?php

/**
 * The LDS channels measurements component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type;

/**
 * The LDS channels measurements component.
 *
 * @view observation.lds_channels
 */
class LDS_Channels extends Component {

	/**
	 * Array of LDS channel measurements.
	 *
	 * @var array<array{air: Measurement_Type|null, depth: Measurement_Type|null, ldsheat: Measurement_Type|null, channel_name: string}>
	 */
	public $channels = array();

	/**
	 * Creates an instance of the LDS_Channels Component.
	 *
	 * @param array<string, array<string, mixed>> $measurements Array of LDS channel measurement arrays
	 */
	public function __construct( array $measurements = array() ) {
		foreach ( $measurements as $channel_key => $channel_data ) {
			$channel = array(
				'air'          => null,
				'depth'        => null,
				'ldsheat'      => null,
				'channel_name' => $channel_key,
			);

			// Extract channel number from key (e.g., 'ch_lds1' -> '1')
			$channel_num = str_replace( array( 'ch_lds', 'air_ch', 'depth_ch', 'ldsheat_ch' ), '', $channel_key );

			if ( isset( $channel_data[ 'air_ch' . $channel_num ] ) ) {
				$channel['air'] = Measurement_Type::for_type( $channel_data[ 'air_ch' . $channel_num ], _x( 'Air', 'lds channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data[ 'depth_ch' . $channel_num ] ) ) {
				$channel['depth'] = Measurement_Type::for_type( $channel_data[ 'depth_ch' . $channel_num ], _x( 'Depth', 'lds channels measurement label', 'ecowitt-weather-block' ) );
			}

			if ( isset( $channel_data[ 'ldsheat_ch' . $channel_num ] ) ) {
				$channel['ldsheat'] = Measurement_Type::for_type( $channel_data[ 'ldsheat_ch' . $channel_num ], _x( 'LDS Heat', 'lds channels measurement label', 'ecowitt-weather-block' ) );
			}

			$this->channels[] = $channel;
		}
	}
}
