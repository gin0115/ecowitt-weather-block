<?php

/**
 * WordPress HTTP Client.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Http;

use WP_Error;
use WP_Http;
use WP_HTTP_Requests_Response;
use PinkCrab\Ecowitt_Weather_Block\Http\Client_Interface;
use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress HTTP Client.
 */
class WordPress_Client implements Client_Interface {

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
	public function request( string $method, string $url, array $headers = array(), ?string $body = null, array $options = array() ): Response {

		// Validate and sanitize the HTTP method.
		$sanitized_method = Http_Sanitizer::sanitize_http_method( $method );
		if ( ! $sanitized_method ) {
			return new Response( 'Invalid HTTP method', 400 );
		}

		// Sanitize the URL.
		$sanitized_url = Http_Sanitizer::sanitize_url( $url );
		if ( ! $sanitized_url ) {
			return new Response( 'Invalid URL', 400 );
		}

		// Sanitize headers for the request.
		$sanitized_headers = Http_Sanitizer::sanitize_headers( $headers );

		// Prepare request arguments.
		$args = array(
			'method'  => $sanitized_method,
			'headers' => $sanitized_headers,
			'timeout' => absint( is_scalar( $options['timeout'] ?? 15 ) ? $options['timeout'] ?? 15 : 15 ),
		);

		// Only include body if it's not null (WordPress doesn't accept null for body).
		if ( null !== $body ) {
			$args['body'] = $body;
		}

		// Make the request using WordPress HTTP API.
		$response = wp_remote_request( $sanitized_url, $args );

		if ( is_wp_error( $response ) ) {
			return new Response( $response->get_error_message(), 500 );
		}
		return new Response( $response['body'], $response['response']['code'], $response['headers']->getAll() );
	}
}
