/**
 * Materialike history theme — material-styled cards with line charts.
 *
 * Uses --m-* CSS custom properties for theming.
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
import { GROUP_META, FIELD_ICON_MAP } from './constants';
import { getIcon } from '../../icons';

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

/**
 * Convert degrees to cardinal direction.
 */
const CARDINALS = [ 'N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW' ];
function degreesToCardinal( deg ) {
	return CARDINALS[ Math.round( ( ( deg % 360 ) + 360 ) % 360 / 22.5 ) % 16 ];
}

/**
 * Rebuild dataPoints from fieldRefs for a given unit key.
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

function ChartCard( { chart, range, iconSet, groupChartCount } ) {
	const meta = GROUP_META[ chart.groupKey ] || {};
	const groupLabel = meta.label || formatLabel( chart.groupKey );
	const iconType = meta.iconType || 'default';
	const IconComponent = getIcon( iconSet, iconType );

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
			background: '#fff',
			border: '1px solid #e0e0e0',
			borderRadius: '8px',
			color: '#212121',
		},
		labelStyle: { color: '#212121' },
	};

	// Legend with clickable toggle and greyed-out hidden items.
	const legendProps = {
		wrapperStyle: { color: '#757575' },
		onClick: handleLegendClick,
		formatter: ( value, entry ) => (
			<span style={ { color: hiddenSeries[ entry.dataKey ] ? '#bdbdbd' : entry.color, cursor: 'pointer' } }>
				{ value }
			</span>
		),
	};

	return (
		<div className="m-history__card">
			<div className="m-history__card-header">
				<span className="m-history__card-icon">
					<IconComponent className="m-history__icon" />
				</span>
				<h4 className="m-history__card-title">{ title }</h4>
				<div className="m-history__card-controls">
					{ ! isScatter && (
						<label className="m-history__fit-toggle">
							<span className="m-history__fit-label">{ __( 'Fit', 'pinkcrab-weather-block' ) }</span>
							<span
								className={ `m-history__fit-track${ autoFitY ? ' m-history__fit-track--on' : '' }` }
								onClick={ () => setAutoFitY( ! autoFitY ) }
								onKeyDown={ ( e ) => { if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); setAutoFitY( ! autoFitY ); } } }
								role="switch"
								aria-checked={ autoFitY }
								tabIndex={ 0 }
							>
								<span className="m-history__fit-thumb" />
							</span>
						</label>
					) }
					{ hasMultipleUnits ? (
						<button
							type="button"
							className="m-history__card-unit m-history__card-unit--toggle"
							onClick={ handleUnitCycle }
							title={ __( 'Click to change unit', 'pinkcrab-weather-block' ) }
						>
							{ unitLabel }
						</button>
					) : (
						<span className="m-history__card-unit">{ unitLabel }</span>
					) }
				</div>
			</div>
			<ResponsiveContainer width="100%" height={ 250 }>
				{ isScatter ? (
					<ComposedChart data={ dataPoints }>
						<CartesianGrid strokeDasharray="3 3" stroke="var(--m-border, #e0e0e0)" />
						<XAxis
							dataKey="timestamp"
							tickFormatter={ ( ts ) => formatTimestamp( ts, range ) }
							tick={ { fontSize: 11, fill: 'var(--m-text-secondary, #757575)' } }
							type="number"
							domain={ [ 'dataMin', 'dataMax' ] }
							tickCount={ xTickCount( range ) }
						/>
						<YAxis
							tick={ { fontSize: 11, fill: 'var(--m-text-secondary, #757575)' } }
							domain={ [ 0, 360 ] }
							ticks={ [ 0, 45, 90, 135, 180, 225, 270, 315, 360 ] }
							tickFormatter={ degreesToCardinal }
						/>
						<Tooltip
							labelFormatter={ ( ts ) => formatTimestamp( ts, range, true ) }
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
						<CartesianGrid strokeDasharray="3 3" stroke="var(--m-border, #e0e0e0)" />
						<XAxis
							dataKey="timestamp"
							tickFormatter={ ( ts ) => formatTimestamp( ts, range ) }
							tick={ { fontSize: 11, fill: 'var(--m-text-secondary, #757575)' } }
						/>
						<YAxis
							tick={ { fontSize: 11, fill: 'var(--m-text-secondary, #757575)' } }
							domain={ yDomain }
						/>
						<Tooltip
							labelFormatter={ ( ts ) => formatTimestamp( ts, range, true ) }
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

export default function MaterialikeHistoryTheme( {
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
		'--m-bg': themeColors.background || undefined,
		'--m-text': themeColors.text || undefined,
		'--m-accent': themeColors.accent || undefined,
		'--m-border': themeColors.border || undefined,
		'--m-surface': themeColors.surface || undefined,
	};

	if ( isLoading ) {
		return (
			<div className="m-history m-history--loading" style={ themeStyle }>
				<div className="m-history__loader">
					<div className="m-history__spinner" />
					<p>{ __( 'Loading weather history…', 'pinkcrab-weather-block' ) }</p>
				</div>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="m-history m-history--error" style={ themeStyle }>
				<p className="m-history__error-text">{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="m-history m-history--empty" style={ themeStyle }>
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
		<div className="m-history" style={ themeStyle }>
			{ stationName && (
				<div className="m-history__header">
					<h3 className="m-history__station-name">{ stationName }</h3>
				</div>
			) }
			<div className="m-history__grid">
				{ charts.length > 0 ? (
					charts.map( ( chart ) => (
						<ChartCard
							key={ `${ chart.groupKey }-${ chart.unitKey }` }
							chart={ chart }
							range={ range }
							iconSet={ iconSet }
							groupChartCount={ groupCounts[ chart.groupKey ] }
						/>
					) )
				) : (
					<p className="m-history__no-fields">
						{ __( 'No fields selected.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
