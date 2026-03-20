<?php

/**
 * Component: Soil Channels Measurements
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var array<array{soilmoisture: Soil_Moisture|null, ad: Count|null, channel_name: string}> $channels Array of soil channel components
 */

?>

<div class="measurement-section mb-lg">
	<div class="device__header">
		<h5 class="device__title text-lg pb-sm">
			<?php esc_html_e( 'Soil Channels', 'pinkcrab-weather-block' ); ?>
		</h5>
	</div>

	<div class="measurement-section__content">
		<?php if ( ! empty( $channels ) ) : ?>
			<div class="channels-grid">
				<?php foreach ( $channels as $channel ) : ?>
					<div class="channel-group mb-md">
						<h6 class="channel-title"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $channel['channel_name'] ) ) ); ?></h6>
						<div class="grid grid--2 grid--gap-sm">
							<?php if ( $channel['soilmoisture'] ) : ?>
								<div class="grid-item">
									<?php $this->component( $channel['soilmoisture'] ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $channel['ad'] ) : ?>
								<div class="grid-item">
									<?php $this->component( $channel['ad'] ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="observation-placeholder">
				<p><?php esc_html_e( 'No soil channel measurement data available.', 'pinkcrab-weather-block' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
