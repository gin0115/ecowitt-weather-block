<?php
/**
 * Component: CO2 AQI Combo Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $co2 CO2 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Measurement_Type|null $co2_24h_average 24h Average component instance
 */
?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'CO2 AQI Combo', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $co2 || $co2_24h_average ) : ?>
                    <div class="grid grid--2 grid--gap-md">
				<?php if ( $co2 ) : ?>
					<div class="grid-item">
						<?php $this->component( $co2 ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $co2_24h_average ) : ?>
					<div class="grid-item">
						<?php $this->component( $co2_24h_average ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No CO2 AQI measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
