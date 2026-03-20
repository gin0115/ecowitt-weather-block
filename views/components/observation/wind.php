<?php

/**
 * Component: Wind Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $wind_speed Wind speed component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $wind_gust Wind gust component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $wind_direction Wind direction component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Wind', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $wind_speed || $wind_gust || $wind_direction ) : ?>
			<div class="grid grid--2 grid--gap-md">
				<?php if ( $wind_speed ) : ?>
					<div class="grid-item">
						<?php $this->component( $wind_speed ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $wind_gust ) : ?>
					<div class="grid-item">
						<?php $this->component( $wind_gust ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $wind_direction ) : ?>
					<div class="grid-item">
						<?php $this->component( $wind_direction ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No wind measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
