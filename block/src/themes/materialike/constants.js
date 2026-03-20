/**
 * Materialike theme constants.
 *
 * Group metadata and field-to-icon-type mappings.
 */
import { __ } from '@wordpress/i18n';

/**
 * Group metadata — icon type keys and display labels.
 */
export const GROUP_META = {
	outdoor:          { iconType: 'outdoor_group',          label: __( 'Outdoor',           'pinkcrab-weather-block' ) },
	indoor:           { iconType: 'indoor_group',           label: __( 'Indoor',            'pinkcrab-weather-block' ) },
	wind:             { iconType: 'wind_group',             label: __( 'Wind',              'pinkcrab-weather-block' ) },
	pressure:         { iconType: 'pressure_group',         label: __( 'Pressure',          'pinkcrab-weather-block' ) },
	rainfall:         { iconType: 'rainfall_group',         label: __( 'Rainfall',          'pinkcrab-weather-block' ) },
	rainfall_piezo:   { iconType: 'rainfall_group',         label: __( 'Rainfall (Piezo)',  'pinkcrab-weather-block' ) },
	solar_and_uvi:    { iconType: 'solar_radiation_group',  label: __( 'Solar & UV',        'pinkcrab-weather-block' ) },
	lightning:        { iconType: 'lightning_group',         label: __( 'Lightning',         'pinkcrab-weather-block' ) },
	indoor_co2:       { iconType: 'co2_group',              label: __( 'Indoor CO₂',        'pinkcrab-weather-block' ) },
	co2_aqi_combo:    { iconType: 'co2_group',              label: __( 'CO₂ AQI',           'pinkcrab-weather-block' ) },
	pm25_aqi_combo:   { iconType: 'air_quality_group',      label: __( 'PM2.5 AQI',         'pinkcrab-weather-block' ) },
	pm10_aqi_combo:   { iconType: 'air_quality_group',      label: __( 'PM10 AQI',          'pinkcrab-weather-block' ) },
	pm1_aqi_combo:    { iconType: 'air_quality_group',      label: __( 'PM1 AQI',           'pinkcrab-weather-block' ) },
	pm4_aqi_combo:    { iconType: 'air_quality_group',      label: __( 'PM4 AQI',           'pinkcrab-weather-block' ) },
	t_rh_aqi_combo:   { iconType: 'temperature_group',      label: __( 'T/RH AQI',          'pinkcrab-weather-block' ) },
	water_leak:       { iconType: 'water_leak_group',       label: __( 'Water Leak',        'pinkcrab-weather-block' ) },
	battery:          { iconType: 'battery_group',           label: __( 'Battery',           'pinkcrab-weather-block' ) },
	temp_and_humidity_channels: { iconType: 'temperature_group', label: __( 'Temp & Humidity Channels', 'pinkcrab-weather-block' ) },
	temp_channels:    { iconType: 'temperature_group',      label: __( 'Temp Channels',     'pinkcrab-weather-block' ) },
	soil_channels:    { iconType: 'soil_moisture_group',    label: __( 'Soil Channels',     'pinkcrab-weather-block' ) },
	leaf_channels:    { iconType: 'leaf_wetness_group',     label: __( 'Leaf Channels',     'pinkcrab-weather-block' ) },
	pm25_channels:    { iconType: 'air_quality_group',      label: __( 'PM2.5 Channels',    'pinkcrab-weather-block' ) },
	lds_channels:     { iconType: 'water_leak_group',       label: __( 'LDS Channels',      'pinkcrab-weather-block' ) },
};

/**
 * Maps field keys to icon type keys.
 *
 * If a field key isn't found here, falls back to the group's iconType.
 */
export const FIELD_ICON_MAP = {
	temperature:      'temperature',
	feels_like:       'temperature',
	dew_point:        'temperature',
	humidity:         'humidity',
	wind_speed:       'wind_speed',
	wind_gust:        'wind_speed',
	wind_direction:   'wind_direction',
	pressure_relative: 'pressure',
	pressure_absolute: 'pressure',
	rain_rate:        'rain_rate',
	rain_event:       'rainfall',
	rain_hourly:      'rainfall',
	rain_daily:       'rainfall',
	rain_weekly:      'rainfall',
	rain_monthly:     'rainfall',
	rain_yearly:      'rainfall',
	rain_total:       'rainfall',
	solar_radiation:  'solar_radiation',
	solar:            'solar_radiation',
	uv_index:         'uv_index',
	uvi:              'uv_index',
	lightning_count:  'lightning',
	lightning_distance: 'lightning',
	lightning_time:   'lightning',
	co2:              'co2',
	co2_24h:          'co2',
	aqi:              'air_quality',
	pm25:             'air_quality',
	pm25_24h:         'air_quality',
	pm10:             'air_quality',
	pm10_24h:         'air_quality',
	battery:          'battery',
	leak:             'water_leak',
	leaf_wetness:     'leaf_wetness',
	soil_moisture:    'soil_moisture',
};
