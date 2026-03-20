/**
 * Default history theme — simple white cards with line charts.
 */
import { __ } from '@wordpress/i18n';
import {
	ResponsiveContainer,
	LineChart,
	Line,
	XAxis,
	YAxis,
	CartesianGrid,
	Tooltip,
	Legend,
} from 'recharts';
import { buildChartData, formatTimestamp, formatLabel } from '../../history/chartUtils';
import { GROUP_META } from '../materialike/constants';

function ChartCard( { chart, range } ) {
	const meta = GROUP_META[ chart.groupKey ] || {};
	const title = meta.label || formatLabel( chart.groupKey );

	return (
		<div className="ecowitt-history-default__card">
			<h4 className="ecowitt-history-default__card-title">
				{ title }
				<span className="ecowitt-history-default__card-unit">({ chart.unit })</span>
			</h4>
			<ResponsiveContainer width="100%" height={ 250 }>
				<LineChart data={ chart.dataPoints }>
					<CartesianGrid strokeDasharray="3 3" stroke="#e5e7eb" />
					<XAxis
						dataKey="timestamp"
						tickFormatter={ ( ts ) => formatTimestamp( ts, range ) }
						tick={ { fontSize: 11 } }
					/>
					<YAxis tick={ { fontSize: 11 } } />
					<Tooltip
						labelFormatter={ ( ts ) => formatTimestamp( ts, range, true ) }
						formatter={ ( val ) => [ val, '' ] }
					/>
					{ chart.series.length > 1 && <Legend /> }
					{ chart.series.map( ( s ) => (
						<Line
							key={ s.key }
							type="monotone"
							dataKey={ s.key }
							name={ s.label }
							stroke={ s.color }
							dot={ false }
							strokeWidth={ 2 }
						/>
					) ) }
				</LineChart>
			</ResponsiveContainer>
		</div>
	);
}

export default function DefaultHistoryTheme( {
	data,
	selectedFields,
	stationName,
	isLoading,
	error,
	range,
} ) {
	if ( isLoading ) {
		return (
			<div className="ecowitt-history-default ecowitt-history-default--loading">
				<p>{ __( 'Loading weather history…', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="ecowitt-history-default ecowitt-history-default--error">
				<p>{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="ecowitt-history-default ecowitt-history-default--empty">
				<p>{ __( 'No weather history available.', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	const charts = buildChartData( data, selectedFields );

	return (
		<div className="ecowitt-history-default">
			{ stationName && (
				<h3 className="ecowitt-history-default__title">{ stationName }</h3>
			) }
			<div className="ecowitt-history-default__grid">
				{ charts.length > 0 ? (
					charts.map( ( chart ) => (
						<ChartCard
							key={ `${ chart.groupKey }-${ chart.unitKey }` }
							chart={ chart }
							range={ range }
						/>
					) )
				) : (
					<p className="ecowitt-history-default__no-fields">
						{ __( 'No fields selected.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
