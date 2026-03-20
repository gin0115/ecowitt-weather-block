/**
 * Meteocons icon set — beautiful animated weather SVGs by Bas Milius.
 * MIT Licensed: https://github.com/basmilius/weather-icons
 *
 * Icons are loaded as image URLs and rendered via <img> tags
 * to preserve their animations, gradients, and colours.
 */

import thermometerSvg from './meteocons/thermometer.svg';
import partlyCloudyDaySvg from './meteocons/partly-cloudy-day.svg';
import thermometerGlassSvg from './meteocons/thermometer-glass.svg';
import humiditySvg from './meteocons/humidity.svg';
import windSvg from './meteocons/wind.svg';
import compassSvg from './meteocons/compass.svg';
import barometerSvg from './meteocons/barometer.svg';
import pressureGroupSvg from './meteocons/pressure.svg';
import raindropsSvg from './meteocons/raindrops.svg';
import raindropMeasureSvg from './meteocons/raindrop-measure.svg';
import sunHotSvg from './meteocons/sun-hot.svg';
import uvIndex1Svg from './meteocons/uv-index-1.svg';
import uvIndex2Svg from './meteocons/uv-index-2.svg';
import uvIndex3Svg from './meteocons/uv-index-3.svg';
import uvIndex4Svg from './meteocons/uv-index-4.svg';
import uvIndex5Svg from './meteocons/uv-index-5.svg';
import uvIndex6Svg from './meteocons/uv-index-6.svg';
import uvIndex7Svg from './meteocons/uv-index-7.svg';
import uvIndex8Svg from './meteocons/uv-index-8.svg';
import uvIndex9Svg from './meteocons/uv-index-9.svg';
import uvIndex10Svg from './meteocons/uv-index-10.svg';
import uvIndex11Svg from './meteocons/uv-index-11.svg';
import clearDaySvg from './meteocons/clear-day.svg';
import thunderstormsSvg from './meteocons/thunderstorms.svg';
import lightningBoltSvg from './meteocons/lightning-bolt.svg';
import smokeParticlesSvg from './meteocons/smoke-particles.svg';
import raindropSvg from './meteocons/raindrop.svg';
import notAvailableSvg from './meteocons/not-available.svg';
import windsockSvg from './meteocons/windsock.svg';
import pollenSvg from './meteocons/pollen.svg';

/**
 * Map measurement type keys to SVG URLs.
 */
const ICON_MAP = {
	temperature: thermometerSvg,
	outdoor: partlyCloudyDaySvg,
	indoor: thermometerGlassSvg,
	humidity: humiditySvg,
	wind_speed: windSvg,
	wind_direction: compassSvg,
	pressure: barometerSvg,
	pressure_group: pressureGroupSvg,
	rainfall: raindropsSvg,
	rain_rate: raindropMeasureSvg,
	solar_radiation: sunHotSvg,
	uv_index: clearDaySvg,
	uv_index_1: uvIndex1Svg,
	uv_index_2: uvIndex2Svg,
	uv_index_3: uvIndex3Svg,
	uv_index_4: uvIndex4Svg,
	uv_index_5: uvIndex5Svg,
	uv_index_6: uvIndex6Svg,
	uv_index_7: uvIndex7Svg,
	uv_index_8: uvIndex8Svg,
	uv_index_9: uvIndex9Svg,
	uv_index_10: uvIndex10Svg,
	uv_index_11: uvIndex11Svg,
	clear_day: clearDaySvg,
	lightning: thunderstormsSvg,
	co2: smokeParticlesSvg,
	air_quality: smokeParticlesSvg,
	battery: notAvailableSvg,
	water_leak: raindropSvg,
	leaf_wetness: pollenSvg,
	soil_moisture: raindropSvg,
	default: notAvailableSvg,

	/* Group header icons — used only for card/section headers */
	outdoor_group: partlyCloudyDaySvg,
	indoor_group: thermometerGlassSvg,
	wind_group: windsockSvg,
	rainfall_group: raindropsSvg,
	solar_radiation_group: sunHotSvg,
	lightning_group: thunderstormsSvg,
	co2_group: smokeParticlesSvg,
	air_quality_group: smokeParticlesSvg,
	temperature_group: thermometerSvg,
	water_leak_group: raindropSvg,
	battery_group: notAvailableSvg,
	soil_moisture_group: raindropSvg,
	leaf_wetness_group: pollenSvg,
};

/**
 * Create an img-based icon component for a given type.
 */
function createIconComponent( src, altText ) {
	return function MeteoconIcon( { className } = {} ) {
		return (
			<img
				src={ src }
				alt={ altText }
				className={ className }
				loading="lazy"
				draggable="false"
			/>
		);
	};
}

/**
 * Build the meteocons set — each key returns a React component
 * that renders an <img> tag with the SVG URL.
 */
const meteocons = {};

Object.entries( ICON_MAP ).forEach( ( [ key, src ] ) => {
	meteocons[ key ] = createIconComponent( src, key.replace( /_/g, ' ' ) );
} );

export default meteocons;
