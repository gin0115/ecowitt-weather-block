<?php

/**
 * Component: Lightning Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $distance Distance component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $count Count component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Lightning', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $distance || $count ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $distance ) : ?>
					<div class="grid-item">
						<?php $this->component( $distance ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $count ) : ?>
					<div class="grid-item">
						<?php $this->component( $count ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No lightning measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
