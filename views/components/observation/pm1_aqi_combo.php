<?php
/**
 * Component: PM1 AQI Combo Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Air_Quality|null $real_time_aqi Real Time AQI component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Air_Quality|null $pm1 PM1 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Air_Quality|null $pm1_24h_aqi 24h AQI component instance
 */
?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'PM1 AQI Combo', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $real_time_aqi || $pm1 || $pm1_24h_aqi ) : ?>
                    <div class="grid grid--2 grid--gap-md">
				<?php if ( $real_time_aqi ) : ?>
					<div class="grid-item">
						<?php $this->component( $real_time_aqi ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $pm1 ) : ?>
					<div class="grid-item">
						<?php $this->component( $pm1 ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $pm1_24h_aqi ) : ?>
					<div class="grid-item">
						<?php $this->component( $pm1_24h_aqi ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No PM1 AQI measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
