/**
 * Hook for fetching historical weather observations from the REST API.
 *
 * Used in both the editor preview and frontend view.js for history mode.
 */
import { useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

const API_NAMESPACE = '/ecowitt-weather/v1';

/**
 * Hook to fetch and manage historical weather data.
 *
 * @return {Object} State and handlers for historical weather data.
 */
export default function useHistoryWeather() {
	const [ data, setData ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( false );
	const [ error, setError ] = useState( null );
	const [ meta, setMeta ] = useState( null );

	/**
	 * Fetch historical observation data.
	 *
	 * @param {string} connectionKey The connection key.
	 * @param {string} mac           The device MAC address.
	 * @param {string} from          Start date in ISO 8601 format.
	 * @param {string} to            End date in ISO 8601 format (optional, defaults to now).
	 * @param {string} cycleType     Aggregation interval (optional, auto-selects if empty).
	 */
	const fetchHistory = useCallback( async ( connectionKey, mac, from, to = '', cycleType = '' ) => {
		if ( ! connectionKey || ! mac || ! from ) {
			return;
		}

		setIsLoading( true );
		setError( null );

		const params = new URLSearchParams( {
			connection: connectionKey,
			mac,
			from,
		} );

		if ( to ) {
			params.set( 'to', to );
		}

		if ( cycleType && cycleType !== 'auto' ) {
			params.set( 'cycle_type', cycleType );
		}

		try {
			const response = await apiFetch( {
				path: `${ API_NAMESPACE }/history?${ params.toString() }`,
			} );

			setData( response.observation || null );
			setMeta( {
				mac: response.mac,
				from: response.from,
				to: response.to,
				cycleType: response.cycle_type,
			} );
		} catch ( err ) {
			setError( err.message || 'Failed to load weather history.' );
			setData( null );
			setMeta( null );
		} finally {
			setIsLoading( false );
		}
	}, [] );

	return {
		data,
		isLoading,
		error,
		meta,
		fetchHistory,
	};
}
