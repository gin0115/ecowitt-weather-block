/**
 * GroupCard — renders a group of measurements in a Material Design card.
 */
import { getIcon } from '../../../icons';
import { GROUP_META } from '../constants';
import MeasurementChip from './MeasurementChip';

/**
 * Format a group key into a readable label.
 *
 * @param {string} key The group key.
 * @return {string} Formatted label.
 */
function formatLabel( key ) {
	return key
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
}

/**
 * GroupCard component.
 *
 * @param {Object} props
 * @param {string} props.groupKey     The group key (e.g. 'outdoor').
 * @param {Object} props.fields       Object of { fieldKey: { variants, preferredUnit } }.
 * @param {string} props.iconSet      The icon set name.
 * @param {Object} props.groupColors  Optional per-group colour overrides.
 * @param {Object} props.fieldColors  Optional per-measurement colour overrides.
 */
export default function GroupCard( { groupKey, fields, iconSet, groupColors, fieldColors } ) {
	const meta = GROUP_META[ groupKey ] || {
		iconType: 'default',
		label: formatLabel( groupKey ),
	};

	const IconComponent = getIcon( iconSet, meta.iconType );

	const groupStyle = groupColors
		? {
				'--m-group-bg': groupColors.background || undefined,
				'--m-group-text': groupColors.text || undefined,
				'--m-group-accent': groupColors.accent || undefined,
		  }
		: undefined;

	return (
		<div className="materialike__group" style={ groupStyle }>
			<div className="materialike__group-header">
				<span className="materialike__group-icon">
					<IconComponent className="materialike__icon" />
				</span>
				<span className="materialike__group-title">{ meta.label }</span>
			</div>
			<div className="materialike__group-body">
				{ Object.entries( fields ).map( ( [ fieldKey, fieldData ] ) => {
					const measurementKey = `${ groupKey }.${ fieldKey }`;
					const colorOverride = fieldColors?.[ measurementKey ];
					const colorStyle = colorOverride
						? {
								'--chip-text': colorOverride.text || undefined,
								'--chip-accent': colorOverride.accent || undefined,
						  }
						: undefined;

					return (
						<MeasurementChip
							key={ fieldKey }
							fieldKey={ fieldKey }
							variants={ fieldData.variants }
							preferredUnit={ fieldData.preferredUnit }
							iconSet={ iconSet }
							groupIconType={ meta.iconType }
							colorStyle={ colorStyle }
						/>
					);
				} ) }
			</div>
		</div>
	);
}
