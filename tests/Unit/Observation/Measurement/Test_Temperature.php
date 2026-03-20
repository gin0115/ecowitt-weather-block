<?php

/**
 * Tests for the Temperature measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Temperature measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 * @group temperature
 */
class Test_Temperature extends \WP_UnitTestCase {


	/**
	 * @testdox It should return the correct measurement type for temperature.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::get_type
	 */
	public function test_returns_correct_measurement_type(): void {
		$measurement_dto = new Measurement( '25.0', 'C', '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->assertSame( Base_Measurement::TYPE_TEMPERATURE, $temperature->get_type() );
	}

	/**
	 * @testdox It should return the correct value from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::get_value
	 */
	public function test_returns_correct_value(): void {
		$measurement_dto = new Measurement( '25.5', 'C', '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->assertSame( '25.5', $temperature->get_value() );
	}

	/**
	 * @testdox It should return the correct unit from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::get_unit
	 */
	public function test_returns_correct_unit(): void {
		$measurement_dto = new Measurement( '25.0', 'F', '1642248600' );
		$temperature = new Temperature( $measurement_dto );

		$this->assertSame( 'F', $temperature->get_unit() );
	}

	/**
	 * @testdox It should return the correct timestamp as DateTime object.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::get_timestamp
	 */
	public function test_returns_correct_timestamp(): void {
		$measurement_dto = new Measurement( '25.0', 'C', '1642248600' );
		$temperature = new Temperature( $measurement_dto );
		$timestamp = $temperature->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle empty timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::__construct
	 */
	public function test_handles_empty_timestamp(): void {
		$measurement_dto = new Measurement( '25.0', 'C', '' );
		$temperature = new Temperature( $measurement_dto );

		$this->assertNull( $temperature->get_timestamp() );
	}

	/**
	 * @testdox It should handle invalid timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature::__construct
	 */
	public function test_handles_invalid_timestamp(): void {
		$measurement_dto = new Measurement( '25.0', 'C', 'invalid_timestamp' );
		$temperature = new Temperature( $measurement_dto );

		$this->assertNull( $temperature->get_timestamp() );
	}

	/**
	 * @testdox It should provide access to temperature unit constants.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature
	 */
	public function test_provides_unit_constants(): void {
		$this->assertSame( 'C', Temperature::UNIT_CELSIUS );
		$this->assertSame( 'F', Temperature::UNIT_FAHRENHEIT );
	}
}
