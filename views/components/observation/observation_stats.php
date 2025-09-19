<?php

/**
 * Component: Observation Stats
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Indoor|null $indoor Indoor component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Outdoor|null $outdoor Outdoor component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Solar_And_Uvi|null $solar_and_uvi Solar and UV component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Rainfall|null $rainfall Rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Rainfall_Piezo|null $rainfall_piezo Piezo rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Wind|null $wind Wind component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Pressure|null $pressure Pressure component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Lightning|null $lightning Lightning component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Indoor_CO2|null $indoor_co2 Indoor CO2 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\CO2_AQI_Combo|null $co2_aqi_combo CO2 AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM25_AQI_Combo|null $pm25_aqi_combo PM2.5 AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM10_AQI_Combo|null $pm10_aqi_combo PM10 AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM1_AQI_Combo|null $pm1_aqi_combo PM1 AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM4_AQI_Combo|null $pm4_aqi_combo PM4 AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\T_RH_AQI_Combo|null $t_rh_aqi_combo Temperature and Humidity AQI Combo component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Water_Leak|null $water_leak Water Leak component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Battery|null $battery Battery component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Soil_Channels|null $soil_channels Soil Channels component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Temp_And_Humidity_Channels|null $temp_and_humidity_channels Temperature and Humidity Channels component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Temp_Channels|null $temp_channels Temperature Channels component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Leaf_Channels|null $leaf_channels Leaf Channels component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\PM25_Channels|null $pm25_channels PM2.5 Channels component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\LDS_Channels|null $lds_channels LDS Channels component instance
 */

?>

<?php if ( $indoor ) : ?>
	<?php $this->component( $indoor ); ?>
<?php endif; ?>

<?php if ( $outdoor ) : ?>
	<?php $this->component( $outdoor ); ?>
<?php endif; ?>

<?php if ( $solar_and_uvi ) : ?>
	<?php $this->component( $solar_and_uvi ); ?>
<?php endif; ?>

<?php if ( $rainfall ) : ?>
	<?php $this->component( $rainfall ); ?>
<?php endif; ?>

<?php if ( $rainfall_piezo ) : ?>
	<?php $this->component( $rainfall_piezo ); ?>
<?php endif; ?>

<?php if ( $wind ) : ?>
	<?php $this->component( $wind ); ?>
<?php endif; ?>

<?php if ( $pressure ) : ?>
	<?php $this->component( $pressure ); ?>
<?php endif; ?>

<?php if ( $lightning ) : ?>
	<?php $this->component( $lightning ); ?>
<?php endif; ?>

<?php if ( $indoor_co2 ) : ?>
	<?php $this->component( $indoor_co2 ); ?>
<?php endif; ?>

<?php if ( $co2_aqi_combo ) : ?>
	<?php $this->component( $co2_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $pm25_aqi_combo ) : ?>
	<?php $this->component( $pm25_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $pm10_aqi_combo ) : ?>
	<?php $this->component( $pm10_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $pm1_aqi_combo ) : ?>
	<?php $this->component( $pm1_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $pm4_aqi_combo ) : ?>
	<?php $this->component( $pm4_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $t_rh_aqi_combo ) : ?>
	<?php $this->component( $t_rh_aqi_combo ); ?>
<?php endif; ?>

<?php if ( $water_leak ) : ?>
	<?php $this->component( $water_leak ); ?>
<?php endif; ?>

<?php if ( $soil_channels ) : ?>
	<?php $this->component( $soil_channels ); ?>
<?php endif; ?>

<?php if ( $temp_and_humidity_channels ) : ?>
	<?php $this->component( $temp_and_humidity_channels ); ?>
<?php endif; ?>

<?php if ( $temp_channels ) : ?>
	<?php $this->component( $temp_channels ); ?>
<?php endif; ?>

<?php if ( $leaf_channels ) : ?>
	<?php $this->component( $leaf_channels ); ?>
<?php endif; ?>

<?php if ( $pm25_channels ) : ?>
	<?php $this->component( $pm25_channels ); ?>
<?php endif; ?>

<?php if ( $lds_channels ) : ?>
	<?php $this->component( $lds_channels ); ?>
<?php endif; ?>

<?php if ( $battery ) : ?>
	<?php $this->component( $battery ); ?>
<?php endif; ?>

<?php if ( ! $indoor && ! $outdoor && ! $solar_and_uvi && ! $rainfall && ! $rainfall_piezo && ! $wind && ! $pressure && ! $lightning && ! $indoor_co2 && ! $co2_aqi_combo && ! $pm25_aqi_combo && ! $pm10_aqi_combo && ! $pm1_aqi_combo && ! $pm4_aqi_combo && ! $t_rh_aqi_combo && ! $water_leak && ! $soil_channels && ! $temp_and_humidity_channels && ! $temp_channels && ! $leaf_channels && ! $pm25_channels && ! $lds_channels && ! $battery ) : ?>
	<div class="observation-placeholder">
		<p><?php esc_html_e( 'No measurement data available.', 'pinkcrab-weather-block' ); ?></p>
	</div>
<?php endif; ?>
