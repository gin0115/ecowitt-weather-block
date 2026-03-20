/**
 * Theme registry for weather block rendering.
 *
 * Maps theme names to React components for both live and history modes.
 */
import DefaultTheme from './default';
import MaterialikeTheme from './materialike';
import DashboardTheme from './dashboard';
import SlateTheme from './slate';
import DefaultHistoryTheme from './default/history';
import MaterialikeHistoryTheme from './materialike/history';
import DashboardHistoryTheme from './dashboard/history';
import SlateHistoryTheme from './slate/history';

const themes = {
	default: DefaultTheme,
	materialike: MaterialikeTheme,
	dashboard: DashboardTheme,
	slate: SlateTheme,
};

const historyThemes = {
	default: DefaultHistoryTheme,
	materialike: MaterialikeHistoryTheme,
	dashboard: DashboardHistoryTheme,
	slate: SlateHistoryTheme,
};

/**
 * Get a live theme component by name.
 *
 * @param {string} themeName The theme name.
 * @return {Function} The React component for the theme.
 */
export function getTheme( themeName ) {
	return themes[ themeName ] || themes.default;
}

/**
 * Get a history theme component by name.
 *
 * @param {string} themeName The theme name.
 * @return {Function} The React component for the history theme.
 */
export function getHistoryTheme( themeName ) {
	return historyThemes[ themeName ] || historyThemes.default;
}

/**
 * Get all available theme names.
 *
 * @return {Array} Array of theme name strings.
 */
export function getThemeNames() {
	return Object.keys( themes );
}
