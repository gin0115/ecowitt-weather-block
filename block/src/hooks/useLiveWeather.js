/**
 * Hook for fetching live weather observations from the REST API.
 *
 * Used in both the editor preview and frontend view.js.
 */
import { useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

const API_NAMESPACE = '/ecowitt-weather/v1';

/**
 * Hook to fetch and manage live weather data.
 *
 * @return {Object} State and handlers for live weather data.
 */
export default function useLiveWeather() {
	const [ data, setData ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( false );
	const [ error, setError ] = useState( null );

	/**
	 * Fetch live observation data.
	 *
	 * @param {string} connectionKey The connection key.
	 * @param {string} mac           The device MAC address.
	 */
	const fetchLiveData = useCallback( async ( connectionKey, mac ) => {
		if ( ! connectionKey || ! mac ) {
			return;
		}

		setIsLoading( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: `${ API_NAMESPACE }/live?connection=${ encodeURIComponent( connectionKey ) }&mac=${ encodeURIComponent( mac ) }`,
			} );
			setData( response.observation || null );
		} catch ( err ) {
			setError( err.message || 'Failed to load weather data.' );
			setData( null );
		} finally {
			setIsLoading( false );
		}
	}, [] );

	return {
		data,
		isLoading,
		error,
		fetchLiveData,
	};
}
