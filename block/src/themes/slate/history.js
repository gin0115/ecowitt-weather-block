/**
 * Slate history theme — dark panel cards with charts.
 *
 * Uses --slate-* CSS custom properties for consistent styling.
 */
import { __ } from '@wordpress/i18n';
import { useState, useMemo } from '@wordpress/element';
import {
	ResponsiveContainer,
	ComposedChart,
	LineChart,
	Line,
	Scatter,
	XAxis,
	YAxis,
	CartesianGrid,
	Tooltip,
	Legend,
} from 'recharts';
import { buildChartData, formatTimestamp, formatLabel } from '../../history/chartUtils';
import { GROUP_META } from '../materialike/constants';

/**
 * Shared axis and tooltip props for both chart types.
 */
/**
 * Determine a sensible tick count for the X axis based on range.
 */
function xTickCount( range ) {
	switch ( range ) {
		case '24h': return 8;
		case '7d': return 7;
		case '30d': return 10;
		case '90d': return 12;
		case '1y': return 12;
		case '2y': return 12;
		default: return 10;
	}
}

function chartAxes( range ) {
	return {
		xAxis: {
			dataKey: 'timestamp',
			tickFormatter: ( ts ) => formatTimestamp( ts, range ),
			tick: { fontSize: 11, fill: 'var(--slate-text-muted, #94a3b8)' },
			stroke: 'var(--slate-border, #334155)',
			type: 'number',
			domain: [ 'dataMin', 'dataMax' ],
			tickCount: xTickCount( range ),
		},
		yAxis: {
			tick: { fontSize: 11, fill: 'var(--slate-text-muted, #94a3b8)' },
			stroke: 'var(--slate-border, #334155)',
		},
		grid: {
			strokeDasharray: '3 3',
			stroke: 'var(--slate-border, #334155)',
		},
		tooltip: {
			labelFormatter: ( ts ) => formatTimestamp( ts, range, true ),
			formatter: ( val ) => [ val, '' ],
			contentStyle: {
				background: 'var(--slate-panel, #1e293b)',
				border: '1px solid var(--slate-border, #334155)',
				borderRadius: '12px',
				color: 'var(--slate-text, #f1f5f9)',
			},
			labelStyle: { color: 'var(--slate-text, #f1f5f9)' },
		},
	};
}

/**
 * Convert degrees to cardinal direction.
 *
 * @param {number} deg Degrees (0-360).
 * @return {string} Cardinal direction.
 */
const CARDINALS = [ 'N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW' ];
function degreesToCardinal( deg ) {
	return CARDINALS[ Math.round( ( ( deg % 360 ) + 360 ) % 360 / 22.5 ) % 16 ];
}

/**
 * Rebuild dataPoints from fieldRefs for a given unit key.
 *
 * @param {Array}  fieldRefs Array of { key, variants, type }.
 * @param {string} unitKey   The unit to use (e.g. '°F', 'mm').
 * @return {{ dataPoints: Array, unitLabel: string }} Rebuilt data.
 */
function rebuildDataPoints( fieldRefs, unitKey ) {
	const timestampMap = {};
	let unitLabel = unitKey;

	fieldRefs.forEach( ( field ) => {
		const variant = field.variants.find( ( v ) => v.unit === unitKey );
		if ( ! variant || ! variant.data ) {
			return;
		}
		unitLabel = variant.label || unitKey;
		variant.data.forEach( ( point ) => {
			if ( ! timestampMap[ point.timestamp ] ) {
				timestampMap[ point.timestamp ] = { timestamp: point.timestamp };
			}
			timestampMap[ point.timestamp ][ field.key ] = parseFloat( point.value );
		} );
	} );

	const dataPoints = Object.values( timestampMap ).sort(
		( a, b ) => a.timestamp - b.timestamp
	);

	return { dataPoints, unitLabel };
}

