<?php

/**
 * Interface for fetching history data from the Ecowitt API.
 *
 * This is the DI seam for caching — swap the implementation
 * to add caching without touching callers.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * History Data Provider interface.
 */
interface History_Data_Provider {

	/**
	 * Fetch history data from the API.
	 *
	 * @param string     $mac        The device MAC address.
	 * @param Connection $connection The API connection credentials.
	 * @param DateTime   $from       The start date.
	 * @param DateTime   $to         The end date.
	 * @param string[]   $groups     The sensor groups to fetch (e.g. ['outdoor', 'wind']).
	 * @param string     $cycle_type The aggregation interval (e.g. '5min', '1hour', '4hour', '1day').
	 * @return array<string, mixed>  The raw decoded data from the API response.
	 */
	public function fetch_history(
		string $mac,
		Connection $connection,
		DateTime $from,
		DateTime $to,
		array $groups,
		string $cycle_type
	): array;

	/**
	 * Whether the last fetch_history() call was served from cache.
	 *
	 * @return bool True if the last call was a cache hit.
	 */
	public function was_cached(): bool;
}
