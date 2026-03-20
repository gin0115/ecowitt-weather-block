/**
 * Save component for Ecowitt Weather block.
 *
 * Outputs a container div with data attributes that view.js will
 * pick up on the frontend to fetch and render weather data.
 */
import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const {
		connectionKey,
		mac,
		stationName,
		selectedFields,
		theme,
		colors,
		iconSet,
		mode,
		defaultRange,
		cycleType,
		autoRefreshInterval,
	} = attributes;

	const isHistory = mode === 'history';
	const className = isHistory ? 'ecowitt-weather-history' : 'ecowitt-weather-live';

	const dataProps = {
		'data-connection': connectionKey,
		'data-mac': mac,
		'data-station-name': stationName,
		'data-selected-fields': JSON.stringify( selectedFields ),
		'data-theme': theme,
		'data-colors': JSON.stringify( colors ),
		'data-icon-set': iconSet,
		'data-mode': mode,
	};

	if ( isHistory ) {
		dataProps[ 'data-default-range' ] = defaultRange;
		dataProps[ 'data-cycle-type' ] = cycleType;
		dataProps[ 'data-auto-refresh-interval' ] = autoRefreshInterval;
	}

	const loadingText = isHistory ? 'Loading weather history…' : 'Loading weather data…';

	return (
		<div
			{ ...useBlockProps.save() }
			className={ className }
			{ ...dataProps }
		>
			<p className={ `${ className }__loading` }>{ loadingText }</p>
		</div>
	);
}
