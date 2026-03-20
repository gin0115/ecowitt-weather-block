/**
 * Hook for fetching connections and devices from the REST API.
 *
 * Used in the editor to let users pick a station (connection + device).
 */
import { useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

const API_NAMESPACE = '/ecowitt-weather/v1';

/**
 * Hook to manage connection and device selection.
 *
 * @return {Object} State and handlers for connection/device picking.
 */
export default function useConnections() {
	const [ connections, setConnections ] = useState( [] );
	const [ devices, setDevices ] = useState( [] );
	const [ isLoadingConnections, setIsLoadingConnections ] = useState( false );
	const [ isLoadingDevices, setIsLoadingDevices ] = useState( false );
	const [ error, setError ] = useState( null );

	/**
	 * Fetch all configured connections.
	 */
	const fetchConnections = useCallback( async () => {
		setIsLoadingConnections( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: `${ API_NAMESPACE }/connections`,
			} );
			setConnections( response.connections || [] );
		} catch ( err ) {
			setError( err.message || 'Failed to load connections.' );
			setConnections( [] );
		} finally {
			setIsLoadingConnections( false );
		}
	}, [] );

	/**
	 * Fetch devices for a given connection key.
	 *
	 * @param {string} connectionKey The connection key.
	 */
	const fetchDevices = useCallback( async ( connectionKey ) => {
		setIsLoadingDevices( true );
		setError( null );
		setDevices( [] );

		try {
			const response = await apiFetch( {
				path: `${ API_NAMESPACE }/devices?connection=${ encodeURIComponent( connectionKey ) }`,
			} );
			setDevices( response.devices || [] );
		} catch ( err ) {
			setError( err.message || 'Failed to load devices.' );
			setDevices( [] );
		} finally {
			setIsLoadingDevices( false );
		}
	}, [] );

	return {
		connections,
		devices,
		isLoadingConnections,
		isLoadingDevices,
		error,
		fetchConnections,
		fetchDevices,
	};
}
