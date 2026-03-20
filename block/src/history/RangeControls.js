/**
 * RangeControls — preset time range buttons + custom date picker.
 *
 * Rendered above the history theme, styled via CSS custom properties
 * so it adapts to the active theme.
 */
import { __ } from '@wordpress/i18n';

const PRESETS = [
	{ key: '24h', label: __( '24h', 'pinkcrab-weather-block' ) },
	{ key: '7d', label: __( '7d', 'pinkcrab-weather-block' ) },
	{ key: '30d', label: __( '30d', 'pinkcrab-weather-block' ) },
	{ key: '90d', label: __( '90d', 'pinkcrab-weather-block' ) },
	{ key: '1y', label: __( '1y', 'pinkcrab-weather-block' ) },
	{ key: '2y', label: __( '2y', 'pinkcrab-weather-block' ) },
	{ key: 'custom', label: __( 'Custom', 'pinkcrab-weather-block' ) },
];

export default function RangeControls( {
	range,
	customFrom,
	customTo,
	onRangeChange,
	onCustomFromChange,
	onCustomToChange,
} ) {
	return (
		<div className="ecowitt-history-controls">
			<div className="ecowitt-history-controls__presets">
				{ PRESETS.map( ( preset ) => (
					<button
						key={ preset.key }
						type="button"
						className={ `ecowitt-history-controls__btn${ range === preset.key ? ' ecowitt-history-controls__btn--active' : '' }` }
						onClick={ () => onRangeChange( preset.key ) }
					>
						{ preset.label }
					</button>
				) ) }
			</div>

			{ range === 'custom' && (
				<div className="ecowitt-history-controls__date-picker">
					<label className="ecowitt-history-controls__date-label">
						{ __( 'From', 'pinkcrab-weather-block' ) }
						<input
							type="date"
							className="ecowitt-history-controls__date-input"
							value={ customFrom }
							onChange={ ( e ) => onCustomFromChange( e.target.value ) }
						/>
					</label>
					<label className="ecowitt-history-controls__date-label">
						{ __( 'To', 'pinkcrab-weather-block' ) }
						<input
							type="date"
							className="ecowitt-history-controls__date-input"
							value={ customTo }
							onChange={ ( e ) => onCustomToChange( e.target.value ) }
						/>
					</label>
				</div>
			) }
		</div>
	);
}
