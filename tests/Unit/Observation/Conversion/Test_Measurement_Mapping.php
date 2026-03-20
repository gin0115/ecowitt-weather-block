<?php

/**
 * Tests for the Measurement_Mapping class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion;

use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Measurement_Mapping;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Direction;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed;

/**
 * Tests for the Measurement_Mapping class.
 *
 * @group unit
 * @group observation
 * @group conversion
 */
class Test_Measurement_Mapping extends \WP_UnitTestCase {

	/**
	 * @testdox It should return correct mappings for outdoor sensors
	 */
	public function test_outdoor_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->outdoor();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'temperature', $result );
		$this->assertArrayHasKey( 'feels_like', $result );
		$this->assertArrayHasKey( 'app_temp', $result );
		$this->assertArrayHasKey( 'dew_point', $result );
		$this->assertArrayHasKey( 'humidity', $result );

		$this->assertEquals( Temperature::class, $result['temperature'] );
		$this->assertEquals( Temperature::class, $result['feels_like'] );
		$this->assertEquals( Temperature::class, $result['app_temp'] );
		$this->assertEquals( Temperature::class, $result['dew_point'] );
		$this->assertEquals( Base_Measurement::TYPE_HUMIDITY, $result['humidity'] );
	}

	/**
	 * @testdox It should return correct mappings for indoor sensors
	 */
	public function test_indoor_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->indoor();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'temperature', $result );
		$this->assertArrayHasKey( 'humidity', $result );

		$this->assertEquals( Temperature::class, $result['temperature'] );
		$this->assertEquals( Base_Measurement::TYPE_HUMIDITY, $result['humidity'] );
	}

	/**
	 * @testdox It should return correct mappings for solar and UV sensors
	 */
	public function test_solar_and_uvi_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->solar_and_uvi();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'solar', $result );
		$this->assertArrayHasKey( 'uvi', $result );

		$this->assertEquals( Solar_Radiation::class, $result['solar'] );
		$this->assertEquals( Base_Measurement::TYPE_UV_INDEX, $result['uvi'] );
	}

	/**
	 * Data provider for rainfall mapping tests.
	 *
	 * @return array
	 */
	public function rainfall_keys_provider(): array {
		return array(
			'rain rate' => array( 'rain_rate', Rain_Rate::class ),
			'daily rainfall' => array( 'daily', Rainfall::class ),
			'event rainfall' => array( 'event', Rainfall::class ),
			'hourly rainfall' => array( 'hourly', Rainfall::class ),
			'weekly rainfall' => array( 'weekly', Rainfall::class ),
			'monthly rainfall' => array( 'monthly', Rainfall::class ),
			'yearly rainfall' => array( 'yearly', Rainfall::class ),
		);
	}

	/**
	 * @testdox It should return correct mapping for $description
	 * @dataProvider rainfall_keys_provider
	 */
	public function test_rainfall_mapping( string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->rainfall();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $key, $result );
		$this->assertEquals( $expected_class, $result[ $key ] );
	}

	/**
	 * @testdox It should return correct mappings for wind sensors
	 */
	public function test_wind_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->wind();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'wind_speed', $result );
		$this->assertArrayHasKey( 'wind_gust', $result );
		$this->assertArrayHasKey( 'wind_direction', $result );

		$this->assertEquals( Wind_Speed::class, $result['wind_speed'] );
		$this->assertEquals( Wind_Speed::class, $result['wind_gust'] );
		$this->assertEquals( Wind_Direction::class, $result['wind_direction'] );
	}

	/**
	 * @testdox It should return correct mappings for pressure sensors
	 */
	public function test_pressure_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->pressure();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'relative', $result );
		$this->assertArrayHasKey( 'absolute', $result );

		$this->assertEquals( Pressure::class, $result['relative'] );
		$this->assertEquals( Pressure::class, $result['absolute'] );
	}

	/**
	 * @testdox It should return correct mappings for lightning sensors
	 */
	public function test_lightning_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->lightning();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'distance', $result );
		$this->assertArrayHasKey( 'count', $result );

		$this->assertEquals( Base_Measurement::TYPE_DISTANCE, $result['distance'] );
		$this->assertEquals( Base_Measurement::TYPE_COUNT, $result['count'] );
	}

	/**
	 * Data provider for CO2 sensor mapping tests.
	 *
	 * @return array
	 */
	public function co2_keys_provider(): array {
		return array(
			'CO2 level' => array( 'co2', Base_Measurement::TYPE_CO2 ),
			'24 hour average' => array( '24_hours_average', Base_Measurement::TYPE_CO2 ),
		);
	}

	/**
	 * @testdox It should return correct mapping for indoor CO2 $description
	 * @dataProvider co2_keys_provider
	 */
	public function test_indoor_co2_mapping( string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->indoor_co2();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $key, $result );
		$this->assertEquals( $expected_class, $result[ $key ] );
	}

	/**
	 * Data provider for air quality sensor mapping tests.
	 *
	 * @return array
	 */
	public function air_quality_keys_provider(): array {
		return array(
			'real time AQI' => array( 'real_time_aqi', Base_Measurement::TYPE_AIR_QUALITY ),
			'24 hour AQI' => array( '24_hours_aqi', Base_Measurement::TYPE_AIR_QUALITY ),
		);
	}

	/**
	 * @testdox It should return correct mapping for PM2.5 AQI $description
	 * @dataProvider air_quality_keys_provider
	 */
	public function test_pm25_aqi_combo_mapping( string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->pm25_aqi_combo();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $key, $result );
		$this->assertArrayHasKey( 'pm25', $result );
		$this->assertEquals( $expected_class, $result[ $key ] );
		$this->assertEquals( Base_Measurement::TYPE_AIR_QUALITY, $result['pm25'] );
	}

	/**
	 * @testdox It should return correct mappings for soil moisture channels
	 */
	public function test_soil_channel_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->soil_channel();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'soilmoisture', $result );
		$this->assertArrayHasKey( 'ad', $result );

		$this->assertEquals( Base_Measurement::TYPE_SOIL_MOISTURE, $result['soilmoisture'] );
		$this->assertEquals( Base_Measurement::TYPE_COUNT, $result['ad'] );
	}

	/**
	 * @testdox It should return correct mappings for temperature only channels
	 */
	public function test_temp_channel_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->temp_channel();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'temperature', $result );
		$this->assertEquals( Temperature::class, $result['temperature'] );
	}

	/**
	 * @testdox It should return correct mappings for leaf wetness channels
	 */
	public function test_leaf_channel_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->leaf_channel();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'leaf_wetness', $result );
		$this->assertEquals( Base_Measurement::TYPE_LEAF_WETNESS, $result['leaf_wetness'] );
	}

	/**
	 * Data provider for LDS channel mapping tests.
	 *
	 * @return array
	 */
	public function lds_keys_provider(): array {
		return array(
			'air gap channel 1' => array( 'air_ch1', Base_Measurement::TYPE_DISTANCE ),
			'air gap channel 2' => array( 'air_ch2', Base_Measurement::TYPE_DISTANCE ),
			'depth channel 1' => array( 'depth_ch1', Base_Measurement::TYPE_DISTANCE ),
			'depth channel 4' => array( 'depth_ch4', Base_Measurement::TYPE_DISTANCE ),
			'LDS heat channel 1' => array( 'ldsheat_ch1', Base_Measurement::TYPE_COUNT ),
			'LDS heat channel 4' => array( 'ldsheat_ch4', Base_Measurement::TYPE_COUNT ),
		);
	}

	/**
	 * @testdox It should return correct mapping for LDS $description
	 * @dataProvider lds_keys_provider
	 */
	public function test_lds_channel_mapping( string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->lds_channel();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $key, $result );
		$this->assertEquals( $expected_class, $result[ $key ] );
	}

	/**
	 * Data provider for battery sensor mapping tests.
	 *
	 * @return array
	 */
	public function battery_keys_provider(): array {
		return array(
			'sensor array' => array( 'sensor_array', Base_Measurement::TYPE_BATTERY ),
			'outdoor sensor' => array( 'outdoor_t_rh_sensor', Base_Measurement::TYPE_BATTERY ),
			'wind sensor' => array( 'wind_sensor', Base_Measurement::TYPE_VOLTAGE ),
			'console' => array( 'console', Base_Measurement::TYPE_VOLTAGE ),
			'WS6006 console' => array( 'ws6006_console', Base_Measurement::TYPE_PERCENTAGE ),
			'soil moisture sensor' => array( 'soilmoisture_sensor_ch1', Base_Measurement::TYPE_VOLTAGE ),
			'LDS battery 1' => array( 'ldsbatt_1', Base_Measurement::TYPE_VOLTAGE ),
		);
	}

	/**
	 * @testdox It should return correct mapping for battery $description
	 * @dataProvider battery_keys_provider
	 */
	public function test_battery_mapping( string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->battery();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $key, $result );
		$this->assertEquals( $expected_class, $result[ $key ] );
	}

	/**
	 * Data provider for get_all tests.
	 *
	 * @return array
	 */
	public function expected_groups_provider(): array {
		return array(
			'outdoor group' => array( 'outdoor' ),
			'indoor group' => array( 'indoor' ),
			'solar and UV group' => array( 'solar_and_uvi' ),
			'rainfall group' => array( 'rainfall' ),
			'wind group' => array( 'wind' ),
			'pressure group' => array( 'pressure' ),
			'battery group' => array( 'battery' ),
		);
	}

	/**
	 * @testdox It should include $group_name in get_all results
	 * @dataProvider expected_groups_provider
	 */
	public function test_get_all_includes_group( string $group_name ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_all();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $group_name, $result );
		$this->assertIsArray( $result[ $group_name ] );
	}

	/**
	 * Data provider for dynamic channel tests.
	 *
	 * @return array
	 */
	public function dynamic_channels_provider(): array {
		return array(
			'PM2.5 channel 1' => array( 'pm25_ch1' ),
			'PM2.5 channel 4' => array( 'pm25_ch4' ),
			'temp/humidity channel 1' => array( 'temp_and_humidity_ch1' ),
			'temp/humidity channel 8' => array( 'temp_and_humidity_ch8' ),
			'soil channel 1' => array( 'soil_ch1' ),
			'soil channel 16' => array( 'soil_ch16' ),
			'temperature channel 1' => array( 'temp_ch1' ),
			'temperature channel 8' => array( 'temp_ch8' ),
			'leaf channel 1' => array( 'leaf_ch1' ),
			'leaf channel 8' => array( 'leaf_ch8' ),
			'LDS channel 1' => array( 'ch_lds1' ),
			'LDS channel 4' => array( 'ch_lds4' ),
		);
	}

	/**
	 * @testdox It should include dynamic $description in get_all results
	 * @dataProvider dynamic_channels_provider
	 */
	public function test_get_all_includes_dynamic_channels( string $channel_name, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_all();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( $channel_name, $result );
		$this->assertIsArray( $result[ $channel_name ] );
	}

	/**
	 * Data provider for get_group tests.
	 *
	 * @return array
	 */
	public function valid_groups_provider(): array {
		return array(
			'outdoor group' => array( 'outdoor' ),
			'indoor group' => array( 'indoor' ),
			'rainfall group' => array( 'rainfall' ),
			'wind group' => array( 'wind' ),
			'pressure group' => array( 'pressure' ),
		);
	}

	/**
	 * @testdox It should return correct mapping for $group_name via get_group
	 * @dataProvider valid_groups_provider
	 */
	public function test_get_group_returns_mapping( string $group_name ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_group( $group_name );

		$this->assertIsArray( $result );
		$this->assertNotEmpty( $result );

		// Verify all values are strings (class-strings or type constant strings)
		foreach ( $result as $key => $value ) {
			$this->assertIsString( $value );
		}
	}

	/**
	 * @testdox It should return null for invalid group names
	 */
	public function test_get_group_returns_null_for_invalid(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_group( 'invalid_group' );

		$this->assertNull( $result );
	}

	/**
	 * Data provider for get_measurement_class tests.
	 *
	 * @return array
	 */
	public function measurement_class_provider(): array {
		return array(
			'outdoor temperature' => array( 'outdoor', 'temperature', Temperature::class ),
			'outdoor humidity' => array( 'outdoor', 'humidity', Base_Measurement::TYPE_HUMIDITY ),
			'rainfall rain rate' => array( 'rainfall', 'rain_rate', Rain_Rate::class ),
			'rainfall daily' => array( 'rainfall', 'daily', Rainfall::class ),
			'wind speed' => array( 'wind', 'wind_speed', Wind_Speed::class ),
			'wind direction' => array( 'wind', 'wind_direction', Wind_Direction::class ),
			'pressure relative' => array( 'pressure', 'relative', Pressure::class ),
		);
	}

	/**
	 * @testdox It should return correct class for $description
	 * @dataProvider measurement_class_provider
	 */
	public function test_get_measurement_class_returns_correct_class( string $group, string $key, string $expected_class, string $description = '' ): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_measurement_class( $group, $key );

		$this->assertEquals( $expected_class, $result );
	}

	/**
	 * @testdox It should return null for non-existent measurement keys
	 */
	public function test_get_measurement_class_returns_null_for_invalid(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_measurement_class( 'outdoor', 'non_existent_key' );

		$this->assertNull( $result );
	}

	/**
	 * @testdox It should return null for non-existent groups
	 */
	public function test_get_measurement_class_returns_null_for_invalid_group(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->get_measurement_class( 'invalid_group', 'temperature' );

		$this->assertNull( $result );
	}

	/**
	 * @testdox It should return same mapping for rainfall and rainfall_piezo
	 */
	public function test_rainfall_piezo_matches_rainfall(): void {
		$mapping = new Measurement_Mapping();
		$rainfall = $mapping->rainfall();
		$rainfall_piezo = $mapping->rainfall_piezo();

		$this->assertEquals( $rainfall, $rainfall_piezo );
	}

	/**
	 * @testdox It should include all expected water leak channels
	 */
	public function test_water_leak_mapping(): void {
		$mapping = new Measurement_Mapping();
		$result = $mapping->water_leak();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'leak_ch1', $result );
		$this->assertArrayHasKey( 'leak_ch2', $result );
		$this->assertArrayHasKey( 'leak_ch3', $result );
		$this->assertArrayHasKey( 'leak_ch4', $result );

		foreach ( $result as $key => $class ) {
			$this->assertEquals( Base_Measurement::TYPE_PERCENTAGE, $class );
		}
	}
}
