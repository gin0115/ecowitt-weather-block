<?php

/**
 * Component: Solar Radiation and UV Index Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Solar|null $solar Solar radiation component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Uvi|null $uvi UV index component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Solar & UV', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $solar || $uvi ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $solar ) : ?>
					<div class="grid-item">
						<?php $this->component( $solar ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $uvi ) : ?>
					<div class="grid-item">
						<?php $this->component( $uvi ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No solar and UV measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
