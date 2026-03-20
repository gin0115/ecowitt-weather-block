/**
 * Editor component for Ecowitt Weather block (live + history).
 *
 * Two phases:
 * 1. Setup — pick a connection and device (station).
 * 2. Configure — choose fields and theme, preview data.
 *
 * When mode is 'history', shows additional controls for
 * default range, cycle type, and auto-refresh interval.
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import {
	useBlockProps,
	InspectorControls,
	ColorPalette as WPColorPalette,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	CheckboxControl,
	Button,
	Spinner,
	Placeholder,
	TextControl,
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';

import './editor.scss';
import useConnections from './hooks/useConnections';
import useLiveWeather from './hooks/useLiveWeather';
import useHistoryWeather from './hooks/useHistoryWeather';
import { getTheme, getHistoryTheme, getThemeNames } from './themes/registry';
import { getIconSetNames } from './icons';
import { computeDateRange } from './history/chartUtils';
import RangeControls from './history/RangeControls';

/**
 * Known observation groups and their human labels.
 */
const OBSERVATION_GROUPS = {
	outdoor: __( 'Outdoor', 'pinkcrab-weather-block' ),
	indoor: __( 'Indoor', 'pinkcrab-weather-block' ),
	wind: __( 'Wind', 'pinkcrab-weather-block' ),
	pressure: __( 'Pressure', 'pinkcrab-weather-block' ),
	rainfall: __( 'Rainfall', 'pinkcrab-weather-block' ),
	solar_and_uvi: __( 'Solar & UV', 'pinkcrab-weather-block' ),
	lightning: __( 'Lightning', 'pinkcrab-weather-block' ),
	indoor_co2: __( 'Indoor CO2', 'pinkcrab-weather-block' ),
	co2_aqi_combo: __( 'CO2 AQI', 'pinkcrab-weather-block' ),
	pm25_aqi_combo: __( 'PM2.5 AQI', 'pinkcrab-weather-block' ),
	pm10_aqi_combo: __( 'PM10 AQI', 'pinkcrab-weather-block' ),
	battery: __( 'Battery', 'pinkcrab-weather-block' ),
};

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
 * Check if a value is a CSS variable reference.
 *
 * @param {string} value
 * @return {boolean}
 */
function isCssVariable( value ) {
	return typeof value === 'string' && value.startsWith( 'var(' );
}

/**
 * Compact colour input that accepts hex values or CSS variables.
 * Shows a small colour swatch + text input on one line.
 */
function ColorVariableInput( { label, value, onChange } ) {
	const [ showPicker, setShowPicker ] = useState( false );
	const isVar = isCssVariable( value );

	return (
		<div className="ecowitt-color-input">
			<div className="ecowitt-color-input__row">
				<button
					type="button"
					className="ecowitt-color-input__swatch"
					style={ {
						backgroundColor: isVar ? '#ddd' : ( value || '#ddd' ),
					} }
					onClick={ () => setShowPicker( ! showPicker ) }
					aria-label={ __( 'Pick colour', 'pinkcrab-weather-block' ) }
					title={ isVar ? value : __( 'Pick colour', 'pinkcrab-weather-block' ) }
				>
					{ isVar && <span className="ecowitt-color-input__var-icon">V</span> }
				</button>
				<TextControl
					label={ label }
					value={ value || '' }
					onChange={ onChange }
					placeholder="var(--color) or #hex"
					__nextHasNoMarginBottom
					className="ecowitt-color-input__text"
				/>
				{ value && (
					<Button
						isSmall
						variant="tertiary"
						onClick={ () => onChange( '' ) }
						className="ecowitt-color-input__clear"
						aria-label={ __( 'Clear', 'pinkcrab-weather-block' ) }
					>
						&times;
					</Button>
				) }
			</div>
			{ showPicker && ! isVar && (
				<div className="ecowitt-color-input__picker">
					<WPColorPalette
						value={ value }
						onChange={ ( newVal ) => {
							onChange( newVal );
							setShowPicker( false );
						} }
						clearable={ false }
					/>
				</div>
			) }
		</div>
	);
}

/**
 * Setup placeholder — connection and device selection.
 */
