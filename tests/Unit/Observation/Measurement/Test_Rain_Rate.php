<?php

/**
 * Tests for the Rain_Rate measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Rain_Rate measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 * @group rain_rate
 */
class Test_Rain_Rate extends \WP_UnitTestCase {


	/**
	 * @testdox It should return the correct measurement type for rain rate.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_type
	 */
	public function test_returns_correct_measurement_type(): void {
		$measurement_dto = new Measurement( '2.5', 'in/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertSame( Base_Measurement::TYPE_RAIN_RATE, $rain_rate->get_type() );
	}

	/**
	 * @testdox It should return the correct value from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_value
	 */
	public function test_returns_correct_value(): void {
		$measurement_dto = new Measurement( '12.75', 'mm/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertSame( '12.75', $rain_rate->get_value() );
	}

	/**
	 * @testdox It should return the correct unit from the DTO.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_unit
	 */
	public function test_returns_correct_unit(): void {
		$measurement_dto = new Measurement( '5.0', 'in/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertSame( 'in/hr', $rain_rate->get_unit() );
	}

	/**
	 * @testdox It should return the correct timestamp as DateTime object.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_timestamp
	 */
	public function test_returns_correct_timestamp(): void {
		$measurement_dto = new Measurement( '2.5', 'mm/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );
		$timestamp       = $rain_rate->get_timestamp();

		$this->assertInstanceOf( DateTime::class, $timestamp );
		$this->assertSame( '1642248600', $timestamp->format( 'U' ) );
	}

	/**
	 * @testdox It should handle empty timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::__construct
	 */
	public function test_handles_empty_timestamp(): void {
		$measurement_dto = new Measurement( '1.0', 'in/hr', '' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertNull( $rain_rate->get_timestamp() );
	}

	/**
	 * @testdox It should handle invalid timestamp string gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::__construct
	 */
	public function test_handles_invalid_timestamp(): void {
		$measurement_dto = new Measurement( '1.0', 'in/hr', 'invalid_timestamp' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertNull( $rain_rate->get_timestamp() );
	}

	/**
	 * @testdox It should provide access to rain rate unit constants.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate
	 */
	public function test_provides_unit_constants(): void {
		$this->assertSame( 'in/hr', Rain_Rate::UNIT_INCHES_PER_HOUR );
		$this->assertSame( 'mm/hr', Rain_Rate::UNIT_MILLIMETERS_PER_HOUR );
	}

	/**
	 * @testdox It should handle zero rain rate values.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_value
	 */
	public function test_handles_zero_rain_rate(): void {
		$measurement_dto = new Measurement( '0.0', 'in/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertSame( '0.0', $rain_rate->get_value() );
	}

	/**
	 * @testdox It should handle high rain rate values.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate::get_value
	 */
	public function test_handles_high_rain_rate(): void {
		$measurement_dto = new Measurement( '242.56', 'in/hr', '1642248600' );
		$rain_rate       = new Rain_Rate( $measurement_dto );

		$this->assertSame( '242.56', $rain_rate->get_value() );
	}
}
