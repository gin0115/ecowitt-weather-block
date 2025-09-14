<?php

/**
 * Interface for conversion configuration.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Observation\Conversion;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Interface for providing unit conversion configuration data.
 */
interface Conversion_Config_Interface {

	/**
	 * Get the conversion configuration data.
	 *
	 * @return array<string, array{
	 *     base_unit: string,
	 *     base_unit_id: int,
	 *     units: array<int, string>,
	 *     to_base_conversions: array<string, callable>,
	 *     from_base_conversions: array<string, callable>,
	 *     format: array<string, callable>
	 * }> Configuration data for all measurement types.
	 */
	public function get(): array;
}
