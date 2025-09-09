<?php

/**
 * Sanitization utilities for HTTP-related data.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Http;

/**
 * HTTP Sanitization utilities.
 */
class Http_Sanitizer {

	/**
	 * Sanitize headers to ensure they contain valid HTTP header characters.
	 *
	 * @param array<string, string|array<string>> $headers The headers to sanitize.
	 * @return array<string, string>
	 */
	public static function sanitize_headers( array $headers ): array {
		$sanitized = array();

		foreach ( $headers as $name => $value ) {
			// Sanitize header name: remove invalid characters
			$clean_name = self::sanitize_header_name( (string) $name );

			// Handle both string and array values
			if ( is_array( $value ) ) {
				// For arrays, sanitize each element and join with ", "
				$sanitized_values = array();
				foreach ( $value as $single_value ) {
					$clean_single_value = self::sanitize_header_value( (string) $single_value );
					if ( $clean_single_value !== false ) {
						$sanitized_values[] = $clean_single_value;
					}
				}
				$clean_value = ! empty( $sanitized_values ) ? implode( ', ', $sanitized_values ) : false;
			} else {
				// For strings, sanitize directly
				$clean_value = self::sanitize_header_value( (string) $value );
			}

			// Only include if both name and value are valid
			if ( ! empty( $clean_name ) && $clean_value !== false ) {
				$sanitized[ $clean_name ] = $clean_value;
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize a header name according to RFC 7230.
	 *
	 * Header names are case-insensitive and should only contain:
	 * - Letters (A-Z, a-z)
	 * - Numbers (0-9)
	 * - Hyphens (-)
	 * - Underscores (_)
	 *
	 * @param string $name The header name.
	 * @return string The sanitized header name in lowercase.
	 */
	public static function sanitize_header_name( string $name ): string {
		// Remove any characters that aren't letters, numbers, hyphens, or underscores
		$sanitized = preg_replace( '/[^a-zA-Z0-9\-_]/', '', $name );

		// Convert to lowercase for consistency (HTTP headers are case-insensitive)
		return $sanitized ? strtolower( $sanitized ) : '';
	}

	/**
	 * Sanitize a header value according to RFC 7230.
	 *
	 * Header values should not contain control characters except TAB (0x09).
	 * This removes control characters and trims whitespace.
	 *
	 * @param string $value The header value.
	 * @return string|false The sanitized value, or false if invalid/empty.
	 */
	public static function sanitize_header_value( string $value ): ?string {
		// Remove control characters except TAB (0x09)
		// Control characters are 0x00-0x1F and 0x7F
		$sanitized = preg_replace( '/[\x00-\x08\x0A-\x1F\x7F]/', '', $value );

		// Trim leading and trailing whitespace
		$sanitized = trim( $sanitized ?? '' );

		// Return false if empty after sanitization
		return $sanitized !== '' ? $sanitized : null;
	}

	/**
	 * Validate if a header name is valid according to HTTP standards.
	 *
	 * @param string $name The header name to validate.
	 * @return bool True if valid, false otherwise.
	 */
	public static function is_valid_header_name( string $name ): bool {
		// Header names should only contain letters, numbers, hyphens, and underscores
		return (bool) preg_match( '/^[a-zA-Z0-9\-_]+$/', $name );
	}

	/**
	 * Validate if a header value is valid according to HTTP standards.
	 *
	 * @param string $value The header value to validate.
	 * @return bool True if valid, false otherwise.
	 */
	public static function is_valid_header_value( string $value ): bool {
		// Check for control characters (except TAB)
		return ! preg_match( '/[\x00-\x08\x0A-\x1F\x7F]/', $value );
	}

	/**
	 * Sanitize a URL to ensure it's safe for HTTP requests.
	 *
	 * @param string $url The URL to sanitize.
	 * @return string|null The sanitized URL, or null if invalid.
	 */
	public static function sanitize_url( string $url ): ?string {
		// Basic URL validation and sanitization
		$sanitized = filter_var( trim( $url ), FILTER_SANITIZE_URL );

		// Validate it's a proper URL
		if ( ! $sanitized || ! filter_var( $sanitized, FILTER_VALIDATE_URL ) ) {
			return null;
		}

		// Only allow HTTP and HTTPS schemes
		$scheme = wp_parse_url( $sanitized, PHP_URL_SCHEME );
		if ( ! in_array( $scheme, array( 'http', 'https' ), true ) ) {
			return null;
		}

		return $sanitized;
	}

	/**
	 * Sanitize HTTP method to ensure it's valid.
	 *
	 * @param string $method The HTTP method to sanitize.
	 * @return string|null The sanitized method, or null if invalid.
	 */
	public static function sanitize_http_method( string $method ): ?string {
		$method = strtoupper( trim( $method ) );

		// List of valid HTTP methods
		$valid_methods = array( 'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS' );

		return in_array( $method, $valid_methods, true ) ? $method : \null;
	}
}
