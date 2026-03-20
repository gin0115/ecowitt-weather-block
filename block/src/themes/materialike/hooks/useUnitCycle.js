/**
 * Hook for cycling through unit variants on click.
 *
 * @param {Array}       variants      Array of { value, label, unit }.
 * @param {string|null} preferredUnit The initially preferred unit, or null.
 * @return {Object} { currentVariant, cycle, hasMultipleUnits }
 */
import { useState, useCallback, useMemo } from '@wordpress/element';

export default function useUnitCycle( variants, preferredUnit ) {
	const initialIndex = useMemo( () => {
		if ( preferredUnit ) {
			const idx = variants.findIndex( ( v ) => v.unit === preferredUnit );
			if ( idx !== -1 ) {
				return idx;
			}
		}
		return 0;
	}, [ variants, preferredUnit ] );

	const [ index, setIndex ] = useState( initialIndex );

	const cycle = useCallback( () => {
		setIndex( ( prev ) => ( prev + 1 ) % variants.length );
	}, [ variants.length ] );

	const hasMultipleUnits = variants.length > 1;
	const currentVariant = variants[ index ] || variants[ 0 ];

	return { currentVariant, cycle, hasMultipleUnits };
}
