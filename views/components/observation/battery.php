<?php

/**
 * Component: Battery Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var array $battery_measurements Array of battery measurement components
 */
?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Battery Status', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( ! empty( $battery_measurements ) ) : ?>
                    <div class="grid grid--2 grid--gap-md">
				<?php foreach ( $battery_measurements as $measurement ) : ?>
					<div class="grid-item">
						<?php $this->component( $measurement ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No battery measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
