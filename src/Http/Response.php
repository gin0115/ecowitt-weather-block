<?php

/**
 * Model for the response from the Ecowitt API.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Http;

use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;

/**
 * HTTP Response Model.
 */
class Response {

	/**
	 * The response body.
	 *
	 * @var string
	 */
	protected string $body;

	/**
	 * The response status code.
	 *
	 * @var int
	 */
	protected int $status_code;

	/**
	 * The response headers.
	 *
	 * @var array<string, string>
	 */
	protected array $headers;

	/**
	 * Creates an instance of the Response.
	 *
	 * @param string                $body        The response body.
	 * @param integer               $status_code The response status code.
	 * @param array<string, string> $headers     The response headers.
	 */
	public function __construct( string $body, int $status_code = 200, array $headers = array() ) {
		$this->body        = $body;
		$this->status_code = absint( $status_code );
		$this->headers     = Http_Sanitizer::sanitize_headers( $headers );
	}

	/**
	 * Access to the response body.
	 *
	 * @return string
	 */
	public function body(): string {
		return $this->body;
	}

	/**
	 * Access to the response status code.
	 *
	 * @return integer
	 */
	public function status_code(): int {
		return $this->status_code;
	}

	/**
	 * Access to the response headers.
	 *
	 * @return array<string, string>
	 */
	public function headers(): array {
		return $this->headers;
	}

	/**
	 * Get headers escaped for HTML output.
	 * Use this when displaying headers in HTML contexts.
	 *
	 * @return array<string, string>
	 */
	public function headers_escaped(): array {
		return Utils::map_keys_and_values( $this->headers, 'esc_attr' );
	}
}