function ChartCard( { chart, range, groupChartCount } ) {
	const meta = GROUP_META[ chart.groupKey ] || {};
	const groupLabel = meta.label || formatLabel( chart.groupKey );

	// When a group has multiple charts, show series names in the title.
	const title = groupChartCount > 1
		? `${ groupLabel } — ${ chart.seriesTitle }`
		: groupLabel;

	// Track hidden series for legend toggle.
	const [ hiddenSeries, setHiddenSeries ] = useState( {} );

	// Unit switching state.
	const availableUnits = chart.availableVariants.map( ( v ) => v.unit );
	const [ activeUnit, setActiveUnit ] = useState( chart.unitKey );

	const handleUnitCycle = () => {
		const idx = availableUnits.indexOf( activeUnit );
		const next = availableUnits[ ( idx + 1 ) % availableUnits.length ];
		setActiveUnit( next );
	};

	// Rebuild dataPoints when unit changes.
	const { dataPoints, unitLabel } = useMemo( () => {
		if ( activeUnit === chart.unitKey || ! chart.fieldRefs ) {
			return { dataPoints: chart.dataPoints, unitLabel: chart.unit };
		}
		return rebuildDataPoints( chart.fieldRefs, activeUnit );
	}, [ activeUnit, chart ] );

	const handleLegendClick = ( entry ) => {
		setHiddenSeries( ( prev ) => ( {
			...prev,
			[ entry.dataKey ]: ! prev[ entry.dataKey ],
		} ) );
	};

	// Y axis auto-fit toggle.
	const [ autoFitY, setAutoFitY ] = useState( false );

	const axes = chartAxes( range );
	const isScatter = chart.chartType === 'scatter';
	const hasMultipleUnits = availableUnits.length > 1;
	const yDomain = isScatter ? [ 0, 360 ] : ( autoFitY ? [ 'dataMin', 'dataMax' ] : undefined );

	// Tooltip formatter — scatter always shows cardinal direction + degrees.
	const tooltipFormatter = isScatter
		? ( val, name ) => [ `${ degreesToCardinal( val ) } (${ val }°)`, name ]
		: ( val, name ) => [ `${ val } ${ unitLabel }`, name ];

	// Tooltip styles — explicit hex values (CSS vars don't work in Recharts portal).
	const tooltipStyle = {
		contentStyle: {
			background: '#1e293b',
			border: '1px solid #334155',
			borderRadius: '12px',
			color: '#f1f5f9',
		},
		labelStyle: { color: '#f1f5f9' },
	};

	// Legend with clickable toggle and greyed-out hidden items.
	const legendProps = {
		wrapperStyle: { color: '#94a3b8' },
		onClick: handleLegendClick,
		formatter: ( value, entry ) => (
			<span style={ { color: hiddenSeries[ entry.dataKey ] ? '#64748b' : entry.color, cursor: 'pointer' } }>
				{ value }
			</span>
		),
	};

	return (
		<div className="slate-history__card">
			<div className="slate-history__card-header">
				<h4 className="slate-history__card-title">{ title }</h4>
				<div className="slate-history__card-controls">
					{ ! isScatter && (
						<label className="slate-history__fit-toggle">
							<span className="slate-history__fit-label">{ __( 'Fit', 'pinkcrab-weather-block' ) }</span>
							<span
								className={ `slate-history__fit-track${ autoFitY ? ' slate-history__fit-track--on' : '' }` }
								onClick={ () => setAutoFitY( ! autoFitY ) }
								onKeyDown={ ( e ) => { if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); setAutoFitY( ! autoFitY ); } } }
								role="switch"
								aria-checked={ autoFitY }
								tabIndex={ 0 }
							>
								<span className="slate-history__fit-thumb" />
							</span>
						</label>
					) }
					{ hasMultipleUnits ? (
						<button
							type="button"
							className="slate-history__card-unit slate-history__card-unit--toggle"
							onClick={ handleUnitCycle }
							title={ __( 'Click to change unit', 'pinkcrab-weather-block' ) }
						>
							{ unitLabel }
						</button>
					) : (
						<span className="slate-history__card-unit">{ unitLabel }</span>
					) }
				</div>
			</div>
			<ResponsiveContainer width="100%" height={ 250 }>
				{ isScatter ? (
					<ComposedChart data={ dataPoints }>
						<CartesianGrid { ...axes.grid } />
						<XAxis { ...axes.xAxis } />
						<YAxis
						{ ...axes.yAxis }
						domain={ [ 0, 360 ] }
						ticks={ [ 0, 45, 90, 135, 180, 225, 270, 315, 360 ] }
						tickFormatter={ degreesToCardinal }
					/>
						<Tooltip
							labelFormatter={ axes.tooltip.labelFormatter }
							formatter={ tooltipFormatter }
							{ ...tooltipStyle }
						/>
						{ chart.series.length > 1 && (
							<Legend { ...legendProps } />
						) }
						{ chart.series.map( ( s ) => (
							<Scatter
								key={ s.key }
								dataKey={ s.key }
								name={ s.label }
								fill={ hiddenSeries[ s.key ] ? 'transparent' : s.color }
								shape={ <circle r={ 3 } /> }
							/>
						) ) }
					</ComposedChart>
				) : (
					<LineChart data={ dataPoints }>
						<CartesianGrid { ...axes.grid } />
						<XAxis { ...axes.xAxis } />
						<YAxis { ...axes.yAxis } domain={ yDomain } />
						<Tooltip
							labelFormatter={ axes.tooltip.labelFormatter }
							formatter={ tooltipFormatter }
							{ ...tooltipStyle }
						/>
						{ chart.series.length > 1 && (
							<Legend { ...legendProps } />
						) }
						{ chart.series.map( ( s ) => (
							<Line
								key={ s.key }
								type="monotone"
								dataKey={ s.key }
								name={ s.label }
								stroke={ hiddenSeries[ s.key ] ? 'transparent' : s.color }
								dot={ false }
								strokeWidth={ 2 }
							/>
						) ) }
					</LineChart>
				) }
			</ResponsiveContainer>
		</div>
	);
}

