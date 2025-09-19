<?php

/**
 * Component: Water Leak Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Percentage|null $leak_ch1 Leak Channel 1 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Percentage|null $leak_ch2 Leak Channel 2 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Percentage|null $leak_ch3 Leak Channel 3 component instance
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type\Percentage|null $leak_ch4 Leak Channel 4 component instance
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Water Leak', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( $leak_ch1 || $leak_ch2 || $leak_ch3 || $leak_ch4 ) : ?>
                    <div class="grid grid--2 grid--gap-md">
				<?php if ( $leak_ch1 ) : ?>
					<div class="grid-item">
						<?php $this->component( $leak_ch1 ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $leak_ch2 ) : ?>
					<div class="grid-item">
						<?php $this->component( $leak_ch2 ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $leak_ch3 ) : ?>
					<div class="grid-item">
						<?php $this->component( $leak_ch3 ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $leak_ch4 ) : ?>
					<div class="grid-item">
						<?php $this->component( $leak_ch4 ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No water leak measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
