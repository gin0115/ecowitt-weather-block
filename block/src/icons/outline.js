/**
 * Outline icon set — thin line style SVGs.
 *
 * Each icon is a function component returning an SVG element.
 * All use 24×24 viewBox, currentColor stroke, and accept className.
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

const outline = {
	/* Thermometer */
	temperature: ( { className } = {} ) => svg( className, (
		<>
			<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0Z" />
			<circle cx="11.5" cy="17.5" r="1.5" fill="currentColor" stroke="none" />
		</>
	) ),

	/* House with thermometer */
	indoor: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
			<path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
			<path d="M12 11v-1" />
		</>
	) ),

	/* Sun behind cloud */
	outdoor: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="9" cy="8" r="3.5" />
			<path d="M9 2.5v1" />
			<path d="M9 13v-.5" />
			<path d="M3.5 8h1" />
			<path d="M14 8h.5" />
			<path d="M5.11 4.11l.71.71" />
			<path d="M12.18 4.11l-.71.71" />
			<path d="M5.11 11.89l.71-.71" />
			<path d="M10 14a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	/* Water drop */
	humidity: ( { className } = {} ) => svg( className, (
		<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0Z" />
	) ),

	/* Wind lines */
	wind_speed: ( { className } = {} ) => svg( className, (
		<>
			<path d="M9.59 4.59A2 2 0 1 1 11 8H2" />
			<path d="M12.59 19.41A2 2 0 1 0 14 16H2" />
			<path d="M17.73 7.73A2.5 2.5 0 1 1 19.5 12H2" />
		</>
	) ),

	/* Compass */
	wind_direction: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<polygon points="12,5 15,14 12,12 9,14" fill="currentColor" stroke="none" />
		</>
	) ),

	/* Barometer gauge — circle with arc and needle */
	pressure: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<path d="M12 12l3.5-3.5" />
			<circle cx="12" cy="12" r="1" fill="currentColor" stroke="none" />
			<path d="M7 17a7 7 0 0 1 1.17-3.89" />
			<path d="M15.83 13.11A7 7 0 0 1 17 17" />
		</>
	) ),

	/* Pressure group header — alias of pressure */
	pressure_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<path d="M12 12l3.5-3.5" />
			<circle cx="12" cy="12" r="1" fill="currentColor" stroke="none" />
			<path d="M7 17a7 7 0 0 1 1.17-3.89" />
			<path d="M15.83 13.11A7 7 0 0 1 17 17" />
		</>
	) ),

	/* Cloud with rain drops */
	rainfall: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M8 19v2" />
			<path d="M12 19v2" />
			<path d="M16 19v2" />
		</>
	) ),

	/* Cloud with heavy rain streaks */
	rain_rate: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M9.2 22l3-7" />
			<path d="M13.2 22l3-7" />
		</>
	) ),

	/* Sun with rays */
	solar_radiation: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" />
			<path d="M12 2v2" />
			<path d="M12 20v2" />
			<path d="M4.93 4.93l1.41 1.41" />
			<path d="M17.66 17.66l1.41 1.41" />
			<path d="M2 12h2" />
			<path d="M20 12h2" />
			<path d="M4.93 19.07l1.41-1.41" />
			<path d="M17.66 6.34l1.41-1.41" />
		</>
	) ),

	/* Sun with strong rays */
	uv_index: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" />
			<path d="M12 2v4" />
			<path d="M12 18v4" />
			<path d="M4.93 4.93l2.83 2.83" />
			<path d="M16.24 16.24l2.83 2.83" />
			<path d="M2 12h4" />
			<path d="M18 12h4" />
			<path d="M4.93 19.07l2.83-2.83" />
			<path d="M16.24 7.76l2.83-2.83" />
		</>
	) ),

	/* Lightning bolt */
	lightning: ( { className } = {} ) => svg( className, (
		<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" />
	) ),

	/* CO2 cloud */
	co2: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M8 16h8" />
			<path d="M10 14v4" />
			<path d="M14 14v4" />
		</>
	) ),

	/* Particles / dots for air quality */
	air_quality: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="6" cy="8" r="1.5" />
			<circle cx="14" cy="6" r="2" />
			<circle cx="10" cy="13" r="1.5" />
			<circle cx="18" cy="11" r="1" />
			<circle cx="5" cy="17" r="1" />
			<circle cx="16" cy="17" r="1.5" />
			<circle cx="11" cy="19" r="1" />
		</>
	) ),

	/* Battery */
	battery: ( { className } = {} ) => svg( className, (
		<>
			<rect x="2" y="7" width="16" height="10" rx="2" />
			<path d="M22 11v2" />
			<rect x="4" y="9" width="8" height="6" rx="1" fill="currentColor" stroke="none" />
		</>
	) ),

	/* Water drop with alert */
	water_leak: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" />
			<path d="M12 13v3" />
			<circle cx="12" cy="18" r="0.5" fill="currentColor" stroke="none" />
		</>
	) ),

	/* Leaf */
	leaf_wetness: ( { className } = {} ) => svg( className, (
		<>
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" />
			<path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
		</>
	) ),

	/* Plant/seedling in soil */
	soil_moisture: ( { className } = {} ) => svg( className, (
		<>
			<path d="M2 18h20" />
			<path d="M12 18v-4" />
			<path d="M12 14c-2-3-1-6 2-8-1 3 0 5 2 7" />
			<path d="M12 14c2-3 1-6-2-8 1 3 0 5-2 7" />
		</>
	) ),

	/* ── Group header icons ── */

	outdoor_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="9" cy="8" r="3.5" />
			<path d="M9 2.5v1" />
			<path d="M9 13v-.5" />
			<path d="M3.5 8h1" />
			<path d="M14 8h.5" />
			<path d="M5.11 4.11l.71.71" />
			<path d="M12.18 4.11l-.71.71" />
			<path d="M5.11 11.89l.71-.71" />
			<path d="M10 14a5 5 0 0 1 9.9 1H21a3 3 0 0 1 0 6H8a4 4 0 0 1-.76-7.93" />
		</>
	) ),

	indoor_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
			<path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
			<path d="M12 11v-1" />
		</>
	) ),

	wind_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M9.59 4.59A2 2 0 1 1 11 8H2" />
			<path d="M12.59 19.41A2 2 0 1 0 14 16H2" />
			<path d="M17.73 7.73A2.5 2.5 0 1 1 19.5 12H2" />
		</>
	) ),

	rainfall_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M8 19v2" />
			<path d="M12 19v2" />
			<path d="M16 19v2" />
		</>
	) ),

	solar_radiation_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="4" />
			<path d="M12 2v2" />
			<path d="M12 20v2" />
			<path d="M4.93 4.93l1.41 1.41" />
			<path d="M17.66 17.66l1.41 1.41" />
			<path d="M2 12h2" />
			<path d="M20 12h2" />
			<path d="M4.93 19.07l1.41-1.41" />
			<path d="M17.66 6.34l1.41-1.41" />
		</>
	) ),

	lightning_group: ( { className } = {} ) => svg( className, (
		<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z" />
	) ),

	co2_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
			<path d="M8 16h8" />
			<path d="M10 14v4" />
			<path d="M14 14v4" />
		</>
	) ),

	air_quality_group: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="6" cy="8" r="1.5" />
			<circle cx="14" cy="6" r="2" />
			<circle cx="10" cy="13" r="1.5" />
			<circle cx="18" cy="11" r="1" />
			<circle cx="5" cy="17" r="1" />
			<circle cx="16" cy="17" r="1.5" />
			<circle cx="11" cy="19" r="1" />
		</>
	) ),

	temperature_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0Z" />
			<circle cx="11.5" cy="17.5" r="1.5" fill="currentColor" stroke="none" />
		</>
	) ),

	water_leak_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M12 2c0 0-6 6.5-6 10.5a6 6 0 0 0 12 0C18 8.5 12 2 12 2Z" />
			<path d="M12 13v3" />
			<circle cx="12" cy="18" r="0.5" fill="currentColor" stroke="none" />
		</>
	) ),

	battery_group: ( { className } = {} ) => svg( className, (
		<>
			<rect x="2" y="7" width="16" height="10" rx="2" />
			<path d="M22 11v2" />
			<rect x="4" y="9" width="8" height="6" rx="1" fill="currentColor" stroke="none" />
		</>
	) ),

	soil_moisture_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M2 18h20" />
			<path d="M12 18v-4" />
			<path d="M12 14c-2-3-1-6 2-8-1 3 0 5 2 7" />
			<path d="M12 14c2-3 1-6-2-8 1 3 0 5-2 7" />
		</>
	) ),

	leaf_wetness_group: ( { className } = {} ) => svg( className, (
		<>
			<path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-4.78 10-10 10Z" />
			<path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
		</>
	) ),

	/* Circle with dot — generic fallback */
	default: ( { className } = {} ) => svg( className, (
		<>
			<circle cx="12" cy="12" r="9" />
			<circle cx="12" cy="12" r="1" fill="currentColor" stroke="none" />
		</>
	) ),
};

export default outline;