export default function SlateHistoryTheme( {
	data,
	selectedFields,
	stationName,
	colors = {},
	iconSet = 'meteocons',
	isLoading,
	error,
	range,
} ) {
	const themeColors = colors.theme || {};
	const themeStyle = {
		'--slate-bg': themeColors.background || undefined,
		'--slate-text': themeColors.text || undefined,
		'--slate-accent': themeColors.accent || undefined,
		'--slate-border': themeColors.border || undefined,
		'--slate-surface': themeColors.surface || undefined,
	};

	if ( isLoading ) {
		return (
			<div className="slate-history slate-history--loading" style={ themeStyle }>
				<div className="slate-theme__loader">
					<div className="slate-theme__spinner" />
					<p>{ __( 'Loading weather history…', 'pinkcrab-weather-block' ) }</p>
				</div>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="slate-history slate-history--error" style={ themeStyle }>
				<p className="slate-theme__error-text">{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="slate-history slate-history--empty" style={ themeStyle }>
				<p>{ __( 'No weather history available.', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	const charts = buildChartData( data, selectedFields );

	// Count how many charts each group has (for title logic).
	const groupCounts = {};
	charts.forEach( ( chart ) => {
		groupCounts[ chart.groupKey ] = ( groupCounts[ chart.groupKey ] || 0 ) + 1;
	} );

	return (
		<div className="slate-history" style={ themeStyle }>
			{ stationName && (
				<div className="slate-theme__header">
					<h3 className="slate-theme__station-name">{ stationName }</h3>
				</div>
			) }
			<div className="slate-history__grid">
				{ charts.length > 0 ? (
					charts.map( ( chart ) => (
						<ChartCard
							key={ `${ chart.groupKey }-${ chart.unitKey }` }
							chart={ chart }
							range={ range }
							groupChartCount={ groupCounts[ chart.groupKey ] }
						/>
					) )
				) : (
					<p className="slate-theme__no-fields">
						{ __( 'No fields selected.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
