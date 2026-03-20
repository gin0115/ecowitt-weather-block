<?php

/**
 * Tests for the Conversion_Config class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion;

use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Temperature;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Pressure;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Wind_Speed;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Rain_Rate;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Solar_Radiation;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Volume;

/**
 * Tests for the Conversion_Config class.
 *
 * @group unit
 * @group observation
 * @group conversion
 */
class Test_Conversion_Config extends \WP_UnitTestCase {

	/**
	 * @testdox It should convert Celsius to Fahrenheit correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_temperature_celsius_to_fahrenheit(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$temp_config = $result[ Base_Measurement::TYPE_TEMPERATURE ];
		$celsius_to_fahrenheit = $temp_config['to_base_conversions'][ Temperature::UNIT_CELSIUS ];

		// Test: (value * 1.8) + 32
		$this->assertEqualsWithDelta( 32.0, $celsius_to_fahrenheit( 0 ), 0.001 );
		$this->assertEqualsWithDelta( 212.0, $celsius_to_fahrenheit( 100 ), 0.001 );
		$this->assertEqualsWithDelta( -40.0, $celsius_to_fahrenheit( -40 ), 0.001 );
		$this->assertEqualsWithDelta( 68.0, $celsius_to_fahrenheit( 20 ), 0.001 );
	}

	/**
	 * @testdox It should convert Fahrenheit to Celsius correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_temperature_fahrenheit_to_celsius(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$temp_config = $result[ Base_Measurement::TYPE_TEMPERATURE ];
		$fahrenheit_to_celsius = $temp_config['from_base_conversions'][ Temperature::UNIT_CELSIUS ];

		// Test: (value - 32) * (5/9)
		$this->assertEqualsWithDelta( 0.0, $fahrenheit_to_celsius( 32 ), 0.001 );
		$this->assertEqualsWithDelta( 100.0, $fahrenheit_to_celsius( 212 ), 0.001 );
		$this->assertEqualsWithDelta( -40.0, $fahrenheit_to_celsius( -40 ), 0.001 );
		$this->assertEqualsWithDelta( 20.0, $fahrenheit_to_celsius( 68 ), 0.001 );
	}

	/**
	 * @testdox It should format temperature values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_temperature_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$temp_config = $result[ Base_Measurement::TYPE_TEMPERATURE ];

		$celsius_format = $temp_config['format'][ Temperature::UNIT_CELSIUS ];
		$fahrenheit_format = $temp_config['format'][ Temperature::UNIT_FAHRENHEIT ];

		// Celsius: round(value, 1)
		$this->assertSame( 20.1, $celsius_format( 20.123 ) );
		$this->assertSame( 20.1, $celsius_format( 20.05 ) );

		// Fahrenheit: (int) value
		$this->assertSame( 68, $fahrenheit_format( 68.7 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.5 ) );
	}

	/**
	 * @testdox It should convert hPa to inHg correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_hpa_to_inhg(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];
		$hpa_to_inhg = $pressure_config['to_base_conversions'][ Pressure::UNIT_HPA ];

		// Test: value * 0.02953
		$this->assertEqualsWithDelta( 29.92, $hpa_to_inhg( 1013.25 ), 0.01 );
		$this->assertEqualsWithDelta( 14.77, $hpa_to_inhg( 500 ), 0.01 );
	}

	/**
	 * @testdox It should convert inHg to hPa correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_inhg_to_hpa(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];
		$inhg_to_hpa = $pressure_config['from_base_conversions'][ Pressure::UNIT_HPA ];

		// Test: value * 33.8639
		$this->assertEqualsWithDelta( 1013.25, $inhg_to_hpa( 29.92 ), 0.1 );
		$this->assertEqualsWithDelta( 500.17, $inhg_to_hpa( 14.77 ), 0.1 );
	}

	/**
	 * @testdox It should convert mmHg to inHg correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_mmhg_to_inhg(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];
		$mmhg_to_inhg = $pressure_config['to_base_conversions'][ Pressure::UNIT_MMHG ];

		// Test: value * 0.03937
		$this->assertEqualsWithDelta( 29.92, $mmhg_to_inhg( 760 ), 0.01 );
	}

	/**
	 * @testdox It should convert inHg to mmHg correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_inhg_to_mmhg(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];
		$inhg_to_mmhg = $pressure_config['from_base_conversions'][ Pressure::UNIT_MMHG ];

		// Test: value * 25.4
		$this->assertEqualsWithDelta( 760, $inhg_to_mmhg( 29.92 ), 0.1 );
	}

	/**
	 * @testdox It should format pressure values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];

		$hpa_format = $pressure_config['format'][ Pressure::UNIT_HPA ];
		$inhg_format = $pressure_config['format'][ Pressure::UNIT_INHG ];
		$mmhg_format = $pressure_config['format'][ Pressure::UNIT_MMHG ];

		// hPa: round(value, 2)
		$this->assertSame( 1013.25, $hpa_format( 1013.254 ) );

		// inHg: round(value, 2)
		$this->assertSame( 29.92, $inhg_format( 29.924 ) );

		// mmHg: round(value, 1)
		$this->assertSame( 760.0, $mmhg_format( 760.04 ) );
	}

	/**
	 * @testdox It should convert m/s to mph correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_mps_to_mph(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$mps_to_mph = $wind_config['to_base_conversions'][ Wind_Speed::UNIT_METERS_PER_SECOND ];

		// Test: value * 2.23694
		$this->assertEqualsWithDelta( 22.37, $mps_to_mph( 10 ), 0.01 );
		$this->assertEqualsWithDelta( 44.74, $mps_to_mph( 20 ), 0.01 );
	}

	/**
	 * @testdox It should convert mph to m/s correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_mph_to_mps(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$mph_to_mps = $wind_config['from_base_conversions'][ Wind_Speed::UNIT_METERS_PER_SECOND ];

		// Test: value * 0.44704
		$this->assertEqualsWithDelta( 10, $mph_to_mps( 22.37 ), 0.1 );
		$this->assertEqualsWithDelta( 20, $mph_to_mps( 44.74 ), 0.1 );
	}

	/**
	 * @testdox It should convert km/h to mph correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_kmh_to_mph(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$kmh_to_mph = $wind_config['to_base_conversions'][ Wind_Speed::UNIT_KILOMETERS_PER_HOUR ];

		// Test: value * 0.621371
		$this->assertEqualsWithDelta( 31.07, $kmh_to_mph( 50 ), 0.01 );
		$this->assertEqualsWithDelta( 62.14, $kmh_to_mph( 100 ), 0.01 );
	}

	/**
	 * @testdox It should convert mph to km/h correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_mph_to_kmh(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$mph_to_kmh = $wind_config['from_base_conversions'][ Wind_Speed::UNIT_KILOMETERS_PER_HOUR ];

		// Test: value * 1.60934
		$this->assertEqualsWithDelta( 50, $mph_to_kmh( 31.07 ), 0.1 );
		$this->assertEqualsWithDelta( 100, $mph_to_kmh( 62.14 ), 0.1 );
	}

	/**
	 * @testdox It should convert knots to mph correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_knots_to_mph(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$knots_to_mph = $wind_config['to_base_conversions'][ Wind_Speed::UNIT_KNOTS ];

		// Test: value * 1.15078
		$this->assertEqualsWithDelta( 11.51, $knots_to_mph( 10 ), 0.01 );
		$this->assertEqualsWithDelta( 23.02, $knots_to_mph( 20 ), 0.01 );
	}

	/**
	 * @testdox It should convert mph to knots correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_mph_to_knots(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$mph_to_knots = $wind_config['from_base_conversions'][ Wind_Speed::UNIT_KNOTS ];

		// Test: value * 0.868976
		$this->assertEqualsWithDelta( 10, $mph_to_knots( 11.51 ), 0.1 );
		$this->assertEqualsWithDelta( 20, $mph_to_knots( 23.02 ), 0.1 );
	}

	/**
	 * @testdox It should convert fpm to mph correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_fpm_to_mph(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$fpm_to_mph = $wind_config['to_base_conversions'][ Wind_Speed::UNIT_FEET_PER_MINUTE ];

		// Test: value * 0.01136
		$this->assertEqualsWithDelta( 1.14, $fpm_to_mph( 100 ), 0.01 );
		$this->assertEqualsWithDelta( 2.27, $fpm_to_mph( 200 ), 0.01 );
	}

	/**
	 * @testdox It should convert mph to fpm correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_mph_to_fpm(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];
		$mph_to_fpm = $wind_config['from_base_conversions'][ Wind_Speed::UNIT_FEET_PER_MINUTE ];

		// Test: value * 88.0
		$this->assertEqualsWithDelta( 100.32, $mph_to_fpm( 1.14 ), 0.1 );
		$this->assertEqualsWithDelta( 199.76, $mph_to_fpm( 2.27 ), 0.1 );
	}

	/**
	 * @testdox It should format wind speed values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];

		$mps_format = $wind_config['format'][ Wind_Speed::UNIT_METERS_PER_SECOND ];
		$kmh_format = $wind_config['format'][ Wind_Speed::UNIT_KILOMETERS_PER_HOUR ];
		$knots_format = $wind_config['format'][ Wind_Speed::UNIT_KNOTS ];
		$mph_format = $wind_config['format'][ Wind_Speed::UNIT_MILES_PER_HOUR ];
		$beaufort_format = $wind_config['format'][ Wind_Speed::UNIT_BEAUFORT ];
		$fpm_format = $wind_config['format'][ Wind_Speed::UNIT_FEET_PER_MINUTE ];

		// m/s: round(value, 1)
		$this->assertSame( 10.1, $mps_format( 10.123 ) );

		// km/h: (int) value
		$this->assertSame( 50, $kmh_format( 50.7 ) );

		// knots: round(value, 1)
		$this->assertSame( 10.1, $knots_format( 10.123 ) );

		// mph: (int) value
		$this->assertSame( 25, $mph_format( 25.7 ) );

		// Beaufort: (int) value
		$this->assertSame( 3, $beaufort_format( 3.7 ) );

		// fpm: (int) value
		$this->assertSame( 100, $fpm_format( 100.7 ) );
	}

	/**
	 * @testdox It should convert mm to inches correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rainfall_mm_to_inches(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rainfall_config = $result[ Base_Measurement::TYPE_RAINFALL ];
		$mm_to_inches = $rainfall_config['to_base_conversions'][ Rainfall::UNIT_MILLIMETERS ];

		// Test: value * 0.0393701
		$this->assertEqualsWithDelta( 1.0, $mm_to_inches( 25.4 ), 0.001 );
		$this->assertEqualsWithDelta( 0.5, $mm_to_inches( 12.7 ), 0.001 );
	}

	/**
	 * @testdox It should convert inches to mm correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rainfall_inches_to_mm(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rainfall_config = $result[ Base_Measurement::TYPE_RAINFALL ];
		$inches_to_mm = $rainfall_config['from_base_conversions'][ Rainfall::UNIT_MILLIMETERS ];

		// Test: value * 25.4
		$this->assertEqualsWithDelta( 25.4, $inches_to_mm( 1.0 ), 0.001 );
		$this->assertEqualsWithDelta( 12.7, $inches_to_mm( 0.5 ), 0.001 );
	}

	/**
	 * @testdox It should format rainfall values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rainfall_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rainfall_config = $result[ Base_Measurement::TYPE_RAINFALL ];

		$mm_format = $rainfall_config['format'][ Rainfall::UNIT_MILLIMETERS ];
		$inches_format = $rainfall_config['format'][ Rainfall::UNIT_INCHES ];

		// mm: round(value, 1)
		$this->assertSame( 25.1, $mm_format( 25.123 ) );

		// inches: round(value, 2)
		$this->assertSame( 1.00, $inches_format( 1.004 ) );
	}

	/**
	 * @testdox It should convert mm/hr to in/hr correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rain_rate_mmhr_to_inhr(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rain_rate_config = $result[ Base_Measurement::TYPE_RAIN_RATE ];
		$mmhr_to_inhr = $rain_rate_config['to_base_conversions'][ Rain_Rate::UNIT_MILLIMETERS_PER_HOUR ];

		// Test: value * 0.0393701
		$this->assertEqualsWithDelta( 1.0, $mmhr_to_inhr( 25.4 ), 0.001 );
		$this->assertEqualsWithDelta( 0.5, $mmhr_to_inhr( 12.7 ), 0.001 );
	}

	/**
	 * @testdox It should convert in/hr to mm/hr correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rain_rate_inhr_to_mmhr(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rain_rate_config = $result[ Base_Measurement::TYPE_RAIN_RATE ];
		$inhr_to_mmhr = $rain_rate_config['from_base_conversions'][ Rain_Rate::UNIT_MILLIMETERS_PER_HOUR ];

		// Test: value * 25.4
		$this->assertEqualsWithDelta( 25.4, $inhr_to_mmhr( 1.0 ), 0.001 );
		$this->assertEqualsWithDelta( 12.7, $inhr_to_mmhr( 0.5 ), 0.001 );
	}

	/**
	 * @testdox It should format rain rate values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rain_rate_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rain_rate_config = $result[ Base_Measurement::TYPE_RAIN_RATE ];

		$mmhr_format = $rain_rate_config['format'][ Rain_Rate::UNIT_MILLIMETERS_PER_HOUR ];
		$inhr_format = $rain_rate_config['format'][ Rain_Rate::UNIT_INCHES_PER_HOUR ];

		// mm/hr: round(value, 1)
		$this->assertSame( 25.1, $mmhr_format( 25.123 ) );

		// in/hr: round(value, 2)
		$this->assertSame( 1.00, $inhr_format( 1.004 ) );
	}

	/**
	 * @testdox It should convert lux to W/m² correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_lux_to_wm2(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];
		$lux_to_wm2 = $solar_config['to_base_conversions'][ Solar_Radiation::UNIT_LUX ];

		// Test: value * 0.0079
		$this->assertEqualsWithDelta( 79.0, $lux_to_wm2( 10000 ), 0.1 );
		$this->assertEqualsWithDelta( 158.0, $lux_to_wm2( 20000 ), 0.1 );
	}

	/**
	 * @testdox It should convert W/m² to lux correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_wm2_to_lux(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];
		$wm2_to_lux = $solar_config['from_base_conversions'][ Solar_Radiation::UNIT_LUX ];

		// Test: value * 126.58
		$this->assertEqualsWithDelta( 10000, $wm2_to_lux( 79.0 ), 1 );
		$this->assertEqualsWithDelta( 20000, $wm2_to_lux( 158.0 ), 1 );
	}

	/**
	 * @testdox It should convert fc to W/m² correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_fc_to_wm2(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];
		$fc_to_wm2 = $solar_config['to_base_conversions'][ Solar_Radiation::UNIT_FOOT_CANDLES ];

		// Test: value * 0.0929
		$this->assertEqualsWithDelta( 9.29, $fc_to_wm2( 100 ), 0.01 );
		$this->assertEqualsWithDelta( 18.58, $fc_to_wm2( 200 ), 0.01 );
	}

	/**
	 * @testdox It should convert W/m² to fc correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_wm2_to_fc(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];
		$wm2_to_fc = $solar_config['from_base_conversions'][ Solar_Radiation::UNIT_FOOT_CANDLES ];

		// Test: value * 10.764
		$this->assertEqualsWithDelta( 100, $wm2_to_fc( 9.29 ), 0.1 );
		$this->assertEqualsWithDelta( 200, $wm2_to_fc( 18.58 ), 0.1 );
	}

	/**
	 * @testdox It should format solar radiation values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];

		$lux_format = $solar_config['format'][ Solar_Radiation::UNIT_LUX ];
		$fc_format = $solar_config['format'][ Solar_Radiation::UNIT_FOOT_CANDLES ];
		$wm2_format = $solar_config['format'][ Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER ];

		// lux: (int) value
		$this->assertSame( 1000, $lux_format( 1000.7 ) );

		// fc: round(value, 1)
		$this->assertSame( 100.1, $fc_format( 100.123 ) );

		// W/m²: round(value, 1)
		$this->assertSame( 79.1, $wm2_format( 79.123 ) );
	}

	/**
	 * @testdox It should convert m³ to L correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_m3_to_liters(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];
		$m3_to_liters = $volume_config['to_base_conversions'][ Volume::UNIT_CUBIC_METERS ];

		// Test: value * 1000.0
		$this->assertEqualsWithDelta( 1000.0, $m3_to_liters( 1.0 ), 0.001 );
		$this->assertEqualsWithDelta( 2000.0, $m3_to_liters( 2.0 ), 0.001 );
	}

	/**
	 * @testdox It should convert L to m³ correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_liters_to_m3(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];
		$liters_to_m3 = $volume_config['from_base_conversions'][ Volume::UNIT_CUBIC_METERS ];

		// Test: value * 0.001
		$this->assertEqualsWithDelta( 1.0, $liters_to_m3( 1000.0 ), 0.001 );
		$this->assertEqualsWithDelta( 2.0, $liters_to_m3( 2000.0 ), 0.001 );
	}

	/**
	 * @testdox It should convert gal to L correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_gallons_to_liters(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];
		$gal_to_liters = $volume_config['to_base_conversions'][ Volume::UNIT_GALLONS ];

		// Test: value * 0.264172
		$this->assertEqualsWithDelta( 0.264, $gal_to_liters( 1.0 ), 0.001 );
		$this->assertEqualsWithDelta( 0.528, $gal_to_liters( 2.0 ), 0.001 );
	}

	/**
	 * @testdox It should convert L to gal correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_liters_to_gallons(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];
		$liters_to_gal = $volume_config['from_base_conversions'][ Volume::UNIT_GALLONS ];

		// Test: value * 3.78541
		$this->assertEqualsWithDelta( 14.33, $liters_to_gal( 3.785 ), 0.1 );
		$this->assertEqualsWithDelta( 28.66, $liters_to_gal( 7.571 ), 0.1 );
	}

	/**
	 * @testdox It should format volume values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];

		$liters_format = $volume_config['format'][ Volume::UNIT_LITERS ];
		$m3_format = $volume_config['format'][ Volume::UNIT_CUBIC_METERS ];
		$gal_format = $volume_config['format'][ Volume::UNIT_GALLONS ];

		// L: round(value, 1)
		$this->assertSame( 100.1, $liters_format( 100.123 ) );

		// m³: round(value, 3)
		$this->assertSame( 1.000, $m3_format( 1.0004 ) );

		// gal: round(value, 2)
		$this->assertSame( 1.00, $gal_format( 1.004 ) );
	}

	/**
	 * Data provider for all compass directions tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_all_directions_data_provider(): array {
		return array(
			// All 16 compass directions
			array( 0, 'N', 'North at 0 degrees' ),
			array( 360, 'N', 'North at 360 degrees (wrap)' ),
			array( 22.5, 'NNE', 'North-Northeast at 22.5 degrees' ),
			array( 45, 'NE', 'Northeast at 45 degrees' ),
			array( 67.5, 'ENE', 'East-Northeast at 67.5 degrees' ),
			array( 90, 'E', 'East at 90 degrees' ),
			array( 112.5, 'ESE', 'East-Southeast at 112.5 degrees' ),
			array( 135, 'SE', 'Southeast at 135 degrees' ),
			array( 157.5, 'SSE', 'South-Southeast at 157.5 degrees' ),
			array( 180, 'S', 'South at 180 degrees' ),
			array( 202.5, 'SSW', 'South-Southwest at 202.5 degrees' ),
			array( 225, 'SW', 'Southwest at 225 degrees' ),
			array( 247.5, 'WSW', 'West-Southwest at 247.5 degrees' ),
			array( 270, 'W', 'West at 270 degrees' ),
			array( 292.5, 'WNW', 'West-Northwest at 292.5 degrees' ),
			array( 315, 'NW', 'Northwest at 315 degrees' ),
			array( 337.5, 'NNW', 'North-Northwest at 337.5 degrees' ),
		);
	}

	/**
	 * @testdox It should convert degrees to compass correctly for all 16 directions
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_all_directions_data_provider
	 */
	public function test_degrees_to_compass_all_directions( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for boundary conditions tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_boundary_conditions_data_provider(): array {
		return array(
			// Test exact boundaries between directions
			array( 11.25, 'N', 'N boundary at 11.25 degrees' ),
			array( 33.75, 'NNE', 'NNE boundary at 33.75 degrees' ),
			array( 56.25, 'NE', 'NE boundary at 56.25 degrees' ),
			array( 78.75, 'ENE', 'ENE boundary at 78.75 degrees' ),
			array( 101.25, 'E', 'E boundary at 101.25 degrees' ),
			array( 123.75, 'ESE', 'ESE boundary at 123.75 degrees' ),
			array( 146.25, 'SE', 'SE boundary at 146.25 degrees' ),
			array( 168.75, 'SSE', 'SSE boundary at 168.75 degrees' ),
			array( 191.25, 'S', 'S boundary at 191.25 degrees' ),
			array( 213.75, 'SSW', 'SSW boundary at 213.75 degrees' ),
			array( 236.25, 'SW', 'SW boundary at 236.25 degrees' ),
			array( 258.75, 'WSW', 'WSW boundary at 258.75 degrees' ),
			array( 281.25, 'W', 'W boundary at 281.25 degrees' ),
			array( 303.75, 'WNW', 'WNW boundary at 303.75 degrees' ),
			array( 326.25, 'NW', 'NW boundary at 326.25 degrees' ),
			array( 348.75, 'NNW', 'NNW boundary at 348.75 degrees' ),
		);
	}

	/**
	 * @testdox It should handle boundary conditions for degrees to compass conversion
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_boundary_conditions_data_provider
	 */
	public function test_degrees_to_compass_boundary_conditions( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for negative degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_negative_degrees_data_provider(): array {
		return array(
			// Test negative degrees (should be converted to positive using absint)
			array( -1, 'N', 'Negative 1 degree becomes positive 1' ),
			array( -360, 'N', 'Negative 360 degrees becomes positive 360' ),
			array( -720, 'N', 'Negative 720 degrees becomes positive 720' ),
			array( -270, 'W', 'Negative 270 degrees becomes positive 270' ),
			array( -180, 'S', 'Negative 180 degrees becomes positive 180' ),
			array( -90, 'E', 'Negative 90 degrees becomes positive 90' ),
		);
	}

	/**
	 * @testdox It should handle negative degrees by converting to positive
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_negative_degrees_data_provider
	 */
	public function test_degrees_to_compass_negative_degrees( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for large degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_large_degrees_data_provider(): array {
		return array(
			// Test degrees > 360 (should wrap around)
			array( 720, 'N', '720 degrees wraps to 0 degrees (N)' ),
			array( 1080, 'N', '1080 degrees wraps to 0 degrees (N)' ),
			array( 450, 'E', '450 degrees wraps to 90 degrees (E)' ),
			array( 540, 'S', '540 degrees wraps to 180 degrees (S)' ),
			array( 630, 'W', '630 degrees wraps to 270 degrees (W)' ),
			array( 405, 'NE', '405 degrees wraps to 45 degrees (NE)' ),
			array( 585, 'SW', '585 degrees wraps to 225 degrees (SW)' ),
		);
	}

	/**
	 * @testdox It should handle degrees greater than 360 by wrapping around
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_large_degrees_data_provider
	 */
	public function test_degrees_to_compass_large_degrees( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for fractional degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_fractional_degrees_data_provider(): array {
		return array(
			// Test fractional degrees that should round to specific directions
			array( 0.1, 'N', 'Small fractional degree 0.1' ),
			array( 5.0, 'N', 'Fractional degree 5.0' ),
			array( 15.0, 'NNE', 'Fractional degree 15.0' ),
			array( 30.0, 'NNE', 'Fractional degree 30.0' ),
			array( 40.0, 'NE', 'Fractional degree 40.0' ),
			array( 50.0, 'NE', 'Fractional degree 50.0' ),
			array( 85.0, 'E', 'Fractional degree 85.0' ),
			array( 95.0, 'E', 'Fractional degree 95.0' ),
			array( 175.0, 'S', 'Fractional degree 175.0' ),
			array( 185.0, 'S', 'Fractional degree 185.0' ),
			array( 265.0, 'W', 'Fractional degree 265.0' ),
			array( 275.0, 'W', 'Fractional degree 275.0' ),
		);
	}

	/**
	 * @testdox It should handle fractional degrees correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_fractional_degrees_data_provider
	 */
	public function test_degrees_to_compass_fractional_degrees( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for zero and small degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_zero_and_small_degrees_data_provider(): array {
		return array(
			// Test zero and very small values
			array( 0.0, 'N', 'Zero degrees' ),
			array( 0.001, 'N', 'Very small degree 0.001' ),
			array( 0.01, 'N', 'Small degree 0.01' ),
			array( 0.1, 'N', 'Small degree 0.1' ),
			array( 1.0, 'N', 'Small degree 1.0' ),
			array( 5.0, 'N', 'Small degree 5.0' ),
			array( 10.0, 'N', 'Small degree 10.0' ),
		);
	}

	/**
	 * @testdox It should handle zero and very small degrees
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_zero_and_small_degrees_data_provider
	 */
	public function test_degrees_to_compass_zero_and_small_degrees( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for high precision decimal degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_high_precision_data_provider(): array {
		return array(
			// Small decimal values
			array( 0.123456789, 'N', 'Small decimal values' ),
			array( 5.999999999, 'N', 'Values just under 6' ),
			array( 11.250000001, 'N', 'Values just over 11.25' ),
			
			// NNE range
			array( 22.499999999, 'NNE', 'Values just under 22.5' ),
			array( 33.750000001, 'NNE', 'Values just over 33.75' ),
			
			// NE range
			array( 44.999999999, 'NE', 'Values just under 45' ),
			array( 56.250000001, 'NE', 'Values just over 56.25' ),
			
			// ENE range
			array( 67.499999999, 'ENE', 'Values just under 67.5' ),
			array( 78.750000001, 'ENE', 'Values just over 78.75' ),
			
			// E range
			array( 89.999999999, 'E', 'Values just under 90' ),
			array( 101.250000001, 'E', 'Values just over 101.25' ),
			
			// ESE range
			array( 112.499999999, 'ESE', 'Values just under 112.5' ),
			array( 123.750000001, 'ESE', 'Values just over 123.75' ),
			
			// SE range
			array( 134.999999999, 'SE', 'Values just under 135' ),
			array( 146.250000001, 'SE', 'Values just over 146.25' ),
			
			// SSE range
			array( 157.499999999, 'SSE', 'Values just under 157.5' ),
			array( 168.750000001, 'SSE', 'Values just over 168.75' ),
			
			// S range
			array( 179.999999999, 'S', 'Values just under 180' ),
			array( 191.250000001, 'S', 'Values just over 191.25' ),
			
			// SSW range
			array( 202.499999999, 'SSW', 'Values just under 202.5' ),
			array( 213.750000001, 'SSW', 'Values just over 213.75' ),
			
			// SW range
			array( 224.999999999, 'SW', 'Values just under 225' ),
			array( 236.250000001, 'SW', 'Values just over 236.25' ),
			
			// WSW range
			array( 247.499999999, 'WSW', 'Values just under 247.5' ),
			array( 258.750000001, 'WSW', 'Values just over 258.75' ),
			
			// W range
			array( 269.999999999, 'W', 'Values just under 270' ),
			array( 281.250000001, 'W', 'Values just over 281.25' ),
			
			// WNW range
			array( 292.499999999, 'WNW', 'Values just under 292.5' ),
			array( 303.750000001, 'WNW', 'Values just over 303.75' ),
			
			// NW range
			array( 314.999999999, 'NW', 'Values just under 315' ),
			array( 326.250000001, 'NW', 'Values just over 326.25' ),
			
			// NNW range
			array( 337.499999999, 'NNW', 'Values just under 337.5' ),
		);
	}

	/**
	 * @testdox It should handle high precision decimal degrees correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_high_precision_data_provider
	 */
	public function test_degrees_to_compass_high_precision_decimals( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for middle of range degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_middle_of_range_data_provider(): array {
		return array(
			// Test degrees in the middle of each compass range
			array( 5.625, 'N', 'Middle of N range (0-11.25)' ),
			array( 16.875, 'NNE', 'Middle of NNE range (11.25-33.75)' ),
			array( 39.375, 'NE', 'Middle of NE range (33.75-56.25)' ),
			array( 61.875, 'ENE', 'Middle of ENE range (56.25-78.75)' ),
			array( 84.375, 'E', 'Middle of E range (78.75-101.25)' ),
			array( 106.875, 'ESE', 'Middle of ESE range (101.25-123.75)' ),
			array( 129.375, 'SE', 'Middle of SE range (123.75-146.25)' ),
			array( 151.875, 'SSE', 'Middle of SSE range (146.25-168.75)' ),
			array( 174.375, 'S', 'Middle of S range (168.75-191.25)' ),
			array( 196.875, 'SSW', 'Middle of SSW range (191.25-213.75)' ),
			array( 219.375, 'SW', 'Middle of SW range (213.75-236.25)' ),
			array( 241.875, 'WSW', 'Middle of WSW range (236.25-258.75)' ),
			array( 264.375, 'W', 'Middle of W range (258.75-281.25)' ),
			array( 286.875, 'WNW', 'Middle of WNW range (281.25-303.75)' ),
			array( 309.375, 'NW', 'Middle of NW range (303.75-326.25)' ),
			array( 331.875, 'NNW', 'Middle of NNW range (326.25-348.75)' ),
		);
	}

	/**
	 * @testdox It should handle middle-of-range degrees correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_middle_of_range_data_provider
	 */
	public function test_degrees_to_compass_middle_of_range( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for very close boundary degrees tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_very_close_boundaries_data_provider(): array {
		return array(
			// N range - values close to 11.25 boundary
			array( 11.24, 'N', 'Just under NNE boundary' ),
			array( 11.26, 'N', 'Just over NNE boundary' ),
			
			// NNE range - values close to 33.75 boundary
			array( 33.74, 'NNE', 'Just under NE boundary' ),
			array( 33.76, 'NNE', 'Just over NE boundary' ),
			
			// NE range - values close to 56.25 boundary
			array( 56.24, 'NE', 'Just under ENE boundary' ),
			array( 56.26, 'NE', 'Just over ENE boundary' ),
			
			// ENE range - values close to 78.75 boundary
			array( 78.74, 'ENE', 'Just under E boundary' ),
			array( 78.76, 'ENE', 'Just over E boundary' ),
			
			// E range - values close to 101.25 boundary
			array( 101.24, 'E', 'Just under ESE boundary' ),
			array( 101.26, 'E', 'Just over ESE boundary' ),
			
			// ESE range - values close to 123.75 boundary
			array( 123.74, 'ESE', 'Just under SE boundary' ),
			array( 123.76, 'ESE', 'Just over SE boundary' ),
			
			// SE range - values close to 146.25 boundary
			array( 146.24, 'SE', 'Just under SSE boundary' ),
			array( 146.26, 'SE', 'Just over SSE boundary' ),
			
			// SSE range - values close to 168.75 boundary
			array( 168.74, 'SSE', 'Just under S boundary' ),
			array( 168.76, 'SSE', 'Just over S boundary' ),
			
			// S range - values close to 191.25 boundary
			array( 191.24, 'S', 'Just under SSW boundary' ),
			array( 191.26, 'S', 'Just over SSW boundary' ),
			
			// SSW range - values close to 213.75 boundary
			array( 213.74, 'SSW', 'Just under SW boundary' ),
			array( 213.76, 'SSW', 'Just over SW boundary' ),
			
			// SW range - values close to 236.25 boundary
			array( 236.24, 'SW', 'Just under WSW boundary' ),
			array( 236.26, 'SW', 'Just over WSW boundary' ),
			
			// WSW range - values close to 258.75 boundary
			array( 258.74, 'WSW', 'Just under W boundary' ),
			array( 258.76, 'WSW', 'Just over W boundary' ),
			
			// W range - values close to 281.25 boundary
			array( 281.24, 'W', 'Just under WNW boundary' ),
			array( 281.26, 'W', 'Just over WNW boundary' ),
			
			// WNW range - values close to 303.75 boundary
			array( 303.74, 'WNW', 'Just under NW boundary' ),
			array( 303.76, 'WNW', 'Just over NW boundary' ),
			
			// NW range - values close to 326.25 boundary
			array( 326.24, 'NW', 'Just under NNW boundary' ),
			array( 326.26, 'NW', 'Just over NNW boundary' ),
			
			// NNW range - values close to 348.75 boundary (wrapping to N)
			array( 348.74, 'NNW', 'Just under N boundary (wrap)' ),
			array( 348.76, 'NNW', 'Just over N boundary (wrap)' ),
		);
	}

	/**
	 * @testdox It should handle very close boundary degrees correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_very_close_boundaries_data_provider
	 */
	public function test_degrees_to_compass_very_close_boundaries( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * Data provider for extreme precision floating point tests
	 * 
	 * @return array Array of [degrees, expected_direction, description]
	 */
	public function compass_extreme_precision_data_provider(): array {
		return array(
			// Test extreme precision floating point numbers
			// All values are truncated by absint() before calculation
			array( 0.0000000001, 'N', 'Very small decimal truncated to 0' ),
			array( 11.249999999999999, 'N', '11.249... truncated to 11' ),
			array( 11.250000000000001, 'N', '11.250... truncated to 11' ),
			array( 33.749999999999999, 'NNE', '33.749... truncated to 33' ),
			array( 33.750000000000001, 'NNE', '33.750... truncated to 33' ),
			array( 56.249999999999999, 'NE', '56.249... truncated to 56' ),
			array( 56.250000000000001, 'NE', '56.250... truncated to 56' ),
			array( 78.749999999999999, 'ENE', '78.749... truncated to 78' ),
			array( 78.750000000000001, 'ENE', '78.750... truncated to 78' ),
			array( 101.249999999999999, 'E', '101.249... truncated to 101' ),
			array( 101.250000000000001, 'E', '101.250... truncated to 101' ),
			array( 123.749999999999999, 'ESE', '123.749... truncated to 123' ),
			array( 123.750000000000001, 'ESE', '123.750... truncated to 123' ),
			array( 146.249999999999999, 'SE', '146.249... truncated to 146' ),
			array( 146.250000000000001, 'SE', '146.250... truncated to 146' ),
			array( 168.749999999999999, 'SSE', '168.749... truncated to 168' ),
			array( 168.750000000000001, 'SSE', '168.750... truncated to 168' ),
			array( 191.249999999999999, 'S', '191.249... truncated to 191' ),
			array( 191.250000000000001, 'S', '191.250... truncated to 191' ),
			array( 213.749999999999999, 'SSW', '213.749... truncated to 213' ),
			array( 213.750000000000001, 'SSW', '213.750... truncated to 213' ),
			array( 236.249999999999999, 'SW', '236.249... truncated to 236' ),
			array( 236.250000000000001, 'SW', '236.250... truncated to 236' ),
			array( 258.749999999999999, 'WSW', '258.749... truncated to 258' ),
			array( 258.750000000000001, 'WSW', '258.750... truncated to 258' ),
			array( 281.249999999999999, 'W', '281.249... truncated to 281' ),
			array( 281.250000000000001, 'W', '281.250... truncated to 281' ),
			array( 303.749999999999999, 'WNW', '303.749... truncated to 303' ),
			array( 303.750000000000001, 'WNW', '303.750... truncated to 303' ),
			array( 326.249999999999999, 'NW', '326.249... truncated to 326' ),
			array( 326.250000000000001, 'NW', '326.250... truncated to 326' ),
			array( 348.749999999999999, 'NNW', '348.749... truncated to 348' ),
			array( 348.750000000000001, 'NNW', '348.750... truncated to 348' ),
		);
	}

	/**
	 * @testdox It should handle extreme precision and floating point edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 * @dataProvider compass_extreme_precision_data_provider
	 */
	public function test_degrees_to_compass_extreme_precision( float $degrees, string $expected, string $description ): void {
		$config = new Conversion_Config();
		$this->assertSame( $expected, $config->degrees_to_compass( $degrees ), $description );
	}

	/**
	 * @testdox It should handle very large numbers with wrapping
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 */
	public function test_degrees_to_compass_very_large_numbers(): void {
		$config = new Conversion_Config();

		// Test very large numbers that should wrap around multiple times
		$this->assertSame( 'N', $config->degrees_to_compass( 3600 ) ); // 10 full rotations
		$this->assertSame( 'E', $config->degrees_to_compass( 3600 + 90 ) ); // 10 rotations + 90°
		$this->assertSame( 'S', $config->degrees_to_compass( 3600 + 180 ) ); // 10 rotations + 180°
		$this->assertSame( 'W', $config->degrees_to_compass( 3600 + 270 ) ); // 10 rotations + 270°
		$this->assertSame( 'NE', $config->degrees_to_compass( 3600 + 45 ) ); // 10 rotations + 45°
		$this->assertSame( 'SW', $config->degrees_to_compass( 3600 + 225 ) ); // 10 rotations + 225°
		$this->assertSame( 'N', $config->degrees_to_compass( 7200 ) ); // 20 full rotations
		$this->assertSame( 'NNE', $config->degrees_to_compass( 7200 + 22.5 ) ); // 20 rotations + 22.5°
		$this->assertSame( 'N', $config->degrees_to_compass( 36000 ) ); // 100 full rotations
		$this->assertSame( 'E', $config->degrees_to_compass( 36000 + 90.123456789 ) ); // 100 rotations + precision
	}

	/**
	 * @testdox It should handle very negative numbers with wrapping
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::degrees_to_compass
	 */
	public function test_degrees_to_compass_very_negative_numbers(): void {
		$config = new Conversion_Config();

		// Test very negative numbers that should wrap around multiple times
		$this->assertSame( 'N', $config->degrees_to_compass( -3600 ) ); // absint(-3600) = 3600, 3600/22.5 = 160, 160%16 = 0 = N
		$this->assertSame( 'E', $config->degrees_to_compass( -3600 - 90 ) ); // absint(-3690) = 3690, 3690/22.5 = 164, 164%16 = 4 = E
		$this->assertSame( 'S', $config->degrees_to_compass( -3600 - 180 ) ); // absint(-3780) = 3780, 3780/22.5 = 168, 168%16 = 8 = S
		$this->assertSame( 'W', $config->degrees_to_compass( -3600 - 270 ) ); // absint(-3870) = 3870, 3870/22.5 = 172, 172%16 = 12 = W
		$this->assertSame( 'NE', $config->degrees_to_compass( -3600 - 45 ) ); // absint(-3645) = 3645, 3645/22.5 = 162, 162%16 = 2 = NE
		$this->assertSame( 'SW', $config->degrees_to_compass( -3600 - 225 ) ); // absint(-3825) = 3825, 3825/22.5 = 170, 170%16 = 10 = SW
		$this->assertSame( 'N', $config->degrees_to_compass( -7200 ) ); // -20 full rotations
		$this->assertSame( 'NNE', $config->degrees_to_compass( -7200 - 22.5 ) ); // absint(-7222.5) = 7222, 7222/22.5 = 320.978, round = 321, 321%16 = 1 = NNE
		$this->assertSame( 'N', $config->degrees_to_compass( -36000 ) ); // -100 full rotations
		$this->assertSame( 'E', $config->degrees_to_compass( -36000 - 90.123456789 ) ); // absint(-36090.123456789) = 36090, 36090/22.5 = 1604, round = 1604, 1604%16 = 4 = E
	}

	/**
	 * @testdox It should convert compass to degrees correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::compass_to_degrees
	 */
	public function test_compass_to_degrees(): void {
		$config = new Conversion_Config();

		// Test all 16 compass directions
		$this->assertSame( 0.0, $config->compass_to_degrees( 'N' ) );
		$this->assertSame( 22.5, $config->compass_to_degrees( 'NNE' ) );
		$this->assertSame( 45.0, $config->compass_to_degrees( 'NE' ) );
		$this->assertSame( 67.5, $config->compass_to_degrees( 'ENE' ) );
		$this->assertSame( 90.0, $config->compass_to_degrees( 'E' ) );
		$this->assertSame( 112.5, $config->compass_to_degrees( 'ESE' ) );
		$this->assertSame( 135.0, $config->compass_to_degrees( 'SE' ) );
		$this->assertSame( 157.5, $config->compass_to_degrees( 'SSE' ) );
		$this->assertSame( 180.0, $config->compass_to_degrees( 'S' ) );
		$this->assertSame( 202.5, $config->compass_to_degrees( 'SSW' ) );
		$this->assertSame( 225.0, $config->compass_to_degrees( 'SW' ) );
		$this->assertSame( 247.5, $config->compass_to_degrees( 'WSW' ) );
		$this->assertSame( 270.0, $config->compass_to_degrees( 'W' ) );
		$this->assertSame( 292.5, $config->compass_to_degrees( 'WNW' ) );
		$this->assertSame( 315.0, $config->compass_to_degrees( 'NW' ) );
		$this->assertSame( 337.5, $config->compass_to_degrees( 'NNW' ) );

		// Test invalid input
		$this->assertSame( 0.0, $config->compass_to_degrees( 'INVALID' ) );
		$this->assertSame( 0.0, $config->compass_to_degrees( '' ) );
	}

	/**
	 * @testdox It should format wind direction values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_direction_formatting(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_dir_config = $result[ Base_Measurement::TYPE_WIND_DIRECTION ];

		$degrees_format = $wind_dir_config['format']['deg'];
		$compass_format = $wind_dir_config['format']['cardinal'];

		// degrees: (int) value
		$this->assertSame( 90, $degrees_format( 90.7 ) );

		// compass: value (no change)
		$this->assertSame( 'N', $compass_format( 'N' ) );
	}

	/**
	 * @testdox It should format temperature values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_temperature_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$temp_config = $result[ Base_Measurement::TYPE_TEMPERATURE ];

		$celsius_format = $temp_config['format'][ Temperature::UNIT_CELSIUS ];
		$fahrenheit_format = $temp_config['format'][ Temperature::UNIT_FAHRENHEIT ];

		// Celsius: round(value, 1) - test various decimal scenarios
		$this->assertSame( 20.0, $celsius_format( 20.0 ) );
		$this->assertSame( 20.0, $celsius_format( 20.04 ) );
		$this->assertSame( 20.1, $celsius_format( 20.05 ) );
		$this->assertSame( 20.1, $celsius_format( 20.06 ) );
		$this->assertSame( 20.1, $celsius_format( 20.14 ) );
		$this->assertSame( 20.2, $celsius_format( 20.15 ) );
		$this->assertSame( 20.2, $celsius_format( 20.16 ) );
		$this->assertSame( 20.1, $celsius_format( 20.123 ) );
		$this->assertSame( 20.2, $celsius_format( 20.156 ) );
		$this->assertSame( 20.0, $celsius_format( 20.049 ) );
		$this->assertSame( 20.1, $celsius_format( 20.051 ) );
		$this->assertSame( -20.0, $celsius_format( -20.04 ) );
		$this->assertSame( -20.1, $celsius_format( -20.05 ) );
		$this->assertSame( -20.1, $celsius_format( -20.06 ) );
		$this->assertSame( 0.0, $celsius_format( 0.04 ) );
		$this->assertSame( 0.1, $celsius_format( 0.05 ) );
		$this->assertSame( 0.1, $celsius_format( 0.06 ) );

		// Fahrenheit: (int) value - test various scenarios
		$this->assertSame( 68, $fahrenheit_format( 68.0 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.1 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.4 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.49 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.5 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.6 ) );
		$this->assertSame( 68, $fahrenheit_format( 68.9 ) );
		$this->assertSame( 69, $fahrenheit_format( 69.0 ) );
		$this->assertSame( 0, $fahrenheit_format( 0.0 ) );
		$this->assertSame( 0, $fahrenheit_format( 0.4 ) );
		$this->assertSame( 0, $fahrenheit_format( 0.5 ) );
		$this->assertSame( 0, $fahrenheit_format( 0.6 ) );
		$this->assertSame( 0, $fahrenheit_format( -0.6 ) );
		$this->assertSame( 0, $fahrenheit_format( -0.5 ) );
		$this->assertSame( 0, $fahrenheit_format( -0.4 ) );
		$this->assertSame( -1, $fahrenheit_format( -1.0 ) );
		$this->assertSame( -1, $fahrenheit_format( -1.1 ) );
	}

	/**
	 * @testdox It should format pressure values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_pressure_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$pressure_config = $result[ Base_Measurement::TYPE_PRESSURE ];

		$hpa_format = $pressure_config['format'][ Pressure::UNIT_HPA ];
		$inhg_format = $pressure_config['format'][ Pressure::UNIT_INHG ];
		$mmhg_format = $pressure_config['format'][ Pressure::UNIT_MMHG ];

		// hPa: round(value, 2) - test various decimal scenarios
		$this->assertSame( 1013.25, $hpa_format( 1013.25 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.254 ) );
		$this->assertSame( 1013.26, $hpa_format( 1013.255 ) );
		$this->assertSame( 1013.26, $hpa_format( 1013.256 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.250 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.251 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.252 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.253 ) );
		$this->assertSame( 1013.25, $hpa_format( 1013.254 ) );
		$this->assertSame( 1013.26, $hpa_format( 1013.255 ) );
		$this->assertSame( 0.00, $hpa_format( 0.004 ) );
		$this->assertSame( 0.01, $hpa_format( 0.005 ) );
		$this->assertSame( 0.01, $hpa_format( 0.006 ) );
		$this->assertSame( -1013.25, $hpa_format( -1013.254 ) );
		$this->assertSame( -1013.26, $hpa_format( -1013.255 ) );

		// inHg: round(value, 2) - test various decimal scenarios
		$this->assertSame( 29.92, $inhg_format( 29.92 ) );
		$this->assertSame( 29.92, $inhg_format( 29.924 ) );
		$this->assertSame( 29.93, $inhg_format( 29.925 ) );
		$this->assertSame( 29.93, $inhg_format( 29.926 ) );
		$this->assertSame( 29.92, $inhg_format( 29.920 ) );
		$this->assertSame( 29.92, $inhg_format( 29.921 ) );
		$this->assertSame( 29.92, $inhg_format( 29.922 ) );
		$this->assertSame( 29.92, $inhg_format( 29.923 ) );
		$this->assertSame( 29.92, $inhg_format( 29.924 ) );
		$this->assertSame( 29.93, $inhg_format( 29.925 ) );
		$this->assertSame( 0.00, $inhg_format( 0.004 ) );
		$this->assertSame( 0.01, $inhg_format( 0.005 ) );
		$this->assertSame( 0.01, $inhg_format( 0.006 ) );

		// mmHg: round(value, 1) - test various decimal scenarios
		$this->assertSame( 760.0, $mmhg_format( 760.0 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.04 ) );
		$this->assertSame( 760.1, $mmhg_format( 760.05 ) );
		$this->assertSame( 760.1, $mmhg_format( 760.06 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.00 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.01 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.02 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.03 ) );
		$this->assertSame( 760.0, $mmhg_format( 760.04 ) );
		$this->assertSame( 760.1, $mmhg_format( 760.05 ) );
		$this->assertSame( 0.0, $mmhg_format( 0.04 ) );
		$this->assertSame( 0.1, $mmhg_format( 0.05 ) );
		$this->assertSame( 0.1, $mmhg_format( 0.06 ) );
		$this->assertSame( -760.0, $mmhg_format( -760.04 ) );
		$this->assertSame( -760.1, $mmhg_format( -760.05 ) );
	}

	/**
	 * @testdox It should format wind speed values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_speed_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_config = $result[ Base_Measurement::TYPE_WIND_SPEED ];

		$mps_format = $wind_config['format'][ Wind_Speed::UNIT_METERS_PER_SECOND ];
		$kmh_format = $wind_config['format'][ Wind_Speed::UNIT_KILOMETERS_PER_HOUR ];
		$knots_format = $wind_config['format'][ Wind_Speed::UNIT_KNOTS ];
		$mph_format = $wind_config['format'][ Wind_Speed::UNIT_MILES_PER_HOUR ];
		$beaufort_format = $wind_config['format'][ Wind_Speed::UNIT_BEAUFORT ];
		$fpm_format = $wind_config['format'][ Wind_Speed::UNIT_FEET_PER_MINUTE ];

		// m/s: round(value, 1) - test various decimal scenarios
		$this->assertSame( 10.0, $mps_format( 10.0 ) );
		$this->assertSame( 10.0, $mps_format( 10.04 ) );
		$this->assertSame( 10.1, $mps_format( 10.05 ) );
		$this->assertSame( 10.1, $mps_format( 10.06 ) );
		$this->assertSame( 10.1, $mps_format( 10.123 ) );
		$this->assertSame( 10.2, $mps_format( 10.156 ) );
		$this->assertSame( 0.0, $mps_format( 0.04 ) );
		$this->assertSame( 0.1, $mps_format( 0.05 ) );
		$this->assertSame( 0.1, $mps_format( 0.06 ) );

		// km/h: (int) value - test various scenarios
		$this->assertSame( 50, $kmh_format( 50.0 ) );
		$this->assertSame( 50, $kmh_format( 50.4 ) );
		$this->assertSame( 50, $kmh_format( 50.5 ) );
		$this->assertSame( 50, $kmh_format( 50.6 ) );
		$this->assertSame( 0, $kmh_format( 0.4 ) );
		$this->assertSame( 0, $kmh_format( 0.5 ) );
		$this->assertSame( 0, $kmh_format( 0.6 ) );

		// knots: round(value, 1) - test various decimal scenarios
		$this->assertSame( 10.0, $knots_format( 10.0 ) );
		$this->assertSame( 10.0, $knots_format( 10.04 ) );
		$this->assertSame( 10.1, $knots_format( 10.05 ) );
		$this->assertSame( 10.1, $knots_format( 10.06 ) );
		$this->assertSame( 10.1, $knots_format( 10.123 ) );
		$this->assertSame( 10.2, $knots_format( 10.156 ) );

		// mph: (int) value - test various scenarios
		$this->assertSame( 25, $mph_format( 25.0 ) );
		$this->assertSame( 25, $mph_format( 25.4 ) );
		$this->assertSame( 25, $mph_format( 25.5 ) );
		$this->assertSame( 25, $mph_format( 25.6 ) );
		$this->assertSame( 0, $mph_format( 0.4 ) );
		$this->assertSame( 0, $mph_format( 0.5 ) );
		$this->assertSame( 0, $mph_format( 0.6 ) );

		// Beaufort: (int) value - test various scenarios
		$this->assertSame( 3, $beaufort_format( 3.0 ) );
		$this->assertSame( 3, $beaufort_format( 3.4 ) );
		$this->assertSame( 3, $beaufort_format( 3.5 ) );
		$this->assertSame( 3, $beaufort_format( 3.6 ) );
		$this->assertSame( 0, $beaufort_format( 0.4 ) );
		$this->assertSame( 0, $beaufort_format( 0.5 ) );
		$this->assertSame( 0, $beaufort_format( 0.6 ) );

		// fpm: (int) value - test various scenarios
		$this->assertSame( 100, $fpm_format( 100.0 ) );
		$this->assertSame( 100, $fpm_format( 100.4 ) );
		$this->assertSame( 100, $fpm_format( 100.5 ) );
		$this->assertSame( 100, $fpm_format( 100.6 ) );
		$this->assertSame( 0, $fpm_format( 0.4 ) );
		$this->assertSame( 0, $fpm_format( 0.5 ) );
		$this->assertSame( 0, $fpm_format( 0.6 ) );
	}

	/**
	 * @testdox It should format rainfall values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_rainfall_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$rainfall_config = $result[ Base_Measurement::TYPE_RAINFALL ];

		$mm_format = $rainfall_config['format'][ Rainfall::UNIT_MILLIMETERS ];
		$inches_format = $rainfall_config['format'][ Rainfall::UNIT_INCHES ];

		// mm: round(value, 1) - test various decimal scenarios
		$this->assertSame( 25.0, $mm_format( 25.0 ) );
		$this->assertSame( 25.0, $mm_format( 25.04 ) );
		$this->assertSame( 25.1, $mm_format( 25.05 ) );
		$this->assertSame( 25.1, $mm_format( 25.06 ) );
		$this->assertSame( 25.1, $mm_format( 25.123 ) );
		$this->assertSame( 25.2, $mm_format( 25.156 ) );
		$this->assertSame( 0.0, $mm_format( 0.04 ) );
		$this->assertSame( 0.1, $mm_format( 0.05 ) );
		$this->assertSame( 0.1, $mm_format( 0.06 ) );
		$this->assertSame( -25.0, $mm_format( -25.04 ) );
		$this->assertSame( -25.1, $mm_format( -25.05 ) );

		// inches: round(value, 2) - test various decimal scenarios
		$this->assertSame( 1.00, $inches_format( 1.00 ) );
		$this->assertSame( 1.00, $inches_format( 1.004 ) );
		$this->assertSame( 1.01, $inches_format( 1.005 ) );
		$this->assertSame( 1.01, $inches_format( 1.006 ) );
		$this->assertSame( 1.00, $inches_format( 1.000 ) );
		$this->assertSame( 1.00, $inches_format( 1.001 ) );
		$this->assertSame( 1.00, $inches_format( 1.002 ) );
		$this->assertSame( 1.00, $inches_format( 1.003 ) );
		$this->assertSame( 1.00, $inches_format( 1.004 ) );
		$this->assertSame( 1.01, $inches_format( 1.005 ) );
		$this->assertSame( 0.00, $inches_format( 0.004 ) );
		$this->assertSame( 0.01, $inches_format( 0.005 ) );
		$this->assertSame( 0.01, $inches_format( 0.006 ) );
		$this->assertSame( -1.00, $inches_format( -1.004 ) );
		$this->assertSame( -1.01, $inches_format( -1.005 ) );
	}

	/**
	 * @testdox It should format solar radiation values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_solar_radiation_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$solar_config = $result[ Base_Measurement::TYPE_SOLAR_RADIATION ];

		$lux_format = $solar_config['format'][ Solar_Radiation::UNIT_LUX ];
		$fc_format = $solar_config['format'][ Solar_Radiation::UNIT_FOOT_CANDLES ];
		$wm2_format = $solar_config['format'][ Solar_Radiation::UNIT_WATTS_PER_SQUARE_METER ];

		// lux: (int) value - test various scenarios
		$this->assertSame( 1000, $lux_format( 1000.0 ) );
		$this->assertSame( 1000, $lux_format( 1000.4 ) );
		$this->assertSame( 1000, $lux_format( 1000.5 ) );
		$this->assertSame( 1000, $lux_format( 1000.6 ) );
		$this->assertSame( 0, $lux_format( 0.4 ) );
		$this->assertSame( 0, $lux_format( 0.5 ) );
		$this->assertSame( 0, $lux_format( 0.6 ) );
		$this->assertSame( 0, $lux_format( -0.6 ) );
		$this->assertSame( 0, $lux_format( -0.5 ) );
		$this->assertSame( 0, $lux_format( -0.4 ) );

		// fc: round(value, 1) - test various decimal scenarios
		$this->assertSame( 100.0, $fc_format( 100.0 ) );
		$this->assertSame( 100.0, $fc_format( 100.04 ) );
		$this->assertSame( 100.1, $fc_format( 100.05 ) );
		$this->assertSame( 100.1, $fc_format( 100.06 ) );
		$this->assertSame( 100.1, $fc_format( 100.123 ) );
		$this->assertSame( 100.2, $fc_format( 100.156 ) );
		$this->assertSame( 0.0, $fc_format( 0.04 ) );
		$this->assertSame( 0.1, $fc_format( 0.05 ) );
		$this->assertSame( 0.1, $fc_format( 0.06 ) );
		$this->assertSame( -100.0, $fc_format( -100.04 ) );
		$this->assertSame( -100.1, $fc_format( -100.05 ) );

		// W/m²: round(value, 1) - test various decimal scenarios
		$this->assertSame( 79.0, $wm2_format( 79.0 ) );
		$this->assertSame( 79.0, $wm2_format( 79.04 ) );
		$this->assertSame( 79.1, $wm2_format( 79.05 ) );
		$this->assertSame( 79.1, $wm2_format( 79.06 ) );
		$this->assertSame( 79.1, $wm2_format( 79.123 ) );
		$this->assertSame( 79.2, $wm2_format( 79.156 ) );
		$this->assertSame( 0.0, $wm2_format( 0.04 ) );
		$this->assertSame( 0.1, $wm2_format( 0.05 ) );
		$this->assertSame( 0.1, $wm2_format( 0.06 ) );
		$this->assertSame( -79.0, $wm2_format( -79.04 ) );
		$this->assertSame( -79.1, $wm2_format( -79.05 ) );
	}

	/**
	 * @testdox It should format volume values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_volume_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$volume_config = $result[ Base_Measurement::TYPE_VOLUME ];

		$liters_format = $volume_config['format'][ Volume::UNIT_LITERS ];
		$m3_format = $volume_config['format'][ Volume::UNIT_CUBIC_METERS ];
		$gal_format = $volume_config['format'][ Volume::UNIT_GALLONS ];

		// L: round(value, 1) - test various decimal scenarios
		$this->assertSame( 100.0, $liters_format( 100.0 ) );
		$this->assertSame( 100.0, $liters_format( 100.04 ) );
		$this->assertSame( 100.1, $liters_format( 100.05 ) );
		$this->assertSame( 100.1, $liters_format( 100.06 ) );
		$this->assertSame( 100.1, $liters_format( 100.123 ) );
		$this->assertSame( 100.2, $liters_format( 100.156 ) );
		$this->assertSame( 0.0, $liters_format( 0.04 ) );
		$this->assertSame( 0.1, $liters_format( 0.05 ) );
		$this->assertSame( 0.1, $liters_format( 0.06 ) );
		$this->assertSame( -100.0, $liters_format( -100.04 ) );
		$this->assertSame( -100.1, $liters_format( -100.05 ) );

		// m³: round(value, 3) - test various decimal scenarios
		$this->assertSame( 1.000, $m3_format( 1.0 ) );
		$this->assertSame( 1.000, $m3_format( 1.0004 ) );
		$this->assertSame( 1.001, $m3_format( 1.0005 ) );
		$this->assertSame( 1.001, $m3_format( 1.0006 ) );
		$this->assertSame( 1.000, $m3_format( 1.0000 ) );
		$this->assertSame( 1.000, $m3_format( 1.0001 ) );
		$this->assertSame( 1.000, $m3_format( 1.0002 ) );
		$this->assertSame( 1.000, $m3_format( 1.0003 ) );
		$this->assertSame( 1.000, $m3_format( 1.0004 ) );
		$this->assertSame( 1.001, $m3_format( 1.0005 ) );
		$this->assertSame( 0.000, $m3_format( 0.0004 ) );
		$this->assertSame( 0.001, $m3_format( 0.0005 ) );
		$this->assertSame( 0.001, $m3_format( 0.0006 ) );
		$this->assertSame( -1.000, $m3_format( -1.0004 ) );
		$this->assertSame( -1.001, $m3_format( -1.0005 ) );

		// gal: round(value, 2) - test various decimal scenarios
		$this->assertSame( 1.00, $gal_format( 1.0 ) );
		$this->assertSame( 1.00, $gal_format( 1.004 ) );
		$this->assertSame( 1.01, $gal_format( 1.005 ) );
		$this->assertSame( 1.01, $gal_format( 1.006 ) );
		$this->assertSame( 1.00, $gal_format( 1.000 ) );
		$this->assertSame( 1.00, $gal_format( 1.001 ) );
		$this->assertSame( 1.00, $gal_format( 1.002 ) );
		$this->assertSame( 1.00, $gal_format( 1.003 ) );
		$this->assertSame( 1.00, $gal_format( 1.004 ) );
		$this->assertSame( 1.01, $gal_format( 1.005 ) );
		$this->assertSame( 0.00, $gal_format( 0.004 ) );
		$this->assertSame( 0.01, $gal_format( 0.005 ) );
		$this->assertSame( 0.01, $gal_format( 0.006 ) );
		$this->assertSame( -1.00, $gal_format( -1.004 ) );
		$this->assertSame( -1.01, $gal_format( -1.005 ) );
	}

	/**
	 * @testdox It should format wind direction values with comprehensive edge cases
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Conversion_Config::get
	 */
	public function test_wind_direction_formatting_comprehensive(): void {
		$config = new Conversion_Config();
		$result = $config->get();
		$wind_dir_config = $result[ Base_Measurement::TYPE_WIND_DIRECTION ];

		$degrees_format = $wind_dir_config['format']['deg'];
		$compass_format = $wind_dir_config['format']['cardinal'];

		// degrees: (int) value - test various scenarios
		$this->assertSame( 0, $degrees_format( 0.0 ) );
		$this->assertSame( 0, $degrees_format( 0.4 ) );
		$this->assertSame( 0, $degrees_format( 0.5 ) );
		$this->assertSame( 0, $degrees_format( 0.6 ) );
		$this->assertSame( 90, $degrees_format( 90.0 ) );
		$this->assertSame( 90, $degrees_format( 90.4 ) );
		$this->assertSame( 90, $degrees_format( 90.5 ) );
		$this->assertSame( 90, $degrees_format( 90.6 ) );
		$this->assertSame( 180, $degrees_format( 180.0 ) );
		$this->assertSame( 180, $degrees_format( 180.4 ) );
		$this->assertSame( 180, $degrees_format( 180.5 ) );
		$this->assertSame( 180, $degrees_format( 180.6 ) );
		$this->assertSame( 270, $degrees_format( 270.0 ) );
		$this->assertSame( 270, $degrees_format( 270.4 ) );
		$this->assertSame( 270, $degrees_format( 270.5 ) );
		$this->assertSame( 270, $degrees_format( 270.6 ) );
		$this->assertSame( 359, $degrees_format( 359.4 ) );
		$this->assertSame( 359, $degrees_format( 359.5 ) );
		$this->assertSame( 359, $degrees_format( 359.6 ) );
		$this->assertSame( 0, $degrees_format( -0.6 ) );
		$this->assertSame( 0, $degrees_format( -0.5 ) );
		$this->assertSame( 0, $degrees_format( -0.4 ) );
		$this->assertSame( -1, $degrees_format( -1.0 ) );
		$this->assertSame( -1, $degrees_format( -1.1 ) );

		// compass: value (no change) - test various scenarios
		$this->assertSame( 'N', $compass_format( 'N' ) );
		$this->assertSame( 'NNE', $compass_format( 'NNE' ) );
		$this->assertSame( 'NE', $compass_format( 'NE' ) );
		$this->assertSame( 'ENE', $compass_format( 'ENE' ) );
		$this->assertSame( 'E', $compass_format( 'E' ) );
		$this->assertSame( 'ESE', $compass_format( 'ESE' ) );
		$this->assertSame( 'SE', $compass_format( 'SE' ) );
		$this->assertSame( 'SSE', $compass_format( 'SSE' ) );
		$this->assertSame( 'S', $compass_format( 'S' ) );
		$this->assertSame( 'SSW', $compass_format( 'SSW' ) );
		$this->assertSame( 'SW', $compass_format( 'SW' ) );
		$this->assertSame( 'WSW', $compass_format( 'WSW' ) );
		$this->assertSame( 'W', $compass_format( 'W' ) );
		$this->assertSame( 'WNW', $compass_format( 'WNW' ) );
		$this->assertSame( 'NW', $compass_format( 'NW' ) );
		$this->assertSame( 'NNW', $compass_format( 'NNW' ) );
		$this->assertSame( '', $compass_format( '' ) );
		$this->assertSame( 'INVALID', $compass_format( 'INVALID' ) );
	}
}
