/**
 * Materialike theme for the Ecowitt Live Weather block.
 *
 * Material Design inspired card layout with elevation, grouped sections,
 * icons, click-to-cycle unit toggling, and CSS custom property colour cascade.
 */
import { __ } from '@wordpress/i18n';
import GroupCard from './components/GroupCard';
import './style.scss';

/**
 * Materialike theme component.
 *
 * @param {Object}      props
 * @param {Object}      props.data           The observation data (multi-unit format).
 * @param {Object}      props.selectedFields Object of { group: { field: preferredUnit } }.
 * @param {string}      props.stationName    The station display name.
 * @param {Object}      props.colors         Colour configuration { theme, groups, measurements }.
 * @param {string}      props.iconSet        The icon set name (outline, filled, illustrative).
 * @param {boolean}     props.isLoading      Loading state.
 * @param {string|null} props.error          Error message or null.
 * @return {JSX.Element} The rendered weather display.
 */
export default function MaterialikeTheme( {
	data,
	selectedFields,
	stationName,
	colors = {},
	iconSet = 'outline',
	isLoading,
	error,
} ) {
	if ( isLoading ) {
		return (
			<div className="materialike materialike--loading">
				<div className="materialike__surface">
					<div className="materialike__loader">
						<div className="materialike__spinner" />
						<p>{ __( 'Loading weather data…', 'pinkcrab-weather-block' ) }</p>
					</div>
				</div>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="materialike materialike--error">
				<div className="materialike__surface">
					<p className="materialike__error-text">{ error }</p>
				</div>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="materialike materialike--empty">
				<div className="materialike__surface">
					<p>{ __( 'No weather data available.', 'pinkcrab-weather-block' ) }</p>
				</div>
			</div>
		);
	}

	// Build groups with full variant arrays for each field.
	const groups = [];

	Object.entries( selectedFields ).forEach( ( [ groupKey, fieldConfig ] ) => {
		if ( ! data[ groupKey ] || typeof fieldConfig !== 'object' ) {
			return;
		}

		const fields = {};
		Object.entries( fieldConfig ).forEach( ( [ fieldKey, preferredUnit ] ) => {
			const variants = data[ groupKey ][ fieldKey ];
			if ( ! variants || ! Array.isArray( variants ) || variants.length === 0 ) {
				return;
			}
			fields[ fieldKey ] = { variants, preferredUnit };
		} );

		if ( Object.keys( fields ).length > 0 ) {
			groups.push( { groupKey, fields } );
		}
	} );

	// Build theme-level CSS custom properties from colors.theme.
	const themeColors = colors.theme || {};
	const themeStyle = {
		'--m-bg': themeColors.background || undefined,
		'--m-text': themeColors.text || undefined,
		'--m-text-muted': themeColors.text_muted || undefined,
		'--m-accent': themeColors.accent || undefined,
		'--m-border': themeColors.border || undefined,
		'--m-surface': themeColors.surface || undefined,
	};

	return (
		<div className="materialike" style={ themeStyle }>
			<div className="materialike__surface">
				{ stationName && (
					<div className="materialike__header">
						<h3 className="materialike__station-name">{ stationName }</h3>
					</div>
				) }
				<div className="materialike__grid">
					{ groups.length > 0 ? (
						groups.map( ( group ) => (
							<GroupCard
								key={ group.groupKey }
								groupKey={ group.groupKey }
								fields={ group.fields }
								iconSet={ iconSet }
								groupColors={ colors.groups?.[ group.groupKey ] }
								fieldColors={ colors.measurements }
							/>
						) )
					) : (
						<p className="materialike__no-fields">
							{ __( 'No fields selected. Use the sidebar to choose measurements.', 'pinkcrab-weather-block' ) }
						</p>
					) }
				</div>
			</div>
		</div>
	);
}
