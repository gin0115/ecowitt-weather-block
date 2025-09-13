<?php

/**
 * Measurement DTO for Ecowitt API v3 responses.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Measurement DTO class for Ecowitt API v3.
 */
class Measurement {

	/**
	 * The value of the measurement.
	 *
	 * @var string
	 */
	public string $value;

	/**
	 * The unit of the measurement.
	 *
	 * @var string
	 */
	public string $unit;

	/**
	 * The timestamp of the measurement.
	 *
	 * @var string
	 */
	public string $timestamp;

	/**
	 * Create an instance of
	 *
	 * @param string $value The value of the measurement.
	 * @param string $unit The unit of the measurement.
	 * @param string $timestamp The timestamp of the measurement.
	 */
	public function __construct( string $value, string $unit, string $timestamp ) {
		$this->value     = $value;
		$this->unit      = $unit;
		$this->timestamp = $timestamp;
	}
}
