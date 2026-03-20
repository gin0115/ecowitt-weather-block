/**
 * Chart utilities for history themes.
 *
 * Transforms API response data into Recharts-compatible format,
 * groups fields by unit for shared charts, and provides formatting helpers.
 */

/**
 * Color palette for chart lines.
 */
export const SERIES_COLORS = [
	'#3b82f6', // blue
	'#ef4444', // red
	'#10b981', // emerald
	'#f59e0b', // amber
	'#8b5cf6', // violet
	'#ec4899', // pink
	'#06b6d4', // cyan
	'#84cc16', // lime
];

/**
 * Format a field key into a human-readable label.
 *
 * @param {string} key Field key (e.g. 'rain_daily').
 * @return {string} Formatted label (e.g. 'Rain Daily').
 */
export function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * Format a Unix timestamp for display.
 *
 * @param {number} ts   Unix timestamp in seconds.
 * @param {string} range Range preset (e.g. '24h', '7d', '30d', '90d').
 * @param {boolean} full Whether to return full date/time (for tooltips).
 * @return {string} Formatted date/time string.
 */
/**
 * Check if range spans more than ~90 days (needs year display).
 *
 * @param {string} range Range preset.
 * @return {boolean} True if year should be shown.
 */
function isLongRange( range ) {
	return [ '1y', '2y', 'custom' ].includes( range );
}

export function formatTimestamp( ts, range = '24h', full = false ) {
	const date = new Date( ts * 1000 );
	const showYear = isLongRange( range );

	if ( full ) {
		return date.toLocaleString( undefined, {
			year: 'numeric',
			month: 'short',
			day: 'numeric',
			hour: '2-digit',
			minute: '2-digit',
		} );
	}

	if ( range === '24h' ) {
		return date.toLocaleTimeString( undefined, {
			hour: '2-digit',
			minute: '2-digit',
		} );
	}

	if ( range === '7d' ) {
		return date.toLocaleDateString( undefined, {
			weekday: 'short',
			hour: '2-digit',
		} );
	}

	if ( showYear ) {
		const mon = date.toLocaleDateString( undefined, { month: 'short' } );
		const yr = String( date.getFullYear() ).slice( -2 );
		return `${ mon } '${ yr }`;
	}

	return date.toLocaleDateString( undefined, {
		month: 'short',
		day: 'numeric',
	} );
}

/**
 * Compute ISO date strings from a range preset.
 *
 * @param {string} preset     Range preset (e.g. '24h', '7d', '30d', '90d').
 * @param {string} customFrom Custom start date (ISO string, used when preset is 'custom').
 * @param {string} customTo   Custom end date (ISO string, used when preset is 'custom').
 * @return {{ from: string, to: string }} ISO date strings.
 */
export function computeDateRange( preset, customFrom = '', customTo = '' ) {
	if ( preset === 'custom' ) {
		return {
			from: customFrom || new Date( Date.now() - 86400000 ).toISOString(),
			to: customTo || new Date().toISOString(),
		};
	}

	const now = new Date();
	const to = now.toISOString();

	const presetMs = {
		'24h': 24 * 60 * 60 * 1000,
		'7d': 7 * 24 * 60 * 60 * 1000,
		'30d': 30 * 24 * 60 * 60 * 1000,
		'90d': 90 * 24 * 60 * 60 * 1000,
		'1y': 365 * 24 * 60 * 60 * 1000,
		'2y': 2 * 365 * 24 * 60 * 60 * 1000,
	};

	const ms = presetMs[ preset ] || presetMs[ '24h' ];
	const from = new Date( now.getTime() - ms ).toISOString();

	return { from, to };
}

/**
 * Build Recharts-compatible chart data from the API response.
 *
 * Groups fields by observation group and sub-groups by unit,
 * so fields sharing the same unit appear on the same chart.
 *
 * @param {Object} data           API observation response.
 * @param {Object} selectedFields Selected fields config ({ groupKey: { fieldKey: unit, ... } }).
 * @return {Array<Object>} Array of chart descriptor objects.
 *
 * Each chart descriptor:
 * {
 *   groupKey:   'outdoor',
 *   unit:       '°F',
 *   unitKey:    'F',
 *   series:     [{ key: 'temperature', label: 'Temperature', color: '#3b82f6' }],
 *   dataPoints: [{ timestamp: 1772323200, temperature: 38.6, humidity: 65, ... }, ...]
 * }
 */
export function buildChartData( data, selectedFields ) {
	if ( ! data || ! selectedFields ) {
		return [];
	}

	const charts = [];

	Object.entries( selectedFields ).forEach( ( [ groupKey, fieldConfig ] ) => {
		if ( ! data[ groupKey ] || typeof fieldConfig !== 'object' ) {
			return;
		}

		// Group selected fields by their unit for shared charts.
		const byUnit = {};

		Object.keys( fieldConfig ).forEach( ( fieldKey ) => {
			const fieldData = data[ groupKey ][ fieldKey ];
			if ( ! fieldData ) {
				return;
			}

			// History API returns { type, variants: [{ unit, label, data }] }.
			const variants = fieldData.variants;
			if ( ! variants || variants.length === 0 ) {
				return;
			}

			// Use preferred unit from selectedFields, fall back to first variant.
			const preferredUnit = fieldConfig[ fieldKey ];
			let variant = variants[ 0 ];
			if ( preferredUnit ) {
				const preferred = variants.find( ( v ) => v.unit === preferredUnit );
				if ( preferred ) {
					variant = preferred;
				}
			}

			if ( ! variant.data || variant.data.length === 0 ) {
				return;
			}

			const unitKey = variant.unit || 'unknown';
			if ( ! byUnit[ unitKey ] ) {
				byUnit[ unitKey ] = {
					label: variant.label || unitKey,
					fields: [],
					availableVariants: variants,
				};
			}
			byUnit[ unitKey ].fields.push( {
				key: fieldKey,
				data: variant.data,
				type: fieldData.type,
				variants,
			} );
		} );

		// Create a chart for each unit group.
		let colorIndex = 0;

		Object.entries( byUnit ).forEach( ( [ unitKey, unitGroup ] ) => {
			// Merge all timestamp data into shared data points.
			const timestampMap = {};

			unitGroup.fields.forEach( ( field ) => {
				field.data.forEach( ( point ) => {
					if ( ! timestampMap[ point.timestamp ] ) {
						timestampMap[ point.timestamp ] = { timestamp: point.timestamp };
					}
					timestampMap[ point.timestamp ][ field.key ] = parseFloat( point.value );
				} );
			} );

			const dataPoints = Object.values( timestampMap ).sort(
				( a, b ) => a.timestamp - b.timestamp
			);

			// Determine chart type — wind direction uses scatter.
			const isScatter = unitGroup.fields.some( ( f ) => f.type === 'wind_direction' );

			const series = unitGroup.fields.map( ( field ) => ( {
				key: field.key,
				label: formatLabel( field.key ),
				color: SERIES_COLORS[ colorIndex++ % SERIES_COLORS.length ],
			} ) );

			// Build a descriptive title from series names when a group has multiple charts.
			const seriesTitle = series.map( ( s ) => s.label ).join( ', ' );

			charts.push( {
				groupKey,
				unit: unitGroup.label,
				unitKey,
				series,
				dataPoints,
				seriesTitle,
				chartType: isScatter ? 'scatter' : 'line',
				availableVariants: unitGroup.availableVariants || [],
				fieldRefs: unitGroup.fields.map( ( f ) => ( {
					key: f.key,
					variants: f.variants,
					type: f.type,
				} ) ),
			} );
		} );
	} );

	return charts;
}
