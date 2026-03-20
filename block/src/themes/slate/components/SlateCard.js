/**
 * SlateCard — renders a group of measurements with layout variants.
 *
 * Layout types:
 * - 'split'     — hero left, sidebar right, optional footer row below divider.
 * - 'hero-grid' — hero top, 3-column grid below divider.
 * - 'compass'   — wind compass left, speed + gust right.
 * - 'dots'      — status indicator dots (water leak).
 */
import useUnitCycle from '../../materialike/hooks/useUnitCycle';
import { GROUP_META, FIELD_ICON_MAP } from '../../materialike/constants';
import WindCompass from './WindCompass';

function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * Unit toggle pill — displays the current unit and cycles on click.
 */
function UnitToggle( { currentVariant, cycle, hasMultipleUnits, size = 'md' } ) {
	if ( ! hasMultipleUnits ) {
		return (
			<span className={ `slate-unit slate-unit--${ size }` }>
				{ currentVariant.label || currentVariant.unit }
			</span>
		);
	}

	return (
		<button
			type="button"
			className={ `slate-unit slate-unit--toggle slate-unit--${ size }` }
			onClick={ cycle }
			aria-label={ `Unit: ${ currentVariant.label || currentVariant.unit }. Click to change.` }
		>
			{ currentVariant.label || currentVariant.unit }
		</button>
	);
}

/**
 * Displays a measurement value with unit toggle.
 */
function MeasurementValue( { variants, preferredUnit, size = 'hero' } ) {
	const { currentVariant, cycle, hasMultipleUnits } = useUnitCycle( variants, preferredUnit );
	const unitSize = size === 'hero' ? 'md' : 'sm';

	return (
		<span className={ `slate-value slate-value--${ size }` }>
			{ currentVariant.value }
			<UnitToggle currentVariant={ currentVariant } cycle={ cycle } hasMultipleUnits={ hasMultipleUnits } size={ unitSize } />
		</span>
	);
}

/**
 * Split layout — primary big left, secondary details right, optional footer.
 */
function SplitLayout( { fields, primaryField, footerFields } ) {
	const primaryKey = primaryField && fields[ primaryField ] ? primaryField : Object.keys( fields )[ 0 ];
	const primary = fields[ primaryKey ];

	const sidebarEntries = Object.entries( fields ).filter(
		( [ key ] ) => key !== primaryKey && ! footerFields.includes( key )
	);

	const footerEntries = Object.entries( fields ).filter(
		( [ key ] ) => key !== primaryKey && footerFields.includes( key )
	);

	return (
		<>
			<div className="slate-card__body">
				<div className="slate-card__primary">
					<MeasurementValue
						variants={ primary.variants }
						preferredUnit={ primary.preferredUnit }
						size="hero"
					/>
					{ primaryKey !== primaryField && Object.keys( fields ).length > 1 && (
						<p className="slate-card__hero-sublabel">{ formatLabel( primaryKey ) }</p>
					) }
				</div>
				{ sidebarEntries.length > 0 && (
					<div className="slate-card__sidebar">
						{ sidebarEntries.map( ( [ key, field ] ) => (
							<div key={ key } className="slate-card__sidebar-item">
								<span className="slate-card__sidebar-label">{ formatLabel( key ) }</span>
								<MeasurementValue
									variants={ field.variants }
									preferredUnit={ field.preferredUnit }
									size="sm"
								/>
							</div>
						) ) }
					</div>
				) }
			</div>
			{ footerEntries.length > 0 && (
				<div className="slate-card__footer">
					{ footerEntries.map( ( [ key, field ] ) => (
						<div key={ key } className="slate-card__footer-item">
							<span className="slate-card__footer-label">{ formatLabel( key ) }:</span>
							<MeasurementValue
								variants={ field.variants }
								preferredUnit={ field.preferredUnit }
								size="sm"
							/>
						</div>
					) ) }
				</div>
			) }
		</>
	);
}

/**
 * Hero-grid layout — primary big top, 3-column grid below (rainfall).
 */
