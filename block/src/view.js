/**
 * Frontend renderer for Ecowitt Weather block (live + history).
 *
 * Finds all .ecowitt-weather-live and .ecowitt-weather-history containers,
 * reads their data attributes, and renders the appropriate display.
 */
import { render, createElement } from '@wordpress/element';
import { getTheme } from './themes/registry';
import HistoryApp from './history/HistoryApp';

const API_BASE = '/wp-json/ecowitt-weather/v1';

/**
 * Fetch live weather data from the REST endpoint.
 *
 * @param {string} connectionKey The connection key.
 * @param {string} mac           The device MAC address.
 * @return {Promise<Object|null>} The observation data or null.
 */
async function fetchLiveData( connectionKey, mac ) {
	const url = `${ API_BASE }/live?connection=${ encodeURIComponent( connectionKey ) }&mac=${ encodeURIComponent( mac ) }`;

	const response = await fetch( url );

	if ( ! response.ok ) {
		throw new Error( `HTTP ${ response.status }` );
	}

	const json = await response.json();
	return json.observation || null;
}

/**
 * Parse common data attributes from a block container.
 *
 * @param {HTMLElement} container The block container element.
 * @return {Object} Parsed attributes.
 */
function parseCommonAttributes( container ) {
	let selectedFields = {};
	try {
		selectedFields = JSON.parse( container.dataset.selectedFields || '{}' );
	} catch ( e ) {
		// Invalid JSON — use empty.
	}

	let colors = {};
	try {
		colors = JSON.parse( container.dataset.colors || '{}' );
	} catch ( e ) {
		// Invalid JSON — use empty.
	}

	return {
		connectionKey: container.dataset.connection || '',
		mac: container.dataset.mac || '',
		stationName: container.dataset.stationName || '',
		themeName: container.dataset.theme || 'default',
		iconSet: container.dataset.iconSet || 'outline',
		selectedFields,
		colors,
	};
}

/**
 * Initialise a single live weather block container.
 *
 * @param {HTMLElement} container The block container element.
 */
async function initLiveBlock( container ) {
	const { connectionKey, mac, stationName, themeName, iconSet, selectedFields, colors } =
		parseCommonAttributes( container );

	if ( ! connectionKey || ! mac ) {
		return;
	}

	const ThemeComponent = getTheme( themeName );

	// Show loading state.
	render(
		createElement( ThemeComponent, {
			data: null,
			selectedFields,
			stationName,
			colors,
			iconSet,
			isLoading: true,
			error: null,
		} ),
		container
	);

	try {
		const data = await fetchLiveData( connectionKey, mac );

		render(
			createElement( ThemeComponent, {
				data,
				selectedFields,
				stationName,
				colors,
				iconSet,
				isLoading: false,
				error: null,
			} ),
			container
		);
	} catch ( err ) {
		render(
			createElement( ThemeComponent, {
				data: null,
				selectedFields,
				stationName,
				colors,
				iconSet,
				isLoading: false,
				error: err.message || 'Failed to load weather data.',
			} ),
			container
		);
	}
}

/**
 * Initialise a single history weather block container.
 *
 * @param {HTMLElement} container The block container element.
 */
function initHistoryBlock( container ) {
	const { connectionKey, mac, stationName, themeName, iconSet, selectedFields, colors } =
		parseCommonAttributes( container );

	if ( ! connectionKey || ! mac ) {
		return;
	}

	const defaultRange = container.dataset.defaultRange || '24h';
	const cycleType = container.dataset.cycleType || 'auto';
	const autoRefreshInterval = parseInt( container.dataset.autoRefreshInterval || '300', 10 );

	render(
		createElement( HistoryApp, {
			connectionKey,
			mac,
			stationName,
			selectedFields,
			themeName,
			colors,
			iconSet,
			defaultRange,
			cycleType,
			autoRefreshInterval,
		} ),
		container
	);
}

/**
 * Initialise all weather blocks on the page.
 */
function init() {
	const liveContainers = document.querySelectorAll( '.ecowitt-weather-live' );
	liveContainers.forEach( initLiveBlock );

	const historyContainers = document.querySelectorAll( '.ecowitt-weather-history' );
	historyContainers.forEach( initHistoryBlock );
}

// Run on DOM ready.
if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', init );
} else {
	init();
}
