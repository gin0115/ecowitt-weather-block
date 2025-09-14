<?php

/**
 * HTTP Exception.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * HTTP Exception.
 *
 * Thrown when HTTP-related errors occur.
 */
class Http_Exception extends Ecowitt_Exception {

	/**
	 * Create exception for HTTP request failure.
	 *
	 * @param int    $status_code
	 * @param string $message
	 * @return self
	 */
	public static function request_failed( int $status_code, string $message ): self {
		return new self( "HTTP request failed with status {$status_code}: {$message}" );
	}

	/**
	 * Create exception for timeout.
	 *
	 * @return self
	 */
	public static function timeout(): self {
		return new self( 'HTTP request timed out.' );
	}

	/**
	 * Create exception for authentication failure.
	 *
	 * @return self
	 */
	public static function authentication_failed(): self {
		return new self( 'Authentication failed. Please check your API credentials.' );
	}

	/**
	 * Create exception for rate limiting.
	 *
	 * @return self
	 */
	public static function rate_limited(): self {
		return new self( 'API rate limit exceeded. Please try again later.' );
	}
}
