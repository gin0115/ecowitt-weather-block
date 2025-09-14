<?php

/**
 * Tests for the Wind_Direction measurement class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Measurement;

use DateTime;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Measurement;

/**
 * Tests for the Wind_Direction measurement class.
 *
 * @group unit
 * @group observation
 * @group measurement
 * @group wind_direction
 */
class Test_Wind_Direction extends \WP_UnitTestCase {

	/**
	 * @testdox It should return the correct measurement type for wind direction.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction::get_type
	 */
	public function test_returns_correct_measurement_type(): void {
		$measurement_dto = new Measurement( '180', '°', '1642248600' );
		$wind_direction = new Wind_Direction( $measurement_dto );

		$this->assertSame( Base_Measurement::TYPE_WIND_DIRECTION, $wind_direction->get_type() );
	}

	/**
	 * @testdox It should provide access to wind direction unit constants.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction
	 */
	public function test_provides_unit_constants(): void {
		$this->assertSame( '°', Wind_Direction::UNIT_DEGREES );
		$this->assertSame( 'cardinal', Wind_Direction::UNIT_CARDINAL );
	}
}
