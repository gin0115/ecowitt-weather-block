/**
 * Slate theme for the Ecowitt Live Weather block.
 *
 * Dark panel cards with group-specific layouts, unit toggle buttons,
 * and a single-column mobile-first design.
 */
import { __ } from '@wordpress/i18n';
import SlateCard from './components/SlateCard';
import './style.scss';

/**
 * Layout type per group — determines how fields are arranged within the card.
 *
 * 'split'     — primary big left, secondary details right, optional footer row.
 * 'hero-grid' — primary big top, 3-column grid of secondaries below.
 * 'compass'   — wind compass dial left, speed + gust right.
 * 'dots'      — status indicator dots in a row.
 */
const GROUP_LAYOUTS = {
	outdoor: 'split',
	indoor: 'split',
	solar_and_uvi: 'split',
	rainfall: 'hero-grid',
	rainfall_piezo: 'hero-grid',
	wind: 'compass',
	pressure: 'split',
	lightning: 'split',
	indoor_co2: 'split',
	co2_aqi_combo: 'split',
	pm25_aqi_combo: 'split',
	pm10_aqi_combo: 'split',
	pm1_aqi_combo: 'split',
	pm4_aqi_combo: 'split',
	t_rh_aqi_combo: 'split',
	water_leak: 'dots',
	battery: 'split',
};

/**
 * Primary field per group — shown as the big hero value.
 */
const PRIMARY_FIELDS = {
	outdoor: 'temperature',
	indoor: 'temperature',
	solar_and_uvi: 'solar',
	rainfall: 'rain_rate',
	rainfall_piezo: 'rain_rate',
	wind: 'wind_speed',
	pressure: 'relative',
	lightning: 'distance',
	indoor_co2: 'co2',
	co2_aqi_combo: 'co2',
	pm25_aqi_combo: 'real_time_aqi',
	pm10_aqi_combo: 'real_time_aqi',
	pm1_aqi_combo: 'real_time_aqi',
	pm4_aqi_combo: 'real_time_aqi',
	t_rh_aqi_combo: 'temperature',
};

/**
 * Fields that belong in the footer row (below a divider) instead of the sidebar.
 */
const FOOTER_FIELDS = {
	outdoor: [ 'dew_point', 'humidity' ],
};

/**
 * Fields shown beside the hero value in hero-grid layout (like sidebar in split).
 */
const HERO_SIDEBAR_FIELDS = {
	rainfall: [ 'event' ],
	rainfall_piezo: [ 'event' ],
};

/**
 * Fields that get promoted to hero sidebar when ALL of them are present.
 * e.g. 'daily' moves to sidebar only when weekly, monthly, and yearly are also selected.
 */
const CONDITIONAL_HERO_SIDEBAR = {
	rainfall: { field: 'daily', requireAll: [ 'daily', 'weekly', 'monthly', 'yearly' ] },
	rainfall_piezo: { field: 'daily', requireAll: [ 'daily', 'weekly', 'monthly', 'yearly' ] },
};

/**
 * Slate theme component.
 */
export default function SlateTheme( {
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
			<div className="slate-theme slate-theme--loading">
				<div className="slate-theme__loader">
					<div className="slate-theme__spinner" />
					<p>{ __( 'Loading weather data…', 'pinkcrab-weather-block' ) }</p>
				</div>
			</div>
		);
	}

	if ( error ) {
		return (
			<div className="slate-theme slate-theme--error">
				<p className="slate-theme__error-text">{ error }</p>
			</div>
		);
	}

	if ( ! data ) {
		return (
			<div className="slate-theme slate-theme--empty">
				<p>{ __( 'No weather data available.', 'pinkcrab-weather-block' ) }</p>
			</div>
		);
	}

	const cards = [];

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
			// Build hero sidebar fields list, including conditional ones.
			const baseSidebar = [ ...( HERO_SIDEBAR_FIELDS[ groupKey ] || [] ) ];
			const conditional = CONDITIONAL_HERO_SIDEBAR[ groupKey ];
			if ( conditional ) {
				const allPresent = conditional.requireAll.every( ( key ) => key in fields );
				if ( allPresent ) {
					baseSidebar.push( conditional.field );
				}
			}

			cards.push( {
				groupKey,
				fields,
				layout: GROUP_LAYOUTS[ groupKey ] || 'split',
				primaryField: PRIMARY_FIELDS[ groupKey ] || null,
				footerFields: FOOTER_FIELDS[ groupKey ] || [],
				heroSidebarFields: baseSidebar,
			} );
		}
	} );

	const themeColors = colors.theme || {};
	const themeStyle = {
		'--slate-bg': themeColors.background || undefined,
		'--slate-text': themeColors.text || undefined,
		'--slate-text-muted': themeColors.text_muted || undefined,
		'--slate-text-dim': themeColors.text_dim || undefined,
		'--slate-accent': themeColors.accent || undefined,
		'--slate-border': themeColors.border || undefined,
		'--slate-surface': themeColors.surface || undefined,
		'--slate-ok': themeColors.success || undefined,
		'--slate-alert': themeColors.alert || undefined,
	};

	return (
		<div className="slate-theme" style={ themeStyle }>
			{ stationName && (
				<div className="slate-theme__header">
					<h3 className="slate-theme__station-name">{ stationName }</h3>
				</div>
			) }
			<div className="slate-theme__grid">
				{ cards.length > 0 ? (
					cards.map( ( card ) => (
						<SlateCard
							key={ card.groupKey }
							groupKey={ card.groupKey }
							fields={ card.fields }
							layout={ card.layout }
							primaryField={ card.primaryField }
							footerFields={ card.footerFields }
							heroSidebarFields={ card.heroSidebarFields }
							iconSet={ iconSet }
						/>
					) )
				) : (
					<p className="slate-theme__no-fields">
						{ __( 'No fields selected. Use the sidebar to choose measurements.', 'pinkcrab-weather-block' ) }
					</p>
				) }
			</div>
		</div>
	);
}
