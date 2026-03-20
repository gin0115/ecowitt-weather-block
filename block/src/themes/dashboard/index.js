/**
 * Dashboard theme for the Ecowitt Live Weather block.
 *
 * Glassmorphism cards on a gradient background.
 * Smart layout: splits certain fields into their own cards,
 * equal-size layout for some groups, 2-column secondary grid.
 */
import { __ } from '@wordpress/i18n';
import { useState, useEffect, useRef, useCallback } from '@wordpress/element';
import DashboardCard from './components/DashboardCard';
import './style.scss';

/**
 * Given an array of card heights, find the ordering that produces
 * the most balanced columns. Uses greedy "shortest column first"
 * assignment sorted tallest-first, which is optimal for 2-3 columns.
 *
 * @param {number[]} heights Array of measured card heights.
 * @param {number}   numCols Number of columns.
 * @return {number[]} Indices in optimal render order.
 */
function balanceColumns( heights, numCols ) {
	if ( numCols <= 1 || heights.length === 0 ) {
		return heights.map( ( _, i ) => i );
	}

	// Sort indices by height descending (tallest first).
	const indices = heights.map( ( h, i ) => ( { i, h } ) );
	indices.sort( ( a, b ) => b.h - a.h );

	// Greedy: assign each card to the shortest column.
	const columns = Array.from( { length: numCols }, () => ( { items: [], height: 0 } ) );
	indices.forEach( ( { i, h } ) => {
		const shortest = columns.reduce( ( min, col ) => ( col.height < min.height ? col : min ), columns[ 0 ] );
		shortest.items.push( i );
		shortest.height += h;
	} );

	// Flatten columns into a single ordered array.
	const result = [];
	columns.forEach( ( col ) => col.items.forEach( ( i ) => result.push( i ) ) );
	return result;
}

/**
 * Groups where all fields should display at equal hero size
 * instead of hero + secondaries.
 */
const EQUAL_LAYOUT_GROUPS = [ 'indoor', 'solar_and_uvi' ];

/**
 * Fields to always split into their own standalone card
 * when they appear in the given group.
 * Key = group, value = array of field keys to split out.
 */
const SPLIT_FIELDS = {
	outdoor: [ 'humidity' ],
};

/**
 * Custom group metadata overrides for split-out fields.
 * Maps "groupKey.fieldKey" to { label, iconType }.
 */
const SPLIT_OVERRIDES = {
	'outdoor.humidity': { label: __( 'Humidity', 'pinkcrab-weather-block' ), iconType: 'humidity' },
};

/**
 * Dashboard theme component.
 */
