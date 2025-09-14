<?php

/**
 * AJAX Exception.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Admin\Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * AJAX Exception.
 *
 * Thrown when AJAX-related errors occur.
 */
class Ajax_Exception extends \RuntimeException {

	/**
	 * Create exception for missing required fields.
	 *
	 * @param string[] $missing_fields
	 * @return self
	 */
	public static function missing_required_fields( array $missing_fields ): self {
		$fields_list = implode( ', ', $missing_fields );
		return new self( "Missing required fields: {$fields_list}" );
	}

	/**
	 * Create exception for invalid request data.
	 *
	 * @param string $reason
	 * @return self
	 */
	public static function invalid_request_data( string $reason ): self {
		return new self( "Invalid request data: {$reason}" );
	}
}
