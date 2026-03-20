/**
 * DashboardCard — glassmorphism card with two layout modes:
 *
 * 'hero'  — first priority field shown large, rest in 2-column sub-grid.
 * 'equal' — all fields shown at the same hero size.
 */
import useUnitCycle from '../../materialike/hooks/useUnitCycle';
import { getIcon } from '../../../icons';
import { GROUP_META, FIELD_ICON_MAP } from '../../materialike/constants';

/**
 * Priority order for selecting the "hero" field per group.
 */
const PRIMARY_FIELDS = {
	outdoor: [ 'temperature', 'feels_like', 'dew_point', 'app_temp' ],
	indoor: [ 'temperature', 'humidity' ],
	wind: [ 'wind_speed', 'wind_gust', 'wind_direction' ],
	pressure: [ 'pressure_relative', 'pressure_absolute' ],
	rainfall: [ 'rain_rate', 'rain_daily', 'rain_event', 'rain_hourly', 'rain_weekly', 'rain_monthly', 'rain_yearly', 'rain_total' ],
	rainfall_piezo: [ 'rain_rate', 'rain_daily', 'rain_event', 'rain_hourly', 'rain_weekly', 'rain_monthly', 'rain_yearly', 'rain_total' ],
	solar_and_uvi: [ 'solar', 'uvi' ],
	lightning: [ 'lightning_count', 'lightning_distance', 'lightning_time' ],
	indoor_co2: [ 'co2', 'co2_24h', 'temperature', 'humidity' ],
	co2_aqi_combo: [ 'co2', 'aqi' ],
	pm25_aqi_combo: [ 'pm25', 'aqi' ],
	pm10_aqi_combo: [ 'pm10', 'aqi' ],
	battery: [ 'battery' ],
	water_leak: [ 'leak' ],
};

function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * Resolve icon type for a field, with dynamic UV index support.
 * When fieldKey is 'uv_index' and we have a numeric value, use the
 * numbered icon (uv_index_1 through uv_index_11). Falls back to
 * clear_day if value is 0 or unavailable.
 */
function resolveIconType( fieldKey, groupIconType, value ) {
	if ( fieldKey === 'uv_index' || fieldKey === 'uvi' ) {
		const num = Math.round( parseFloat( value ) );
		if ( num >= 1 && num <= 11 ) {
			return `uv_index_${ num }`;
		}
		return 'clear_day';
	}
	return FIELD_ICON_MAP[ fieldKey ] || groupIconType || 'default';
}

/**
 * Hero-sized measurement display — large value with big icon.
 */
function HeroField( { fieldKey, variants, preferredUnit, iconSet, groupIconType } ) {
	const { currentVariant, cycle, hasMultipleUnits } = useUnitCycle( variants, preferredUnit );
	const iconType = resolveIconType( fieldKey, groupIconType, currentVariant.value );
	const IconComponent = getIcon( iconSet, iconType );
	const label = formatLabel( fieldKey );

	const props = {
		className: `db-card__hero${ hasMultipleUnits ? ' db-card__hero--cyclable' : '' }`,
	};

	if ( hasMultipleUnits ) {
		props.onClick = cycle;
		props.onKeyDown = ( e ) => {
			if ( e.key === 'Enter' || e.key === ' ' ) {
				e.preventDefault();
				cycle();
			}
		};
		props.role = 'button';
		props.tabIndex = 0;
		props[ 'aria-label' ] = `${ label }: ${ currentVariant.value } ${ currentVariant.label || currentVariant.unit }. Click to change unit.`;
	}

	return (
		<div { ...props }>
			<div className="db-card__hero-top">
				<span className="db-card__hero-label">{ label }</span>
				{ hasMultipleUnits && (
					<span className="db-card__dots" aria-hidden="true">
						{ variants.map( ( v ) => (
							<span
								key={ v.unit }
								className={ `db-card__dot${ v.unit === currentVariant.unit ? ' db-card__dot--active' : '' }` }
							/>
						) ) }
					</span>
				) }
			</div>
			<div className="db-card__hero-content">
				<span className="db-card__hero-icon">
					<IconComponent className="db-card__icon--lg" />
				</span>
				<div className="db-card__hero-reading">
					<span className="db-card__hero-value">{ currentVariant.value }</span>
					<span className="db-card__hero-unit">{ currentVariant.label || currentVariant.unit }</span>
				</div>
			</div>
		</div>
	);
}

/**
 * Compact measurement for the 2-column secondary grid.
 */