export default function DashboardTheme( {
	data,
	selectedFields,
	stationName,
	colors = {},
	iconSet = 'meteocons',
	isLoading,
	error,
} ) {
	if ( isLoading ) {
		return (
			<div className="db-theme db-theme--loading">
				<div className="db-theme__loader">
					<div className="db-theme__spinner" />
					<p>{ __( 'Loading weather data…', 'pinkcrab-weather-block' ) }</p>
				</div>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="db-theme db-theme--error">
				<p className="db-theme__error-text">{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="db-theme db-theme--empty">
				<p>{ __( 'No weather data available.', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	// Build groups with full variant arrays for each field.
	const cards = [];

	Object.entries( selectedFields ).forEach( ( [ groupKey, fieldConfig ] ) => {
		if ( ! data[ groupKey ] || typeof fieldConfig !== 'object' ) {
			return;
		}

		const splitKeys = SPLIT_FIELDS[ groupKey ] || [];
		const mainFields = {};
		const splitCards = [];

		Object.entries( fieldConfig ).forEach( ( [ fieldKey, preferredUnit ] ) => {
			const variants = data[ groupKey ][ fieldKey ];
			if ( ! variants || ! Array.isArray( variants ) || variants.length === 0 ) {
				return;
			}

			// Check if this field should be split into its own card.
			if ( splitKeys.includes( fieldKey ) ) {
				const overrideKey = `${ groupKey }.${ fieldKey }`;
				const override = SPLIT_OVERRIDES[ overrideKey ] || {};
				splitCards.push( {
					groupKey: `${ groupKey }__${ fieldKey }`,
					fields: { [ fieldKey ]: { variants, preferredUnit } },
					layout: 'equal',
					labelOverride: override.label,
					iconOverride: override.iconType,
				} );
			} else {
				mainFields[ fieldKey ] = { variants, preferredUnit };
			}
		} );

		// Add the main group card (if it has remaining fields).
		if ( Object.keys( mainFields ).length > 0 ) {
			const layout = EQUAL_LAYOUT_GROUPS.includes( groupKey ) ? 'equal' : 'hero';
			cards.push( { groupKey, fields: mainFields, layout } );
		}

		// Add any split-out field cards.
		splitCards.forEach( ( card ) => cards.push( card ) );
	} );

	// State: balanced card order (indices into cards array).
	const [ orderedIndices, setOrderedIndices ] = useState( null );
	const [ isBalancing, setIsBalancing ] = useState( true );
	const gridRef = useRef( null );

	// After initial render, measure card heights and reorder for balanced columns.
	const rebalance = useCallback( () => {
		const grid = gridRef.current;
		if ( ! grid || cards.length === 0 ) {
			return;
		}

		const cardEls = grid.querySelectorAll( ':scope > .db-card' );
		if ( cardEls.length !== cards.length ) {
			return;
		}

		// Detect how many columns we have from the grid width.
		const gridWidth = grid.offsetWidth;
		const colWidth = 280 + 16; // minmax(280px) + gap
		const numCols = Math.max( 1, Math.floor( ( gridWidth + 16 ) / colWidth ) );

		const heights = Array.from( cardEls ).map( ( el ) => el.offsetHeight );
		const balanced = balanceColumns( heights, numCols );

		setOrderedIndices( balanced );
		setIsBalancing( false );
	}, [ cards.length ] );

	useEffect( () => {
		// Use requestAnimationFrame to ensure the DOM has painted.
		const raf = requestAnimationFrame( () => rebalance() );
		return () => cancelAnimationFrame( raf );
	}, [ rebalance ] );

	// Determine render order: use balanced order if available, otherwise natural.
	const renderCards = orderedIndices
		? orderedIndices.map( ( i ) => cards[ i ] ).filter( Boolean )
		: cards;

	// Build theme-level CSS custom properties from colors.theme.
	const themeColors = colors.theme || {};
	const themeStyle = {
		'--db-bg': themeColors.background || undefined,
		'--db-text': themeColors.text || undefined,
		'--db-text-muted': themeColors.text_muted || undefined,
		'--db-text-dim': themeColors.text_dim || undefined,
		'--db-accent': themeColors.accent || undefined,
		'--db-border': themeColors.border || undefined,
		'--db-surface': themeColors.surface || undefined,
	};

	return (
		<div className="db-theme" style={ themeStyle }>
			{ stationName && (
				<div className="db-theme__header">
					<h3 className="db-theme__station-name">{ stationName }</h3>
				</div>
			) }
			{ isBalancing && (
				<div className="db-theme__balance-overlay">
					<div className="db-theme__spinner" />
				</div>
			) }
			<div className={ `db-theme__grid${ isBalancing ? ' db-theme__grid--measuring' : '' }` } ref={ gridRef }>
				{ renderCards.length > 0 ? (
					renderCards.map( ( card ) => (
						<DashboardCard
							key={ card.groupKey }
							groupKey={ card.groupKey }
							fields={ card.fields }
							iconSet={ iconSet }
							layout={ card.layout || 'hero' }
							labelOverride={ card.labelOverride }
							iconOverride={ card.iconOverride }
						/>
					) )
				) : (
					<p className="db-theme__no-fields">
						{ __( 'No fields selected. Use the sidebar to choose measurements.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
