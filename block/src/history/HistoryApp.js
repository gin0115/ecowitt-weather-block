/**
 * HistoryApp — main orchestrator for frontend history blocks.
 *
 * Manages range state, fetches data via useHistoryWeather,
 * and renders RangeControls + the selected history theme.
 */
import { useState, useEffect, useCallback, useRef } from '@wordpress/element';
import useHistoryWeather from '../hooks/useHistoryWeather';
import { getHistoryTheme } from '../themes/registry';
import { computeDateRange } from './chartUtils';
import RangeControls from './RangeControls';

export default function HistoryApp( {
	connectionKey,
	mac,
	stationName,
	selectedFields,
	themeName,
	colors,
	iconSet,
	defaultRange,
	cycleType,
} ) {
	const [ range, setRange ] = useState( defaultRange || '24h' );
	const [ customFrom, setCustomFrom ] = useState( '' );
	const [ customTo, setCustomTo ] = useState( '' );
	const [ isFullscreen, setIsFullscreen ] = useState( false );
	const containerRef = useRef( null );

	const { data, isLoading, error, meta, fetchHistory } = useHistoryWeather();

	// Fetch data when range or custom dates change.
	useEffect( () => {
		const { from, to } = computeDateRange( range, customFrom, customTo );
		const cycle = cycleType === 'auto' ? '' : cycleType;
		fetchHistory( connectionKey, mac, from, to, cycle );
	}, [ connectionKey, mac, range, customFrom, customTo, cycleType, fetchHistory ] );

	// Sync state when exiting fullscreen via Escape or browser controls.
	useEffect( () => {
		const onFullscreenChange = () => {
			if ( ! document.fullscreenElement ) {
				setIsFullscreen( false );
			}
		};
		document.addEventListener( 'fullscreenchange', onFullscreenChange );
		return () => document.removeEventListener( 'fullscreenchange', onFullscreenChange );
	}, [] );

	const toggleFullscreen = useCallback( () => {
		if ( ! isFullscreen && containerRef.current ) {
			containerRef.current.requestFullscreen?.();
			setIsFullscreen( true );
		} else if ( document.fullscreenElement ) {
			document.exitFullscreen?.();
			setIsFullscreen( false );
		}
	}, [ isFullscreen ] );

	const HistoryTheme = getHistoryTheme( themeName );

	return (
		<div
			ref={ containerRef }
			className={ `ecowitt-weather-history__app${ isFullscreen ? ' ecowitt-weather-history__app--fullscreen' : '' }` }
		>
			<div className="ecowitt-weather-history__toolbar">
				<RangeControls
					range={ range }
					customFrom={ customFrom }
					customTo={ customTo }
					onRangeChange={ setRange }
					onCustomFromChange={ setCustomFrom }
					onCustomToChange={ setCustomTo }
				/>
				<button
					type="button"
					className="ecowitt-weather-history__fullscreen-btn"
					onClick={ toggleFullscreen }
					aria-label={ isFullscreen ? 'Exit fullscreen' : 'Enter fullscreen' }
					title={ isFullscreen ? 'Exit fullscreen' : 'Fullscreen' }
				>
					{ isFullscreen ? '\u2716' : '\u26F6' }
				</button>
			</div>
			<HistoryTheme
				data={ data }
				selectedFields={ selectedFields }
				stationName={ stationName }
				colors={ colors }
				iconSet={ iconSet }
				isLoading={ isLoading }
				error={ error }
				range={ range }
			/>
			{ isFullscreen && (
				<div className="ecowitt-weather-history__rotate-prompt">
					<span>&#x1F4F1; Rotate to landscape for best view</span>
				</div>
			) }
		</div>
	);
}
