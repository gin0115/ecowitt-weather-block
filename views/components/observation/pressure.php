<?php

/**
 * Component: Pressure Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $relative Relative pressure component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $absolute Absolute pressure component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Pressure', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $relative || $absolute ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $relative ) : ?>
					<div class="grid-item">
						<?php $this->component( $relative ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $absolute ) : ?>
					<div class="grid-item">
						<?php $this->component( $absolute ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No pressure measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
