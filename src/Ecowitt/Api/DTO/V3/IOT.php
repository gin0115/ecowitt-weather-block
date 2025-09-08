<?php

/**
 * IOT Device DTO for Ecowitt API v3 responses.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * IOT Device DTO class for Ecowitt API v3.
 */
class IOT {

	/**
	 * IOT device name/title.
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * IOT device default title.
	 *
	 * @var string
	 */
	public string $default_title;

	/**
	 * IOT device ID.
	 *
	 * @var string
	 */
	public string $device_id;

	/**
	 * IOT device version.
	 *
	 * @var string
	 */
	public string $version;

	/**
	 * IOT device creation time.
	 *
	 * @var string
	 */
	public string $createtime;

	/**
	 * Additional IOT device data.
	 *
	 * @var array<string, mixed>
	 */
	public array $additional_data;

	/**
	 * Constructor.
	 *
	 * @param string $name IOT device name.
	 * @param string $default_title IOT device default title.
	 * @param string $device_id IOT device ID.
	 * @param string $version IOT device version.
	 * @param string $createtime IOT device creation time.
	 * @param array<string, mixed> $additional_data Additional device data.
	 */
	public function __construct(
		string $name = '',
		string $default_title = '',
		string $device_id = '',
		string $version = '',
		string $createtime = '',
		array $additional_data = array()
	) {
		$this->name            = $name;
		$this->default_title   = $default_title;
		$this->device_id       = $device_id;
		$this->version         = $version;
		$this->createtime      = $createtime;
		$this->additional_data = $additional_data;
	}

	/**
	 * Create IOT device from API v3 response array.
	 *
	 * @param array<string, mixed> $data IOT device data from API v3 response.
	 * @return IOT IOT device instance.
	 */
	public static function from_array( array $data ): IOT {
		// Sanitize and validate data - never trust API data
		$name          = sanitize_text_field( is_scalar( $data['name'] ?? '' ) ? (string) ( $data['name'] ?? '' ) : '' );
		$default_title = sanitize_text_field( is_scalar( $data['default_title'] ?? '' ) ? (string) ( $data['default_title'] ?? '' ) : '' );
		$device_id     = sanitize_text_field( is_scalar( $data['device_id'] ?? '' ) ? (string) ( $data['device_id'] ?? '' ) : '' );
		$version       = sanitize_text_field( is_scalar( $data['version'] ?? '' ) ? (string) ( $data['version'] ?? '' ) : '' );
		$createtime    = sanitize_text_field( is_scalar( $data['createtime'] ?? '' ) ? (string) ( $data['createtime'] ?? '' ) : '' );

		// Store any additional data that doesn't fit the main properties - sanitize each value
		$known_keys      = array( 'name', 'default_title', 'device_id', 'version', 'createtime' );
		$raw_additional  = array_diff_key( $data, array_flip( $known_keys ) );
		$additional_data = array();
		foreach ( $raw_additional as $key => $value ) {
			$sanitized_key = sanitize_key( (string) $key );
			if ( is_string( $value ) ) {
				$additional_data[ $sanitized_key ] = sanitize_text_field( $value );
			} elseif ( is_numeric( $value ) ) {
				$additional_data[ $sanitized_key ] = is_float( $value ) ? (float) $value : absint( $value );
			} elseif ( is_bool( $value ) ) {
				// Preserve boolean values
				$additional_data[ $sanitized_key ] = $value;
			} elseif ( is_null( $value ) ) {
				// Preserve null values
				$additional_data[ $sanitized_key ] = $value;
			} elseif ( is_array( $value ) ) {
				// For arrays, we'll store them as-is but mark them for manual review
				$additional_data[ $sanitized_key ] = $value; // Consider recursive sanitization if needed
			} else {
				// For other types (objects, resources, etc.), convert to empty string for safety
				$additional_data[ $sanitized_key ] = '';
			}
		}

		return new self( $name, $default_title, $device_id, $version, $createtime, $additional_data );
	}
}
