<?php

/**
 * The observation stats component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Observation;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Indoor;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Outdoor;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Solar_And_Uvi;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Rainfall;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Rainfall_Piezo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Wind;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Pressure;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Lightning;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Indoor_CO2;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\CO2_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM25_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM10_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM1_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM4_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\T_RH_AQI_Combo;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Water_Leak;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Battery;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Soil_Channels;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Temp_And_Humidity_Channels;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Temp_Channels;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Leaf_Channels;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM25_Channels;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\LDS_Channels;

/**
 * The observation stats component.
 *
 * @view observation.observation_stats
 */
class Observation_Stats extends Component {

	/**
	 * Indoor component.
	 *
	 * @var Indoor|null
	 */
	public $indoor = null;

	/**
	 * Outdoor component.
	 *
	 * @var Outdoor|null
	 */
	public $outdoor = null;

	/**
	 * Solar and UV component.
	 *
	 * @var Solar_And_Uvi|null
	 */
	public $solar_and_uvi = null;

	/**
	 * Rainfall component.
	 *
	 * @var Rainfall|null
	 */
	public $rainfall = null;

	/**
	 * Piezo rainfall component.
	 *
	 * @var Rainfall_Piezo|null
	 */
	public $rainfall_piezo = null;

	/**
	 * Wind component.
	 *
	 * @var Wind|null
	 */
	public $wind = null;

	/**
	 * Pressure component.
	 *
	 * @var Pressure|null
	 */
	public $pressure = null;

	/**
	 * Lightning component.
	 *
	 * @var Lightning|null
	 */
	public $lightning = null;

	/**
	 * Indoor CO2 component.
	 *
	 * @var Indoor_CO2|null
	 */
	public $indoor_co2 = null;

	/**
	 * CO2 AQI Combo component.
	 *
	 * @var CO2_AQI_Combo|null
	 */
	public $co2_aqi_combo = null;

	/**
	 * PM2.5 AQI Combo component.
	 *
	 * @var PM25_AQI_Combo|null
	 */
	public $pm25_aqi_combo = null;

	/**
	 * PM10 AQI Combo component.
	 *
	 * @var PM10_AQI_Combo|null
	 */
	public $pm10_aqi_combo = null;

	/**
	 * PM1 AQI Combo component.
	 *
	 * @var PM1_AQI_Combo|null
	 */
	public $pm1_aqi_combo = null;

	/**
	 * PM4 AQI Combo component.
	 *
	 * @var PM4_AQI_Combo|null
	 */
	public $pm4_aqi_combo = null;

	/**
	 * Temperature and Humidity AQI Combo component.
	 *
	 * @var T_RH_AQI_Combo|null
	 */
	public $t_rh_aqi_combo = null;

	/**
	 * Water Leak component.
	 *
	 * @var Water_Leak|null
	 */
	public $water_leak = null;

	/**
	 * Battery component.
	 *
	 * @var Battery|null
	 */
	public $battery = null;

	/**
	 * Soil Channels component.
	 *
	 * @var Soil_Channels|null
	 */
	public $soil_channels = null;

	/**
	 * Temperature and Humidity Channels component.
	 *
	 * @var Temp_And_Humidity_Channels|null
	 */
	public $temp_and_humidity_channels = null;

	/**
	 * Temperature Channels component.
	 *
	 * @var Temp_Channels|null
	 */
	public $temp_channels = null;

	/**
	 * Leaf Channels component.
	 *
	 * @var Leaf_Channels|null
	 */
	public $leaf_channels = null;

	/**
	 * PM2.5 Channels component.
	 *
	 * @var PM25_Channels|null
	 */
	public $pm25_channels = null;

	/**
	 * LDS Channels component.
	 *
	 * @var LDS_Channels|null
	 */
	public $lds_channels = null;

	/**
	 * Creates an instance of the Observation_Stats Component.
	 *
	 * @param Observation $observation
	 */
	public function __construct( Observation $observation ) {
		if ( ! empty( $observation->indoor ) ) {
			$this->indoor = new Indoor( $observation->indoor );
		}
		if ( ! empty( $observation->outdoor ) ) {
			$this->outdoor = new Outdoor( $observation->outdoor );
		}
		if ( ! empty( $observation->solar_and_uvi ) ) {
			$this->solar_and_uvi = new Solar_And_Uvi( $observation->solar_and_uvi );
		}
		if ( ! empty( $observation->rainfall ) ) {
			$this->rainfall = new Rainfall( $observation->rainfall );
		}
		if ( ! empty( $observation->rainfall_piezo ) ) {
			$this->rainfall_piezo = new Rainfall_Piezo( $observation->rainfall_piezo );
		}
		if ( ! empty( $observation->wind ) ) {
			$this->wind = new Wind( $observation->wind );
		}
		if ( ! empty( $observation->pressure ) ) {
			$this->pressure = new Pressure( $observation->pressure );
		}
		if ( ! empty( $observation->lightning ) ) {
			$this->lightning = new Lightning( $observation->lightning );
		}
		if ( ! empty( $observation->indoor_co2 ) ) {
			$this->indoor_co2 = new Indoor_CO2( $observation->indoor_co2 );
		}
		if ( ! empty( $observation->co2_aqi_combo ) ) {
			$this->co2_aqi_combo = new CO2_AQI_Combo( $observation->co2_aqi_combo );
		}
		if ( ! empty( $observation->pm25_aqi_combo ) ) {
			$this->pm25_aqi_combo = new PM25_AQI_Combo( $observation->pm25_aqi_combo );
		}
		if ( ! empty( $observation->pm10_aqi_combo ) ) {
			$this->pm10_aqi_combo = new PM10_AQI_Combo( $observation->pm10_aqi_combo );
		}
		if ( ! empty( $observation->pm1_aqi_combo ) ) {
			$this->pm1_aqi_combo = new PM1_AQI_Combo( $observation->pm1_aqi_combo );
		}
		if ( ! empty( $observation->pm4_aqi_combo ) ) {
			$this->pm4_aqi_combo = new PM4_AQI_Combo( $observation->pm4_aqi_combo );
		}
		if ( ! empty( $observation->t_rh_aqi_combo ) ) {
			$this->t_rh_aqi_combo = new T_RH_AQI_Combo( $observation->t_rh_aqi_combo );
		}
		if ( ! empty( $observation->water_leak ) ) {
			$this->water_leak = new Water_Leak( $observation->water_leak );
		}
		if ( ! empty( $observation->battery ) ) {
			$this->battery = new Battery( $observation->battery );
		}
		if ( ! empty( $observation->soil_channels ) ) {
			$this->soil_channels = new Soil_Channels( $observation->soil_channels );
		}
		if ( ! empty( $observation->temp_and_humidity_channels ) ) {
			$this->temp_and_humidity_channels = new Temp_And_Humidity_Channels( $observation->temp_and_humidity_channels );
		}
		if ( ! empty( $observation->temp_channels ) ) {
			$this->temp_channels = new Temp_Channels( $observation->temp_channels );
		}
		if ( ! empty( $observation->leaf_channels ) ) {
			$this->leaf_channels = new Leaf_Channels( $observation->leaf_channels );
		}
		if ( ! empty( $observation->pm25_channels ) ) {
			$this->pm25_channels = new PM25_Channels( $observation->pm25_channels );
		}
		if ( ! empty( $observation->lds_channels ) ) {
			$this->lds_channels = new LDS_Channels( $observation->lds_channels );
		}
	}
}
