<?php

/**
 * Flow Rate measurement.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Flow Rate measurement class.
 */
class Flow_Rate extends Base_Measurement {


	/**
	 * Get the measurement type identifier.
	 *
	 * @return string The measurement type.
	 */
	public function get_type(): string {
		return self::TYPE_FLOW_RATE;
	}
}