function SetupPlaceholder( { attributes, setAttributes } ) {
	const {
		connections,
		devices,
		isLoadingConnections,
		isLoadingDevices,
		error,
		fetchConnections,
		fetchDevices,
	} = useConnections();

	// Fetch connections on mount.
	useEffect( () => {
		fetchConnections();
	}, [ fetchConnections ] );

	const handleConnectionChange = ( key ) => {
		setAttributes( { connectionKey: key, mac: '', stationName: '' } );
		if ( key ) {
			fetchDevices( key );
		}
	};

	const handleDeviceSelect = ( mac, name ) => {
		setAttributes( { mac, stationName: name || mac } );
	};

	return (
		<Placeholder
			icon={ attributes.mode === 'history' ? 'chart-line' : 'cloud' }
			label={ attributes.mode === 'history'
				? __( 'Ecowitt Weather History', 'pinkcrab-weather-block' )
				: __( 'Ecowitt Live Weather', 'pinkcrab-weather-block' )
			}
			instructions={ __( 'Select a weather station to display.', 'pinkcrab-weather-block' ) }
		>
			{ error && <p className="ecowitt-weather-live__error">{ error }</p> }

			{ isLoadingConnections ? (
				<Spinner />
			) : (
				<SelectControl
					label={ __( 'Connection', 'pinkcrab-weather-block' ) }
					value={ attributes.connectionKey }
					options={ [
						{ label: __( '— Select connection —', 'pinkcrab-weather-block' ), value: '' },
						...connections.map( ( conn ) => ( {
							label: conn.name || conn.key,
							value: conn.key,
						} ) ),
					] }
					onChange={ handleConnectionChange }
				/>
			) }

			{ attributes.connectionKey && (
				<>
					{ isLoadingDevices ? (
						<Spinner />
					) : (
						devices.length > 0 && (
							<SelectControl
								label={ __( 'Device', 'pinkcrab-weather-block' ) }
								value={ attributes.mac }
								options={ [
									{ label: __( '— Select device —', 'pinkcrab-weather-block' ), value: '' },
									...devices.map( ( device ) => ( {
										label: device.name || device.mac,
										value: device.mac,
									} ) ),
								] }
								onChange={ ( mac ) => {
									const device = devices.find( ( d ) => d.mac === mac );
									handleDeviceSelect( mac, device?.name );
								} }
							/>
						)
					) }
				</>
			) }
		</Placeholder>
	);
}

/**
 * Field picker sidebar — checkboxes grouped by observation group.
 *
 * selectedFields shape: { group: { fieldKey: preferredUnit|null } }
 * data shape: { group: { fieldKey: [ { value, label, unit }, ... ] } }
 */
function FieldPicker( { data, selectedFields, onChange, isLoading } ) {
	if ( isLoading ) {
		return (
			<PanelBody
				title={ __( 'Fields', 'pinkcrab-weather-block' ) }
				initialOpen={ true }
			>
				<Spinner />
				<p>{ __( 'Loading observation data…', 'pinkcrab-weather-block' ) }</p>
			</PanelBody>
		);
	}

	if ( ! data ) {
		return null;
	}

	return (
		<>
			{ Object.entries( OBSERVATION_GROUPS ).map( ( [ groupKey, groupLabel ] ) => {
				const groupData = data[ groupKey ];
				if ( ! groupData || Object.keys( groupData ).length === 0 ) {
					return null;
				}

				const groupSelected = selectedFields[ groupKey ] || {};

				return (
					<PanelBody
						key={ groupKey }
						title={ groupLabel }
						initialOpen={ false }
					>
						{ Object.entries( groupData ).map( ( [ fieldKey, fieldValue ] ) => {
							const isSelected = fieldKey in groupSelected;

							// Live data: fieldValue is array of variants [{ value, label, unit }, ...]
							// History data: fieldValue is { type, variants: [{ unit, label, data }] }
							const isLiveVariants = Array.isArray( fieldValue );
							const unitOptions = isLiveVariants
								? fieldValue
								: ( fieldValue.variants || [] );
							const hasMultipleUnits = unitOptions.length > 1;

							return (
								<div key={ fieldKey } className="ecowitt-weather-live__field-row">
									<CheckboxControl
										label={ formatLabel( fieldKey ) }
										checked={ isSelected }
										onChange={ ( checked ) => {
											const updated = { ...selectedFields };
											if ( checked ) {
												if ( ! updated[ groupKey ] ) {
													updated[ groupKey ] = {};
												}
												updated[ groupKey ] = {
													...updated[ groupKey ],
													[ fieldKey ]: null,
												};
											} else {
												if ( updated[ groupKey ] ) {
													const { [ fieldKey ]: removed, ...rest } = updated[ groupKey ];
													if ( Object.keys( rest ).length === 0 ) {
														delete updated[ groupKey ];
													} else {
														updated[ groupKey ] = rest;
													}
												}
											}
											onChange( updated );
										} }
									/>
									{ isSelected && hasMultipleUnits && (
										<SelectControl
											label={ __( 'Unit', 'pinkcrab-weather-block' ) }
											value={ groupSelected[ fieldKey ] || unitOptions[ 0 ].unit }
											options={ unitOptions.map( ( v ) => ( {
												label: `${ v.label } (${ v.unit })`,
												value: v.unit,
											} ) ) }
											onChange={ ( unit ) => {
												const updated = { ...selectedFields };
												updated[ groupKey ] = {
													...updated[ groupKey ],
													[ fieldKey ]: unit,
												};
												onChange( updated );
											} }
											__nextHasNoMarginBottom
										/>
									) }
								</div>
							);
						} ) }
					</PanelBody>
				);
			} ) }
		</>
	);
}

