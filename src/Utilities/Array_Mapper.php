<?php

/**
 * Array mapping utilities for functional programming patterns.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Utilities;

use PinkCrab\FunctionConstructors\Arrays as Arr;
use PinkCrab\FunctionConstructors\GeneralFunctions as Gen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array mapping utilities for functional programming.
 */
class Array_Mapper {

	/**
	 * Maps both array keys and values using the provided callable(s).
	 *
	 * @param callable      $value_callable The callable to apply to values.
	 * @param callable|null $key_callable   Optional. The callable to apply to keys. If null, uses value_callable.
	 * @return callable
	 */
	public static function map_array( callable $value_callable, ?callable $key_callable = null ): callable {
		// If no key callable provided, use the same callable for both
		$key_callable = $key_callable ?? $value_callable;

		return Gen\compose(
			Arr\mapKey( $key_callable ),
			Arr\map( $value_callable )
		);
	}
}
