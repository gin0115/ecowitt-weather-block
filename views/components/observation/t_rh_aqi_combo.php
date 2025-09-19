<?php
/**
 * Component: Temperature and Humidity AQI Combo Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Temperature|null $temperature Temperature component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Humidity|null $humidity Humidity component instance
 */
?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Temperature & Humidity AQI Combo', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $temperature || $humidity ) : ?>
                    <div class="grid grid--2 grid--gap-md">
				<?php if ( $temperature ) : ?>
					<div class="grid-item">
						<?php $this->component( $temperature ); ?>
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
				<p><?php esc_html_e( 'No temperature and humidity AQI measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