/**
 * Main edit component.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const {
		connectionKey, mac, stationName, selectedFields,
		theme, colors, iconSet, mode,
		defaultRange, cycleType, autoRefreshInterval,
	} = attributes;
	const hasStation = connectionKey && mac;
	const isHistory = mode === 'history';

	// Live data hook.
	const { data: liveData, isLoading: liveLoading, error: liveError, fetchLiveData } = useLiveWeather();

	// History data hook.
	const { data: historyData, isLoading: historyLoading, error: historyError, fetchHistory } = useHistoryWeather();

	// Editor range state for history preview.
	const [ editorRange, setEditorRange ] = useState( defaultRange || '24h' );
	const [ editorCustomFrom, setEditorCustomFrom ] = useState( '' );
	const [ editorCustomTo, setEditorCustomTo ] = useState( '' );

	// Fetch live data when station is configured (live mode).
	useEffect( () => {
		if ( hasStation && ! isHistory ) {
			fetchLiveData( connectionKey, mac );
		}
	}, [ connectionKey, mac, hasStation, isHistory, fetchLiveData ] );

	// Fetch history data when station is configured (history mode).
	useEffect( () => {
		if ( hasStation && isHistory ) {
			const { from, to } = computeDateRange( editorRange, editorCustomFrom, editorCustomTo );
			const cycle = cycleType === 'auto' ? '' : cycleType;
			fetchHistory( connectionKey, mac, from, to, cycle );
		}
	}, [ connectionKey, mac, hasStation, isHistory, editorRange, editorCustomFrom, editorCustomTo, cycleType, fetchHistory ] );

	// If no station selected, show setup.
	if ( ! hasStation ) {
		return (
			<div { ...blockProps }>
				<SetupPlaceholder
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</div>
		);
	}

	// Determine which data/state to use based on mode.
	const data = isHistory ? historyData : liveData;
	const isLoading = isHistory ? historyLoading : liveLoading;
	const error = isHistory ? historyError : liveError;

	// Select the appropriate theme component.
	const ThemeComponent = isHistory ? getHistoryTheme( theme ) : getTheme( theme );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Station', 'pinkcrab-weather-block' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Station Title', 'pinkcrab-weather-block' ) }
						value={ stationName || '' }
						placeholder={ mac }
						onChange={ ( value ) => setAttributes( { stationName: value } ) }
					/>
					<Button
						variant="secondary"
						onClick={ () => setAttributes( { connectionKey: '', mac: '', stationName: '' } ) }
					>
						{ __( 'Change Station', 'pinkcrab-weather-block' ) }
					</Button>
					<Button
						variant="tertiary"
						onClick={ () => {
							if ( isHistory ) {
								const { from, to } = computeDateRange( editorRange, editorCustomFrom, editorCustomTo );
								const cycle = cycleType === 'auto' ? '' : cycleType;
								fetchHistory( connectionKey, mac, from, to, cycle );
							} else {
								fetchLiveData( connectionKey, mac );
							}
						} }
						style={ { marginLeft: '8px' } }
					>
						{ __( 'Refresh', 'pinkcrab-weather-block' ) }
					</Button>
				</PanelBody>

				<PanelBody
					title={ __( 'Theme', 'pinkcrab-weather-block' ) }
					initialOpen={ false }
				>
					<SelectControl
						label={ __( 'Display Theme', 'pinkcrab-weather-block' ) }
						value={ theme }
						options={ getThemeNames().map( ( name ) => ( {
							label: formatLabel( name ),
							value: name,
						} ) ) }
						onChange={ ( value ) => setAttributes( { theme: value } ) }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Icon Set', 'pinkcrab-weather-block' ) }
					initialOpen={ false }
				>
					<SelectControl
						label={ __( 'Icon Style', 'pinkcrab-weather-block' ) }
						value={ iconSet }
						options={ getIconSetNames().map( ( name ) => ( {
							label: formatLabel( name ),
							value: name,
						} ) ) }
						onChange={ ( value ) => setAttributes( { iconSet: value } ) }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Theme Colours', 'pinkcrab-weather-block' ) }
					initialOpen={ false }
				>
					{ [ 'background', 'text', 'text_muted', 'text_dim', 'accent', 'border', 'surface', 'success', 'alert' ].map( ( colorKey ) => (
						<ColorVariableInput
							key={ colorKey }
							label={ formatLabel( colorKey ) }
							value={ colors?.theme?.[ colorKey ] || '' }
							onChange={ ( value ) => {
								const updated = { ...colors };
								if ( ! updated.theme ) {
									updated.theme = {};
								}
								updated.theme = { ...updated.theme, [ colorKey ]: value };
								setAttributes( { colors: updated } );
							} }
						/>
					) ) }
				</PanelBody>

				{ isHistory && (
					<PanelBody
						title={ __( 'History Settings', 'pinkcrab-weather-block' ) }
						initialOpen={ true }
					>
						<SelectControl
							label={ __( 'Default Range', 'pinkcrab-weather-block' ) }
							value={ defaultRange }
							options={ [
								{ label: __( '24 Hours', 'pinkcrab-weather-block' ), value: '24h' },
								{ label: __( '7 Days', 'pinkcrab-weather-block' ), value: '7d' },
								{ label: __( '30 Days', 'pinkcrab-weather-block' ), value: '30d' },
								{ label: __( '90 Days', 'pinkcrab-weather-block' ), value: '90d' },
							] }
							onChange={ ( value ) => setAttributes( { defaultRange: value } ) }
						/>
						<SelectControl
							label={ __( 'Cycle Type', 'pinkcrab-weather-block' ) }
							value={ cycleType }
							options={ [
								{ label: __( 'Auto (recommended)', 'pinkcrab-weather-block' ), value: 'auto' },
								{ label: __( '5 Minutes', 'pinkcrab-weather-block' ), value: '5min' },
								{ label: __( '1 Hour', 'pinkcrab-weather-block' ), value: '1hour' },
								{ label: __( '4 Hours', 'pinkcrab-weather-block' ), value: '4hour' },
								{ label: __( '1 Day', 'pinkcrab-weather-block' ), value: '1day' },
							] }
							onChange={ ( value ) => setAttributes( { cycleType: value } ) }
						/>
						<NumberControl
							label={ __( 'Auto Refresh (seconds)', 'pinkcrab-weather-block' ) }
							value={ autoRefreshInterval }
							min={ 60 }
							max={ 900 }
							step={ 30 }
							onChange={ ( value ) => setAttributes( { autoRefreshInterval: parseInt( value, 10 ) } ) }
						/>
					</PanelBody>
				) }

				<FieldPicker
					data={ data }
					selectedFields={ selectedFields }
					onChange={ ( fields ) => setAttributes( { selectedFields: fields } ) }
					isLoading={ isLoading }
				/>
			</InspectorControls>

			{ isHistory && (
				<RangeControls
					range={ editorRange }
					customFrom={ editorCustomFrom }
					customTo={ editorCustomTo }
					onRangeChange={ setEditorRange }
					onCustomFromChange={ setEditorCustomFrom }
					onCustomToChange={ setEditorCustomTo }
				/>
			) }

			<ThemeComponent
				data={ data }
				selectedFields={ selectedFields }
				stationName={ stationName }
				colors={ colors }
				iconSet={ iconSet }
				isLoading={ isLoading }
				error={ error }
				{ ...( isHistory ? { range: editorRange } : {} ) }
			/>
		</div>
	);
}
