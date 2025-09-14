<?php

/**
 * Tests for the Humidity measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Humidity measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 * @group humidity
 */
class Test_Humidity extends \WP_UnitTestCase {

	/**
	 * @testdox It should return the correct measurement type for humidity.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::get_type
	 */
	public function test_returns_correct_measurement_type(): void {
		$measurement_dto = new Measurement( '65', '%', '1642248600' );
		$humidity = new Humidity( $measurement_dto );

		$this->assertSame( Base_Measurement::TYPE_HUMIDITY, $humidity->get_type() );
	}

	/**
	 * @testdox It should return the correct value from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::get_value
	 */
	public function test_returns_correct_value(): void {
		$measurement_dto = new Measurement( '65.5', '%', '1642248600' );
		$humidity = new Humidity( $measurement_dto );

		$this->assertSame( '65.5', $humidity->get_value() );
	}

	/**
	 * @testdox It should return the correct unit from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::get_unit
	 */
	public function test_returns_correct_unit(): void {
		$measurement_dto = new Measurement( '65', '%', '1642248600' );
		$humidity = new Humidity( $measurement_dto );

		$this->assertSame( '%', $humidity->get_unit() );
	}

	/**
	 * @testdox It should return the correct timestamp as DateTime object.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::get_timestamp
	 */
	public function test_returns_correct_timestamp(): void {
		$measurement_dto = new Measurement( '65', '%', '1642248600' );
		$humidity = new Humidity( $measurement_dto );
		$timestamp = $humidity->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle empty timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::__construct
	 */
	public function test_handles_empty_timestamp(): void {
		$measurement_dto = new Measurement( '65', '%', '' );
		$humidity = new Humidity( $measurement_dto );

		$this->assertNull( $humidity->get_timestamp() );
	}

	/**
	 * @testdox It should handle invalid timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Humidity::__construct
	 */
	public function test_handles_invalid_timestamp(): void {
		$measurement_dto = new Measurement( '65', '%', 'invalid_timestamp' );
		$humidity = new Humidity( $measurement_dto );

		$this->assertNull( $humidity->get_timestamp() );
	}
}
