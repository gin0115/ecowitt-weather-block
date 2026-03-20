/**
 * MeasurementChip — displays a single measurement with click-to-cycle units.
 *
 * Receives the full variants array and manages cycling internally.
 */
import useUnitCycle from '../hooks/useUnitCycle';
import { getIcon } from '../../../icons';
import { FIELD_ICON_MAP } from '../constants';

/**
 * Format a field key into a readable label.
 *
 * @param {string} key The field key.
 * @return {string} Formatted label.
 */
function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * MeasurementChip component.
 *
 * @param {Object}      props
 * @param {string}      props.fieldKey       The field key (e.g. 'temperature').
 * @param {Array}       props.variants       Array of { value, label, unit }.
 * @param {string|null} props.preferredUnit  The initially preferred unit.
 * @param {string}      props.iconSet        The icon set name.
 * @param {string}      props.groupIconType  Fallback icon type from the group.
 * @param {Object}      props.colorStyle     Optional inline style for per-measurement colours.
 */
export default function MeasurementChip( {
	fieldKey,
	variants,
	preferredUnit,
	iconSet,
	groupIconType,
	colorStyle,
} ) {
	const { currentVariant, cycle, hasMultipleUnits } = useUnitCycle( variants, preferredUnit );
	const iconType = FIELD_ICON_MAP[ fieldKey ] || groupIconType || 'default';
	const IconComponent = getIcon( iconSet, iconType );
	const label = formatLabel( fieldKey );

	const chipProps = {
		className: `materialike__chip${ hasMultipleUnits ? ' materialike__chip--cyclable' : '' }`,
		style: colorStyle || undefined,
	};

	if ( hasMultipleUnits ) {
		chipProps.onClick = cycle;
		chipProps.onKeyDown = ( e ) => {
			if ( e.key === 'Enter' || e.key === ' ' ) {
				e.preventDefault();
				cycle();
			}
		};
		chipProps.role = 'button';
		chipProps.tabIndex = 0;
		chipProps[ 'aria-label' ] = `${ label }: ${ currentVariant.value } ${ currentVariant.label || currentVariant.unit }. Click to change unit.`;
	}

	return (
		<div { ...chipProps }>
			<span className="materialike__chip-icon">
				<IconComponent className="materialike__icon" />
			</span>
			<span className="materialike__chip-label">{ label }</span>
			<span className="materialike__chip-value">
				{ currentVariant.value }
				{ currentVariant.label && (
					<span className="materialike__chip-unit">{ currentVariant.label }</span>
				) }
			</span>
			{ hasMultipleUnits && (
				<span className="materialike__chip-dots" aria-hidden="true">
					{ variants.map( ( v, i ) => (
						<span
							key={ v.unit }
							className={ `materialike__chip-dot${ v.unit === currentVariant.unit ? ' materialike__chip-dot--active' : '' }` }
						/>
					) ) }
				</span>
			) }
		</div>
	);
}
