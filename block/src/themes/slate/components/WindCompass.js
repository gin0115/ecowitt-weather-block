/**
 * WindCompass — circular compass dial showing wind direction.
 *
 * Renders a circle with a direction needle and cardinal label in the centre.
 */
export default function WindCompass( { degrees = 0, cardinal = 'N' } ) {
	return (
		<div className="slate-compass">
			<div
				className="slate-compass__needle"
				style={ { transform: `rotate(${ degrees }deg)` } }
			>
				<div className="slate-compass__needle-line">
					<div className="slate-compass__needle-tip" />
				</div>
			</div>
			<span className="slate-compass__cardinal">{ cardinal }</span>
		</div>
	);
}
