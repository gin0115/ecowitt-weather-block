<?php

/**
 * Tests for the Pressure measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Pressure measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 * @group pressure
 */
class Test_Pressure extends \WP_UnitTestCase {

	/**
	 * @testdox It should return the correct measurement type for pressure.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::get_type
	 */
	public function test_returns_correct_measurement_type(): void {
		$measurement_dto = new Measurement( '1013.25', 'hPa', '1642248600' );
		$pressure = new Pressure( $measurement_dto );

		$this->assertSame( Base_Measurement::TYPE_PRESSURE, $pressure->get_type() );
	}

	/**
	 * @testdox It should return the correct value from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::get_value
	 */
	public function test_returns_correct_value(): void {
		$measurement_dto = new Measurement( '29.92', 'inHg', '1642248600' );
		$pressure = new Pressure( $measurement_dto );

		$this->assertSame( '29.92', $pressure->get_value() );
	}

	/**
	 * @testdox It should return the correct unit from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::get_unit
	 */
	public function test_returns_correct_unit(): void {
		$measurement_dto = new Measurement( '760.0', 'mmHg', '1642248600' );
		$pressure = new Pressure( $measurement_dto );

		$this->assertSame( 'mmHg', $pressure->get_unit() );
	}

	/**
	 * @testdox It should return the correct timestamp as DateTime object.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::get_timestamp
	 */
	public function test_returns_correct_timestamp(): void {
		$measurement_dto = new Measurement( '1013.25', 'hPa', '1642248600' );
		$pressure = new Pressure( $measurement_dto );
		$timestamp = $pressure->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle empty timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::__construct
	 */
	public function test_handles_empty_timestamp(): void {
		$measurement_dto = new Measurement( '1013.25', 'hPa', '' );
		$pressure = new Pressure( $measurement_dto );

		$this->assertNull( $pressure->get_timestamp() );
	}

	/**
	 * @testdox It should handle invalid timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure::__construct
	 */
	public function test_handles_invalid_timestamp(): void {
		$measurement_dto = new Measurement( '1013.25', 'hPa', 'invalid_timestamp' );
		$pressure = new Pressure( $measurement_dto );

		$this->assertNull( $pressure->get_timestamp() );
	}

	/**
	 * @testdox It should provide access to pressure unit constants.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure
	 */
	public function test_provides_unit_constants(): void {
		$this->assertSame( 'hPa', Pressure::UNIT_HPA );
		$this->assertSame( 'inHg', Pressure::UNIT_INHG );
		$this->assertSame( 'mmHg', Pressure::UNIT_MMHG );
	}
}
