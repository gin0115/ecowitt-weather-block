/**
 * Illustrative icon set — more decorative, detailed SVGs.
 *
 * Each icon is a function component returning an SVG element.
 * All use 24×24 viewBox, currentColor, and accept className.
 */

const svg = ( className, children ) => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		width="24"
		height="24"
		fill="none"
		stroke="currentColor"
		strokeWidth="1.5"
		strokeLinecap="round"
		strokeLinejoin="round"
		className={ className }
	>
		{ children }
	</svg>
);

const illustrative = {
	/* House with thermometer inside */
	indoor: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
			<path d="M13 16.5v-4a1.5 1.5 0 0 0-3 0v4a2.5 2.5 0 1 0 3 0Z" opacity="0.5" />
			<circle cx="11.5" cy="17.5" r="1" fill="currentColor" stroke="none" opacity="0.5" />
		</>
	) ),

	/* Sun behind cloud with landscape hint */
	outdoor: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="8" cy="7" r="3.5" />
			<path d="M8 1.5v1" />
			<path d="M2.5 7h1" />
			<path d="M13 7h.5" />
			<path d="M4.61 3.61l.71.71" />
			<path d="M11.39 3.61l-.71.71" />
			<path d="M10 13a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" fill="currentColor" fillOpacity="0.1" />
			<path d="M10 13a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	temperature: ( { className } = {} ) => svg( className, (
		<>
			<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0Z" />
			<circle cx="11.5" cy="17.5" r="2" fill="currentColor" stroke="none" />
			<line x1="11.5" y1="15" x2="11.5" y2="7" stroke="currentColor" strokeWidth="2" />
			<line x1="15" y1="6" x2="17" y2="6" />
			<line x1="15" y1="9" x2="16" y2="9" />
			<line x1="15" y1="12" x2="17" y2="12" />
		</>
	) ),

	humidity: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0Z" />
			<path d="M8 14a4 4 0 0 0 4 4" opacity="0.5" />
			<circle cx="10" cy="10" r="1" fill="currentColor" stroke="none" opacity="0.3" />
		</>
	) ),

	wind_speed: ( { className } = {} ) => svg( className, (
		<>
			<path d="M9.59 4.59A2 2 0 1 1 11 8H2" />
			<path d="M12.59 19.41A2 2 0 1 0 14 16H2" />
			<path d="M17.73 7.73A2.5 2.5 0 1 1 19.5 12H2" />
			<circle cx="1" cy="8" r="0.5" fill="currentColor" stroke="none" />
			<circle cx="1" cy="12" r="0.5" fill="currentColor" stroke="none" />
			<circle cx="1" cy="16" r="0.5" fill="currentColor" stroke="none" />
		</>
	) ),

	wind_direction: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<polygon points="12,4 15,14 12,12 9,14" fill="currentColor" stroke="none" />
			<line x1="12" y1="2" x2="12" y2="4" />
			<line x1="12" y1="20" x2="12" y2="22" />
			<line x1="2" y1="12" x2="4" y2="12" />
			<line x1="20" y1="12" x2="22" y2="12" />
			<text x="12" y="23" textAnchor="middle" fontSize="3" fill="currentColor" stroke="none" fontFamily="sans-serif">S</text>
			<text x="12" y="2.5" textAnchor="middle" fontSize="3" fill="currentColor" stroke="none" fontFamily="sans-serif">N</text>
		</>
	) ),

	/* Barometer gauge with tick marks */
	pressure: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<path d="M12 12l3.5-3.5" strokeWidth="2" />
			<circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none" />
			<path d="M6 17a8 8 0 0 1 .86-3.64" opacity="0.4" />
			<path d="M17.14 13.36A8 8 0 0 1 18 17" opacity="0.4" />
			<line x1="12" y1="3" x2="12" y2="5" opacity="0.3" />
			<line x1="19.36" y1="7.64" x2="17.95" y2="9.05" opacity="0.3" />
			<line x1="4.64" y1="7.64" x2="6.05" y2="9.05" opacity="0.3" />
		</>
	) ),

	/* Pressure group header — alias of pressure */
	pressure_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<path d="M12 12l3.5-3.5" strokeWidth="2" />
			<circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none" />
			<path d="M6 17a8 8 0 0 1 .86-3.64" opacity="0.4" />
			<path d="M17.14 13.36A8 8 0 0 1 18 17" opacity="0.4" />
			<line x1="12" y1="3" x2="12" y2="5" opacity="0.3" />
			<line x1="19.36" y1="7.64" x2="17.95" y2="9.05" opacity="0.3" />
			<line x1="4.64" y1="7.64" x2="6.05" y2="9.05" opacity="0.3" />
		</>
	) ),

	rainfall: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M7 18l-1 4" opacity="0.5" />
			<path d="M10 18l-1 4" />
			<path d="M13 18l-1 4" opacity="0.5" />
			<path d="M16 18l-1 4" />
			<path d="M19 18l-1 4" opacity="0.5" />
		</>
	) ),

	rain_rate: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M8 17l-2 5" strokeWidth="2" />
			<path d="M12 17l-2 5" strokeWidth="2" />
			<path d="M16 17l-2 5" strokeWidth="2" />
		</>
	) ),

	solar_radiation: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" fill="currentColor" fillOpacity="0.2" />
			<circle cx="12" cy="12" r="4" />
			<path d="M12 2v3" />
			<path d="M12 19v3" />
			<path d="M4.93 4.93l2.12 2.12" />
			<path d="M16.95 16.95l2.12 2.12" />
			<path d="M2 12h3" />
			<path d="M19 12h3" />
			<path d="M4.93 19.07l2.12-2.12" />
			<path d="M16.95 7.05l2.12-2.12" />
			<path d="M15 9l3-3" opacity="0.4" />
			<path d="M16 11l2-2" opacity="0.4" />
		</>
	) ),

	uv_index: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" fill="currentColor" fillOpacity="0.3" />
			<circle cx="12" cy="12" r="4" />
			<path d="M12 1v4" />
			<path d="M12 19v4" />
			<path d="M4.22 4.22l2.83 2.83" />
			<path d="M16.95 16.95l2.83 2.83" />
			<path d="M1 12h4" />
			<path d="M19 12h4" />
			<path d="M4.22 19.78l2.83-2.83" />
			<path d="M16.95 7.05l2.83-2.83" />
		</>
	) ),

	lightning: ( { className } = {} ) => svg( className, (
		<>
			<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" fill="currentColor" fillOpacity="0.15" />
			<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" />
		</>
	) ),

	co2: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="8" opacity="0.15" fill="currentColor" />
			<circle cx="12" cy="12" r="8" />
			<text x="12" y="14" textAnchor="middle" fontSize="6" fill="currentColor" stroke="none" fontFamily="sans-serif" fontWeight="bold">CO₂</text>
		</>
	) ),

	air_quality: ( { className } = {} ) => svg( className, (
		<>
			<path d="M8 2c3 0 5 2 8 2s4-1 4-1v11s-1 1-4 1-5-2-8-2-4 1-4 1V2s1-1 4-1Z" fill="currentColor" fillOpacity="0.15" />
			<path d="M8 2c3 0 5 2 8 2s4-1 4-1v11s-1 1-4 1-5-2-8-2-4 1-4 1V2s1-1 4-1Z" />
			<path d="M4 22v-7" />
		</>
	) ),

	battery: ( { className } = {} ) => svg( className, (
		<>
			<rect x="2" y="7" width="16" height="10" rx="2" />
			<path d="M22 11v2" />
			<rect x="4" y="9" width="5" height="6" rx="0.5" fill="currentColor" fillOpacity="0.5" stroke="none" />
			<rect x="10" y="9" width="5" height="6" rx="0.5" fill="currentColor" fillOpacity="0.25" stroke="none" />
		</>
	) ),

	water_leak: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" />
			<path d="M9 16a3 3 0 0 0 3 3" opacity="0.5" />
			<circle cx="10" cy="12" r="0.5" fill="currentColor" stroke="none" opacity="0.3" />
			<circle cx="13" cy="10" r="0.5" fill="currentColor" stroke="none" opacity="0.3" />
		</>
	) ),

	leaf_wetness: ( { className } = {} ) => svg( className, (
		<>
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" />
			<path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
			<path d="M14 8l-2 4" opacity="0.4" />
			<path d="M16 10l-1 3" opacity="0.4" />
		</>
	) ),

	soil_moisture: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" />
			<path d="M8 12h8" opacity="0.3" />
			<path d="M6 15h12" opacity="0.2" />
			<path d="M9 9h6" opacity="0.4" />
		</>
	) ),

	/* ── Group header icons ── */

	outdoor_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="8" cy="7" r="3.5" />
			<path d="M8 1.5v1" />
			<path d="M2.5 7h1" />
			<path d="M13 7h.5" />
			<path d="M4.61 3.61l.71.71" />
			<path d="M11.39 3.61l-.71.71" />
			<path d="M10 13a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" fill="currentColor" fillOpacity="0.1" />
			<path d="M10 13a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	indoor_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
			<path d="M13 16.5v-4a1.5 1.5 0 0 0-3 0v4a2.5 2.5 0 1 0 3 0Z" opacity="0.5" />
			<circle cx="11.5" cy="17.5" r="1" fill="currentColor" stroke="none" opacity="0.5" />
		</>
	) ),

	wind_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M9.59 4.59A2 2 0 1 1 11 8H2" />
			<path d="M12.59 19.41A2 2 0 1 0 14 16H2" />
			<path d="M17.73 7.73A2.5 2.5 0 1 1 19.5 12H2" />
			<circle cx="1" cy="8" r="0.5" fill="currentColor" stroke="none" />
			<circle cx="1" cy="12" r="0.5" fill="currentColor" stroke="none" />
			<circle cx="1" cy="16" r="0.5" fill="currentColor" stroke="none" />
		</>
	) ),

	rainfall_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M7 18l-1 4" opacity="0.5" />
			<path d="M10 18l-1 4" />
			<path d="M13 18l-1 4" opacity="0.5" />
			<path d="M16 18l-1 4" />
			<path d="M19 18l-1 4" opacity="0.5" />
		</>
	) ),

	solar_radiation_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" fill="currentColor" fillOpacity="0.2" />
			<circle cx="12" cy="12" r="4" />
			<path d="M12 2v3" />
			<path d="M12 19v3" />
			<path d="M4.93 4.93l2.12 2.12" />
			<path d="M16.95 16.95l2.12 2.12" />
			<path d="M2 12h3" />
			<path d="M19 12h3" />
			<path d="M4.93 19.07l2.12-2.12" />
			<path d="M16.95 7.05l2.12-2.12" />
			<path d="M15 9l3-3" opacity="0.4" />
			<path d="M16 11l2-2" opacity="0.4" />
		</>
	) ),

	lightning_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" fill="currentColor" fillOpacity="0.15" />
			<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" />
		</>
	) ),

	co2_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="8" opacity="0.15" fill="currentColor" />
			<circle cx="12" cy="12" r="8" />
			<text x="12" y="14" textAnchor="middle" fontSize="6" fill="currentColor" stroke="none" fontFamily="sans-serif" fontWeight="bold">CO₂</text>
		</>
	) ),

	air_quality_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M8 2c3 0 5 2 8 2s4-1 4-1v11s-1 1-4 1-5-2-8-2-4 1-4 1V2s1-1 4-1Z" fill="currentColor" fillOpacity="0.15" />
			<path d="M8 2c3 0 5 2 8 2s4-1 4-1v11s-1 1-4 1-5-2-8-2-4 1-4 1V2s1-1 4-1Z" />
			<path d="M4 22v-7" />
		</>
	) ),

	temperature_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0Z" />
			<circle cx="11.5" cy="17.5" r="2" fill="currentColor" stroke="none" />
			<line x1="11.5" y1="15" x2="11.5" y2="7" stroke="currentColor" strokeWidth="2" />
			<line x1="15" y1="6" x2="17" y2="6" />
			<line x1="15" y1="9" x2="16" y2="9" />
			<line x1="15" y1="12" x2="17" y2="12" />
		</>
	) ),

	water_leak_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" />
			<path d="M9 16a3 3 0 0 0 3 3" opacity="0.5" />
			<circle cx="10" cy="12" r="0.5" fill="currentColor" stroke="none" opacity="0.3" />
			<circle cx="13" cy="10" r="0.5" fill="currentColor" stroke="none" opacity="0.3" />
		</>
	) ),

	battery_group: ( { className } = {} ) => svg( className, (
		<>
			<rect x="2" y="7" width="16" height="10" rx="2" />
			<path d="M22 11v2" />
			<rect x="4" y="9" width="5" height="6" rx="0.5" fill="currentColor" fillOpacity="0.5" stroke="none" />
			<rect x="10" y="9" width="5" height="6" rx="0.5" fill="currentColor" fillOpacity="0.25" stroke="none" />
		</>
	) ),

	soil_moisture_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M12 22c4-4 8-7.5 8-12a8 8 0 0 0-16 0c0 4.5 4 8 8 12Z" />
			<path d="M8 12h8" opacity="0.3" />
			<path d="M6 15h12" opacity="0.2" />
			<path d="M9 9h6" opacity="0.4" />
		</>
	) ),

	leaf_wetness_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" fill="currentColor" fillOpacity="0.1" />
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" />
			<path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
			<path d="M14 8l-2 4" opacity="0.4" />
			<path d="M16 10l-1 3" opacity="0.4" />
		</>
	) ),

	default: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<circle cx="12" cy="12" r="2" fill="currentColor" stroke="none" opacity="0.3" />
		</>
	) ),
};

export default illustrative;
