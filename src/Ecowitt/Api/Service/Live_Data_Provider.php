<?php

/**
 * Interface for fetching live observation data.
 *
 * DI seam for caching — swap the implementation to add
 * caching without touching callers.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Live Data Provider interface.
 */
interface Live_Data_Provider {

	/**
	 * Fetch live observation data from the API.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @return array<string, mixed>  The raw decoded data from the API response.
	 */
	public function fetch_live( string $mac, Connection $connection ): array;
}
