<?php

/**
 * Component: Piezo Rainfall Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rain_Rate|null $rain_rate Rain rate component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $daily Daily rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $event Event rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $hourly Hourly rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $weekly Weekly rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $monthly Monthly rainfall component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Rainfall|null $yearly Yearly rainfall component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Piezo Rainfall', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $rain_rate || $daily || $event || $hourly || $weekly || $monthly || $yearly ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $rain_rate ) : ?>
					<div class="grid-item">
						<?php $this->component( $rain_rate ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $daily ) : ?>
					<div class="grid-item">
						<?php $this->component( $daily ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $event ) : ?>
					<div class="grid-item">
						<?php $this->component( $event ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $hourly ) : ?>
					<div class="grid-item">
						<?php $this->component( $hourly ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $weekly ) : ?>
					<div class="grid-item">
						<?php $this->component( $weekly ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $monthly ) : ?>
					<div class="grid-item">
						<?php $this->component( $monthly ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $yearly ) : ?>
					<div class="grid-item">
						<?php $this->component( $yearly ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No piezo rainfall measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
