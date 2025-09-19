<?php

/**
 * Component: Outdoor Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature|null $temperature Temperature component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature|null $feels_like Feels like temperature component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature|null $app_temp Apparent temperature component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature|null $dew_point Dew point temperature component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Humidity|null $humidity Humidity component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Outdoor Conditions', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $temperature || $feels_like || $app_temp || $dew_point || $humidity ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $temperature ) : ?>
					<div class="grid-item">
						<?php $this->component( $temperature ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $feels_like ) : ?>
					<div class="grid-item">
						<?php $this->component( $feels_like ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $app_temp ) : ?>
					<div class="grid-item">
						<?php $this->component( $app_temp ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $dew_point ) : ?>
					<div class="grid-item">
						<?php $this->component( $dew_point ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $humidity ) : ?>
					<div class="grid-item">
						<?php $this->component( $humidity ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No outdoor measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
