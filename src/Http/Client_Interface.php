<?php

/**
 * Interface for the HTTP Client to interact with the Ecowitt API.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Http;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd


/**
 * HTTP Client Interface.
 */
interface Client_Interface {

	/**
	 * Make a request.
	 *
	 * @param string                $method  The HTTP method.
	 * @param string                $url     The URL to request.
	 * @param array<string, string> $headers The headers to send.
	 * @param string|null           $body    The body to send.
	 * @param array<string, mixed>  $options Additional options for the request.
	 *
	 * @return Response The response from the request.
	 */
	public function request( string $method, string $url, array $headers = array(), ?string $body = null, array $options = array() ): Response;
}
