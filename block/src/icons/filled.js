/**
 * Filled icon set — solid filled SVGs.
 *
 * Each icon is a function component returning an SVG element.
 * All use 24×24 viewBox, currentColor fill, and accept className.
 */

const svg = ( className, children ) => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		width="24"
		height="24"
		fill="currentColor"
		className={ className }
	>
		{ children }
	</svg>
);

const filled = {
	temperature: ( { className } = {} ) => svg( className, (
		<path d="M15 13.53V4a4 4 0 0 0-8 0v9.53A6 6 0 1 0 15 13.53ZM11 4a2 2 0 0 1 2 2v6h-4V4h2Z" />
	) ),

	/* House */
	indoor: ( { className } = {} ) => svg( className, (
		<path d="M3 10.5L12 3l9 7.5V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10.5Z" />
	) ),

	/* Sun behind cloud */
	outdoor: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="9" cy="7" r="4" opacity="0.4" />
			<path d="M10 14a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	humidity: ( { className } = {} ) => svg( className, (
		<path d="M12 1.27l6.36 6.36a9 9 0 1 1-12.73 0L12 1.27Z" />
	) ),

	wind_speed: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 8h8a3 3 0 1 0-2.12-5.12L8.17 3.59A1 1 0 0 0 8.88 5H11a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2h0Z" />
			<path d="M3 12h16.5a3.5 3.5 0 1 0-2.47-5.97 1 1 0 0 0 1.41 1.41A1.5 1.5 0 0 1 19.5 10H3a1 1 0 0 0 0 2Z" />
			<path d="M3 16h11a3 3 0 1 1-2.12 5.12 1 1 0 0 0-1.42 1.42A5 5 0 0 0 14 20a3 3 0 0 0 0-6H3a1 1 0 0 0 0 2Z" />
		</>
	) ),

	wind_direction: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="10" opacity="0.2" />
			<polygon points="12,4 16,15 12,13 8,15" />
		</>
	) ),

	/* Barometer gauge */
	pressure: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="10" opacity="0.2" />
			<path d="M12 12l3.5-3.5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
			<circle cx="12" cy="12" r="2" />
			<path d="M7 17a7 7 0 0 1 1.17-3.89" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
			<path d="M15.83 13.11A7 7 0 0 1 17 17" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
		</>
	) ),

	/* Pressure group header — alias of pressure */
	pressure_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="10" opacity="0.2" />
			<path d="M12 12l3.5-3.5" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
			<circle cx="12" cy="12" r="2" />
			<path d="M7 17a7 7 0 0 1 1.17-3.89" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
			<path d="M15.83 13.11A7 7 0 0 1 17 17" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
		</>
	) ),

	rainfall: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 13.5A7 7 0 1 1 15.71 7h1.79a4.5 4.5 0 0 1 .5 8.97V16a4.5 4.5 0 0 1-4.5-4.5H4Z" opacity="0.3" />
			<circle cx="8" cy="20" r="1.5" />
			<circle cx="12" cy="20" r="1.5" />
			<circle cx="16" cy="20" r="1.5" />
		</>
	) ),

	rain_rate: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 13.5A7 7 0 1 1 15.71 7h1.79a4.5 4.5 0 0 1 .5 8.97V16a4.5 4.5 0 0 1-4.5-4.5H4Z" opacity="0.3" />
			<path d="M10 22l3-8" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
			<path d="M14 22l3-8" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
		</>
	) ),

	solar_radiation: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="5" />
			<rect x="11" y="1" width="2" height="3" rx="1" />
			<rect x="11" y="20" width="2" height="3" rx="1" />
			<rect x="1" y="11" width="3" height="2" rx="1" />
			<rect x="20" y="11" width="3" height="2" rx="1" />
			<rect x="4.22" y="4.22" width="2" height="3" rx="1" transform="rotate(-45 5.22 5.72)" />
			<rect x="17.78" y="16.78" width="2" height="3" rx="1" transform="rotate(-45 18.78 18.28)" />
			<rect x="4.22" y="16.78" width="2" height="3" rx="1" transform="rotate(45 5.22 18.28)" />
			<rect x="17.78" y="4.22" width="2" height="3" rx="1" transform="rotate(45 18.78 5.72)" />
		</>
	) ),

	uv_index: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="6" />
			<rect x="11" y="0" width="2" height="4" rx="1" />
			<rect x="11" y="20" width="2" height="4" rx="1" />
			<rect x="0" y="11" width="4" height="2" rx="1" />
			<rect x="20" y="11" width="4" height="2" rx="1" />
			<rect x="3.51" y="3.51" width="2" height="4" rx="1" transform="rotate(-45 4.51 5.51)" />
			<rect x="18.49" y="16.49" width="2" height="4" rx="1" transform="rotate(-45 19.49 18.49)" />
			<rect x="3.51" y="16.49" width="2" height="4" rx="1" transform="rotate(45 4.51 18.49)" />
			<rect x="18.49" y="3.51" width="2" height="4" rx="1" transform="rotate(45 19.49 5.51)" />
		</>
	) ),

	lightning: ( { className } = {} ) => svg( className, (
		<path d="M13 1L2 14h9l-1 9 11-13h-9l1-9Z" />
	) ),

	co2: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="8" opacity="0.2" />
			<path d="M10 10a2 2 0 0 0-3 1.73v.54a2 2 0 0 0 3 1.73" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
			<circle cx="14" cy="12" r="2" fill="none" stroke="currentColor" strokeWidth="2" />
		</>
	) ),

	air_quality: ( { className } = {} ) => svg( className, (
		<path d="M4 22V2s1-1 4-1c3 0 5 2 8 2s4-1 4-1v12s-1 1-4 1-5-2-8-2-4 1-4 1" />
	) ),

	battery: ( { className } = {} ) => svg( className, (
		<>
			<rect x="1" y="6" width="18" height="12" rx="2" />
			<path d="M23 10v4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
		</>
	) ),

	water_leak: ( { className } = {} ) => svg( className, (
		<path d="M12 1c0 0-7 7-7 12a7 7 0 0 0 14 0c0-5-7-12-7-12Z" />
	) ),

	leaf_wetness: ( { className } = {} ) => svg( className, (
		<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10ZM2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
	) ),

	soil_moisture: ( { className } = {} ) => svg( className, (
		<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" />
	) ),

	/* ── Group header icons ── */

	outdoor_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="9" cy="7" r="4" opacity="0.4" />
			<path d="M10 14a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	indoor_group: ( { className } = {} ) => svg( className, (
		<path d="M3 10.5L12 3l9 7.5V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10.5Z" />
	) ),

	wind_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 8h8a3 3 0 1 0-2.12-5.12L8.17 3.59A1 1 0 0 0 8.88 5H11a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2h0Z" />
			<path d="M3 12h16.5a3.5 3.5 0 1 0-2.47-5.97 1 1 0 0 0 1.41 1.41A1.5 1.5 0 0 1 19.5 10H3a1 1 0 0 0 0 2Z" />
			<path d="M3 16h11a3 3 0 1 1-2.12 5.12 1 1 0 0 0-1.42 1.42A5 5 0 0 0 14 20a3 3 0 0 0 0-6H3a1 1 0 0 0 0 2Z" />
		</>
	) ),

	rainfall_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 13.5A7 7 0 1 1 15.71 7h1.79a4.5 4.5 0 0 1 .5 8.97V16a4.5 4.5 0 0 1-4.5-4.5H4Z" opacity="0.3" />
			<circle cx="8" cy="20" r="1.5" />
			<circle cx="12" cy="20" r="1.5" />
			<circle cx="16" cy="20" r="1.5" />
		</>
	) ),

	solar_radiation_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="5" />
			<rect x="11" y="1" width="2" height="3" rx="1" />
			<rect x="11" y="20" width="2" height="3" rx="1" />
			<rect x="1" y="11" width="3" height="2" rx="1" />
			<rect x="20" y="11" width="3" height="2" rx="1" />
			<rect x="4.22" y="4.22" width="2" height="3" rx="1" transform="rotate(-45 5.22 5.72)" />
			<rect x="17.78" y="16.78" width="2" height="3" rx="1" transform="rotate(-45 18.78 18.28)" />
			<rect x="4.22" y="16.78" width="2" height="3" rx="1" transform="rotate(45 5.22 18.28)" />
			<rect x="17.78" y="4.22" width="2" height="3" rx="1" transform="rotate(45 18.78 5.72)" />
		</>
	) ),

	lightning_group: ( { className } = {} ) => svg( className, (
		<path d="M13 1L2 14h9l-1 9 11-13h-9l1-9Z" />
	) ),

	co2_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="8" opacity="0.2" />
			<path d="M10 10a2 2 0 0 0-3 1.73v.54a2 2 0 0 0 3 1.73" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
			<circle cx="14" cy="12" r="2" fill="none" stroke="currentColor" strokeWidth="2" />
		</>
	) ),

	air_quality_group: ( { className } = {} ) => svg( className, (
		<path d="M4 22V2s1-1 4-1c3 0 5 2 8 2s4-1 4-1v12s-1 1-4 1-5-2-8-2-4 1-4 1" />
	) ),

	temperature_group: ( { className } = {} ) => svg( className, (
		<path d="M15 13.53V4a4 4 0 0 0-8 0v9.53A6 6 0 1 0 15 13.53ZM11 4a2 2 0 0 1 2 2v6h-4V4h2Z" />
	) ),

	water_leak_group: ( { className } = {} ) => svg( className, (
		<path d="M12 1c0 0-7 7-7 12a7 7 0 0 0 14 0c0-5-7-12-7-12Z" />
	) ),

	battery_group: ( { className } = {} ) => svg( className, (
		<>
			<rect x="1" y="6" width="18" height="12" rx="2" />
			<path d="M23 10v4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
		</>
	) ),

	soil_moisture_group: ( { className } = {} ) => svg( className, (
		<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" />
	) ),

	leaf_wetness_group: ( { className } = {} ) => svg( className, (
		<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10ZM2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
	) ),

	default: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="10" opacity="0.3" />
			<circle cx="12" cy="12" r="3" />
		</>
	) ),
};

export default filled;