function HeroGridLayout( { fields, primaryField, heroSidebarFields = [] } ) {
	const primaryKey = primaryField && fields[ primaryField ] ? primaryField : Object.keys( fields )[ 0 ];
	const primary = fields[ primaryKey ];

	const sidebarEntries = Object.entries( fields )
		.filter( ( [ key ] ) => key !== primaryKey && heroSidebarFields.includes( key ) )
		.sort( ( a, b ) => heroSidebarFields.indexOf( a[ 0 ] ) - heroSidebarFields.indexOf( b[ 0 ] ) );

	const gridEntries = Object.entries( fields ).filter(
		( [ key ] ) => key !== primaryKey && ! heroSidebarFields.includes( key )
	);

	return (
		<>
			<div className="slate-card__body">
				<div className="slate-card__primary">
					<MeasurementValue
						variants={ primary.variants }
						preferredUnit={ primary.preferredUnit }
						size="hero"
					/>
					<p className="slate-card__hero-sublabel">{ formatLabel( primaryKey ) }</p>
				</div>
				{ sidebarEntries.length > 0 && (
					<div className="slate-card__sidebar">
						{ sidebarEntries.map( ( [ key, field ] ) => (
							<div key={ key } className="slate-card__sidebar-item">
								<span className="slate-card__sidebar-label">{ formatLabel( key ) }</span>
								<MeasurementValue
									variants={ field.variants }
									preferredUnit={ field.preferredUnit }
									size="sm"
								/>
							</div>
						) ) }
					</div>
				) }
			</div>
			{ gridEntries.length > 0 && (
				<div className="slate-card__footer">
					{ gridEntries.map( ( [ key, field ] ) => (
						<div key={ key } className="slate-card__footer-item">
							<span className="slate-card__footer-label">{ formatLabel( key ) }</span>
							<MeasurementValue
								variants={ field.variants }
								preferredUnit={ field.preferredUnit }
								size="sm"
							/>
						</div>
					) ) }
				</div>
			) }
		</>
	);
}

/**
 * Compass layout — wind compass left, speed + gust right.
 */
function CompassLayout( { fields } ) {
	const directionField = fields.wind_direction;
	const speedField = fields.wind_speed;
	const gustField = fields.wind_gust;

	// Always use the deg variant for compass rotation, regardless of preferred unit.
	const degVariant = directionField
		? directionField.variants.find( ( v ) => v.unit === 'deg' ) || directionField.variants[ 0 ]
		: { value: '0', unit: 'deg' };

	const degrees = parseFloat( degVariant.value ) || 0;
	const cardinalDirections = [
		'N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE',
		'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW',
	];
	const cardinalIndex = Math.round( degrees / 22.5 ) % 16;
	const cardinal = cardinalDirections[ cardinalIndex ];

	return (
		<div className="slate-card__body">
			<div className="slate-card__compass-wrap">
				<WindCompass degrees={ degrees } cardinal={ cardinal } />
			</div>
			<div className="slate-card__wind-details">
				{ speedField && (
					<MeasurementValue
						variants={ speedField.variants }
						preferredUnit={ speedField.preferredUnit }
						size="hero"
					/>
				) }
				{ gustField && (
					<div className="slate-card__wind-gust">
						<span className="slate-card__sidebar-label">Gust:</span>
						<MeasurementValue
							variants={ gustField.variants }
							preferredUnit={ gustField.preferredUnit }
							size="sm"
						/>
					</div>
				) }
			</div>
		</div>
	);
}

/**
 * Dots layout — status indicator dots for water leak channels.
 */
function DotsLayout( { fields } ) {
	return (
		<div className="slate-card__dots-grid">
			{ Object.entries( fields ).map( ( [ key, field ] ) => {
				const { currentVariant } = useUnitCycle( field.variants, field.preferredUnit );
				const isLeaking = parseFloat( currentVariant.value ) > 0;
				const dotClass = isLeaking ? 'slate-card__dot--alert' : 'slate-card__dot--ok';
				const label = key.replace( 'leak_', '' ).toUpperCase();

				return (
					<div key={ key } className="slate-card__dot-item">
						<div className={ `slate-card__dot ${ dotClass }` } />
						<span className="slate-card__dot-label">{ label }</span>
					</div>
				);
			} ) }
		</div>
	);
}

/**
 * SlateCard component.
 */
export default function SlateCard( { groupKey, fields, layout, primaryField, footerFields, heroSidebarFields = [], iconSet } ) {
	const meta = GROUP_META[ groupKey ] || {
		iconType: 'default',
		label: formatLabel( groupKey ),
	};

	return (
		<section className="slate-card">
			<h2 className="slate-card__title">{ meta.label }</h2>

			{ layout === 'split' && (
				<SplitLayout
					fields={ fields }
					primaryField={ primaryField }
					footerFields={ footerFields }
				/>
			) }

			{ layout === 'hero-grid' && (
				<HeroGridLayout
					fields={ fields }
					primaryField={ primaryField }
					heroSidebarFields={ heroSidebarFields }
				/>
			) }

			{ layout === 'compass' && (
				<CompassLayout fields={ fields } />
			) }

			{ layout === 'dots' && (
				<DotsLayout fields={ fields } />
			) }
		</section>
	);
}