function SecondaryField( { fieldKey, variants, preferredUnit, iconSet, groupIconType } ) {
	const { currentVariant, cycle, hasMultipleUnits } = useUnitCycle( variants, preferredUnit );
	const iconType = resolveIconType( fieldKey, groupIconType, currentVariant.value );
	const IconComponent = getIcon( iconSet, iconType );
	const label = formatLabel( fieldKey );

	const props = {
		className: `db-card__cell${ hasMultipleUnits ? ' db-card__cell--cyclable' : '' }`,
	};

	if ( hasMultipleUnits ) {
		props.onClick = cycle;
		props.onKeyDown = ( e ) => {
			if ( e.key === 'Enter' || e.key === ' ' ) {
				e.preventDefault();
				cycle();
			}
		};
		props.role = 'button';
		props.tabIndex = 0;
		props[ 'aria-label' ] = `${ label }: ${ currentVariant.value } ${ currentVariant.label || currentVariant.unit }. Click to change unit.`;
	}

	return (
		<div { ...props }>
			<div className="db-card__cell-top">
				<span className="db-card__cell-icon">
					<IconComponent className="db-card__icon--md" />
				</span>
				{ hasMultipleUnits && (
					<span className="db-card__dots" aria-hidden="true">
						{ variants.map( ( v ) => (
							<span
								key={ v.unit }
								className={ `db-card__dot${ v.unit === currentVariant.unit ? ' db-card__dot--active' : '' }` }
							/>
						) ) }
					</span>
				) }
			</div>
			<span className="db-card__cell-value">
				{ currentVariant.value }
				<span className="db-card__cell-unit">{ currentVariant.label || currentVariant.unit }</span>
			</span>
			<span className="db-card__cell-label">{ label }</span>
		</div>
	);
}

/**
 * DashboardCard component.
 *
 * @param {string} layout         'hero' (primary + secondaries) or 'equal' (all same size).
 * @param {string} labelOverride  Optional label override (for split-out fields).
 * @param {string} iconOverride   Optional icon type override.
 */
export default function DashboardCard( { groupKey, fields, iconSet, layout = 'hero', labelOverride, iconOverride } ) {
	// Strip split suffix for GROUP_META lookup (e.g. 'outdoor__humidity' → 'outdoor').
	const baseGroupKey = groupKey.split( '__' )[ 0 ];
	const meta = GROUP_META[ baseGroupKey ] || {
		iconType: 'default',
		label: formatLabel( baseGroupKey ),
	};

	const cardLabel = labelOverride || meta.label;
	const cardIconType = iconOverride || meta.iconType;
	const CardIcon = getIcon( iconSet, cardIconType );

	const fieldEntries = Object.entries( fields );

	// Single field: always use hero layout for full-size display.
	if ( fieldEntries.length === 1 ) {
		const [ fieldKey, fieldData ] = fieldEntries[ 0 ];
		return (
			<div className="db-card">
				<div className="db-card__header">
					<span className="db-card__header-icon">
						<CardIcon className="db-card__icon--header" />
					</span>
					<span className="db-card__title">{ cardLabel }</span>
				</div>
				<HeroField
					fieldKey={ fieldKey }
					variants={ fieldData.variants }
					preferredUnit={ fieldData.preferredUnit }
					iconSet={ iconSet }
					groupIconType={ cardIconType }
				/>
			</div>
		);
	}

	// Equal layout: all fields shown at hero size.
	if ( layout === 'equal' ) {
		return (
			<div className="db-card db-card--equal">
				<div className="db-card__header">
					<span className="db-card__header-icon">
						<CardIcon className="db-card__icon--header" />
					</span>
					<span className="db-card__title">{ cardLabel }</span>
				</div>
				<div className="db-card__equal-grid">
					{ fieldEntries.map( ( [ fieldKey, fieldData ] ) => (
						<HeroField
							key={ fieldKey }
							fieldKey={ fieldKey }
							variants={ fieldData.variants }
							preferredUnit={ fieldData.preferredUnit }
							iconSet={ iconSet }
							groupIconType={ cardIconType }
						/>
					) ) }
				</div>
			</div>
		);
	}

	// Hero layout: first priority field as hero, rest in 2-column grid.
	const priorities = PRIMARY_FIELDS[ baseGroupKey ] || [];
	let primaryKey = null;

	for ( const key of priorities ) {
		if ( fields[ key ] ) {
			primaryKey = key;
			break;
		}
	}

	if ( ! primaryKey && fieldEntries.length > 0 ) {
		primaryKey = fieldEntries[ 0 ][ 0 ];
	}

	const primaryData = primaryKey ? fields[ primaryKey ] : null;
	const secondaryEntries = fieldEntries.filter( ( [ key ] ) => key !== primaryKey );

	return (
		<div className="db-card">
			<div className="db-card__header">
				<span className="db-card__header-icon">
					<CardIcon className="db-card__icon--header" />
				</span>
				<span className="db-card__title">{ cardLabel }</span>
			</div>

			{ primaryData && (
				<HeroField
					fieldKey={ primaryKey }
					variants={ primaryData.variants }
					preferredUnit={ primaryData.preferredUnit }
					iconSet={ iconSet }
					groupIconType={ cardIconType }
				/>
			) }

			{ secondaryEntries.length > 0 && (
				<div className="db-card__grid">
					{ secondaryEntries.map( ( [ fieldKey, fieldData ] ) => (
						<SecondaryField
							key={ fieldKey }
							fieldKey={ fieldKey }
							variants={ fieldData.variants }
							preferredUnit={ fieldData.preferredUnit }
							iconSet={ iconSet }
							groupIconType={ cardIconType }
						/>
					) ) }
				</div>
			) }
		</div>
	);
}
