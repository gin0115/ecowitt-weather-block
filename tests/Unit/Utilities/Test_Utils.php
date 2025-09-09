<?php

/**
 * Tests for the Utils class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Utilities;

use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;

/**
 * Tests for the Utils class.
 * 
 * @group unit
 * @group utilities
 */
class Test_Utils extends \WP_UnitTestCase {

	/**
	 * @testdox It should map both array keys and values with the same callable when only one is provided
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Utilities\Utils::map_keys_and_values
	 */
	public function test_map_keys_and_values_with_single_callable(): void {
		$input = array(
			'Content-Type' => 'application/json',
			'X-Custom'     => 'value"with"quotes',
		);

		$expected = array(
			'Content-Type' => 'application/json',
			'X-Custom'     => 'value&quot;with&quot;quotes',
		);

		$result = Utils::map_keys_and_values( $input, 'esc_attr' );

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should map array keys and values with different callables when both are provided
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Utilities\Utils::map_keys_and_values
	 */
	public function test_map_keys_and_values_with_different_callables(): void {
		$input = array(
			'key1' => 'VALUE1',
			'key2' => 'VALUE2',
		);

		$expected = array(
			'KEY1' => 'value1',
			'KEY2' => 'value2',
		);

		$result = Utils::map_keys_and_values( $input, 'strtolower', 'strtoupper' );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider for different array mapping scenarios.
	 *
	 * @return array<string, array<mixed>>
	 */
	public function map_keys_and_values_scenarios_provider(): array {
		return array(
			'empty array'               => array(
				array(),
				'esc_attr',
				null,
				array(),
			),
			'single element array'      => array(
				array( 'key' => 'value' ),
				'esc_attr',
				null,
				array( 'key' => 'value' ),
			),
			'array with special chars'  => array(
				array(
					'header<script>' => 'value&test',
					'normal'         => 'clean',
				),
				'esc_attr',
				null,
				array(
					'header&lt;script&gt;' => 'value&amp;test',
					'normal'                => 'clean',
				),
			),
			'different key/value funcs' => array(
				array( 'hello' => 'WORLD' ),
				'strtolower',
				'strtoupper',
				array( 'HELLO' => 'world' ),
			),
		);
	}

	/**
	 * @testdox It should handle various array mapping scenarios correctly
	 * @dataProvider map_keys_and_values_scenarios_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Utilities\Utils::map_keys_and_values
	 */
	public function test_map_keys_and_values_scenarios( array $input, callable $value_callable, ?callable $key_callable, array $expected ): void {
		$result = Utils::map_keys_and_values( $input, $value_callable, $key_callable );

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle complex escaping scenarios correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Utilities\Utils::map_keys_and_values
	 */
	public function test_map_keys_and_values_with_complex_escaping(): void {
		$input = array(
			'Content-Type'   => 'application/json',
			'X-Custom<>'     => 'value"with&special\'chars',
			'Authorization'  => 'Bearer <token>',
		);

		$expected = array(
			'Content-Type'                => 'application/json',
			'X-Custom&lt;&gt;'            => 'value&quot;with&amp;special&#039;chars',
			'Authorization'               => 'Bearer &lt;token&gt;',
		);

		$result = Utils::map_keys_and_values( $input, 'esc_attr' );

		$this->assertSame( $expected, $result );
	}
}
