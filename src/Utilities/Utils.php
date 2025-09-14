<?php

/**
 * General utility functions.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Utilities;

use PinkCrab\FunctionConstructors\Arrays as Arr;
use PinkCrab\FunctionConstructors\GeneralFunctions as Gen;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * General utility functions.
 */
class Utils {

	/**
	 * Maps both array keys and values using the provided callable(s).
	 *
	 * @template TKey of array-key
	 * @template TValue
	 * @template TNewValue
	 * @param array<TKey, TValue> $input         The array to transform.
	 * @param callable(TValue): TNewValue $value_callable The callable to apply to values.
	 * @param callable(TKey): array-key|null $key_callable   Optional. The callable to apply to keys. If null, uses value_callable.
	 * @return array<array-key, TNewValue>
	 */
	public static function map_keys_and_values( array $input, callable $value_callable, ?callable $key_callable = null ): array {
		// If no key callable provided, use the same callable for both
		$key_callable = $key_callable ?? $value_callable;

		// @phpstan-ignore-next-line
		$mapper = Gen\compose( Arr\mapKey( $key_callable ), Arr\map( $value_callable ) );

		/** @var \Closure(array<TKey, TValue>): array<array-key, TNewValue> $mapper */
		return $mapper( $input );
	}

	/**
	 * Get the WordPress date format with fallback default.
	 *
	 * @return string The date format string.
	 */
	public static function get_date_format(): string {
		$format = get_option( 'date_format' );
		return is_string( $format ) && '' !== $format ? esc_attr( $format ) : 'Y-m-d';
	}

	/**
	 * Get the WordPress time format with fallback default.
	 *
	 * @return string The time format string.
	 */
	public static function get_time_format(): string {
		$format = get_option( 'time_format' );
		return is_string( $format ) && '' !== $format ? esc_attr( $format ) : 'H:i:s';
	}

	/**
	 * Get the combined date and time format with fallback defaults.
	 *
	 * @return string The combined datetime format string.
	 */
	public static function get_datetime_format(): string {
		return self::get_date_format() . ' ' . self::get_time_format();
	}
}
