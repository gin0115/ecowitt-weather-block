/**
 * Default theme for the Ecowitt Live Weather block.
 *
 * Renders a simple card grid of selected weather fields.
 * Each field is an array of unit variants from the REST API:
 * [ { value, label, unit }, { value, label, unit }, ... ]
 */
import { __ } from '@wordpress/i18n';

/**
 * Render a single measurement field card.
 *
 * @param {Object} props
 * @param {string} props.label   The field label.
 * @param {Object} props.variant The selected variant { value, label, unit }.
 * @return {JSX.Element} The field card.
 */
function FieldCard( { label, variant } ) {
	return (
		<div className="ecowitt-weather-live__card">
			<span className="ecowitt-weather-live__card-label">{ label }</span>
			<span className="ecowitt-weather-live__card-value">
				{ variant.value }
				{ variant.unit && (
					<span className="ecowitt-weather-live__card-unit">
						{ ' ' }{ variant.unit }
					</span>
				) }
			</span>
		</div>
	);
}

/**
 * Format a field key into a readable label.
 *
 * @param {string} key The field key e.g. "temperature" or "feels_like".
 * @return {string} Formatted label.
 */
function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * Pick the preferred variant from a variants array.
 *
 * @param {Array}       variants      Array of { value, label, unit }.
 * @param {string|null} preferredUnit The preferred unit string, or null for first.
 * @return {Object} The selected variant.
 */
function pickVariant( variants, preferredUnit ) {
	if ( preferredUnit ) {
		const match = variants.find( ( v ) => v.unit === preferredUnit );
		if ( match ) {
			return match;
		}
	}
	return variants[ 0 ];
}

/**
 * Default theme component.
 *
 * @param {Object}      props
 * @param {Object}      props.data           The observation data (multi-unit format).
 * @param {Object}      props.selectedFields Object of { group: { field: preferredUnit } }.
 * @param {string}      props.stationName    The station display name.
 * @param {boolean}     props.isLoading      Loading state.
 * @param {string|null} props.error          Error message or null.
 * @return {JSX.Element} The rendered weather display.
 */
export default function DefaultTheme( { data, selectedFields, stationName, isLoading, error } ) {
	if ( isLoading ) {
		return (
			<div className="ecowitt-weather-live ecowitt-weather-live--loading">
				<p>{ __( 'Loading weather data…', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="ecowitt-weather-live ecowitt-weather-live--error">
				<p>{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="ecowitt-weather-live ecowitt-weather-live--empty">
				<p>{ __( 'No weather data available.', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	// Collect all fields to render.
	const fields = [];

	Object.entries( selectedFields ).forEach( ( [ group, fieldConfig ] ) => {
		if ( ! data[ group ] || typeof fieldConfig !== 'object' ) {
			return;
		}

		Object.entries( fieldConfig ).forEach( ( [ fieldKey, preferredUnit ] ) => {
			const variants = data[ group ][ fieldKey ];
			if ( ! variants || ! Array.isArray( variants ) || variants.length === 0 ) {
				return;
			}

			const variant = pickVariant( variants, preferredUnit );
			fields.push( {
				key: `${ group }.${ fieldKey }`,
				label: formatLabel( fieldKey ),
				variant,
			} );
		} );
	} );

	return (
		<div className="ecowitt-weather-live ecowitt-weather-live--default">
			{ stationName && (
				<h3 className="ecowitt-weather-live__title">{ stationName }</h3>
			) }
			<div className="ecowitt-weather-live__grid">
				{ fields.length > 0 ? (
					fields.map( ( field ) => (
						<FieldCard
							key={ field.key }
							label={ field.label }
							variant={ field.variant }
						/>
					) )
				) : (
					<p className="ecowitt-weather-live__no-fields">
						{ __( 'No fields selected.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
