/**
 * Icon registry for weather measurement types.
 *
 * Provides access to 3 icon sets: outline, filled, illustrative.
 */
import outline from './outline';
import filled from './filled';
import illustrative from './illustrative';
import meteocons from './meteocons';

const sets = { outline, filled, illustrative, meteocons };

/**
 * Get a specific icon by set name and measurement type.
 *
 * @param {string} setName The icon set name (outline, filled, illustrative).
 * @param {string} type    The measurement type key.
 * @return {Function} The icon component, or default fallback.
 */
export function getIcon( setName, type ) {
	const set = sets[ setName ] || sets.outline;
	return set[ type ] || set.default;
}

/**
 * Get a full icon set by name.
 *
 * @param {string} setName The icon set name.
 * @return {Object} The icon set object.
 */
export function getIconSet( setName ) {
	return sets[ setName ] || sets.outline;
}

/**
 * Get all available icon set names.
 *
 * @return {Array} Array of icon set name strings.
 */
export function getIconSetNames() {
	return Object.keys( sets );
}
