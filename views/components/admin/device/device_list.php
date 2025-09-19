<?php

/**
 * Template: Device List Component
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var array<int, PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\Device> $devices Array of device components
 */
?>

<div class="ecowitt-devices-section">
	<div class="ecowitt-devices-section__header">
		<h2 class="ecowitt-devices-section__title"><?php esc_html_e( 'Weather Station Devices', 'pinkcrab-weather-block' ); ?></h2>
		<?php if ( ! empty( $devices ) ) : ?>
			<div class="devices-stats">
				<span class="devices-count">
					<?php
					$device_count = count( $devices );
					printf(
						/* translators: %1$d: number of devices */
						esc_html( _n( '%1$d Device', '%1$d Devices', $device_count, 'pinkcrab-weather-block' ) ),
						absint( $device_count )
					);
					?>
				</span>
				<?php
				$total_iot_count = array_sum(
					array_map(
						function ( $device ) {
							return count( $device->iot_devices );
						},
						$devices
					)
				);
				?>
				<?php if ( $total_iot_count > 0 ) : ?>
					<span class="iot-devices-count">
						<?php
						printf(
							/* translators: %d: number of IOT devices */
							esc_html( _n( '%d IOT Device', '%d IOT Devices', $total_iot_count, 'pinkcrab-weather-block' ) ),
							absint( $total_iot_count )
						);
						?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="ecowitt-devices-section__content">
		<div class="devices-container">
		<?php if ( ! empty( $devices ) ) : ?>
			<div class="devices-list">
				<?php foreach ( $devices as $device ) : ?>
					<?php $this->component( $device ); ?>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
		<div class="devices-empty devices-list">
			<div class="empty-icon">
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
					<rect x="2" y="4" width="20" height="16" rx="2"/>
					<path d="M7 15h.01"/>
					<path d="M17 15h.01"/>
					<path d="M7 11h.01"/>
					<path d="M17 11h.01"/>
					<path d="M7 7h.01"/>
					<path d="M17 7h.01"/>
				</svg>
			</div>
			<h3 class="empty-title"><?php esc_html_e( 'No Devices Found', 'pinkcrab-weather-block' ); ?></h3>
			<p class="empty-description">
				<?php esc_html_e( 'No weather station devices were found. Make sure you have a valid connection configured and that your weather station is properly set up.', 'pinkcrab-weather-block' ); ?>
			</p>
		</div>
		<?php endif; ?>

		<?php // Debug info - remove in production ?>
		<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
			<details class="mt-xl">
				<summary>Debug Information</summary>
				<?php
				echo '<pre>';
				$device_count    = count( $devices );
				$total_iot_count = array_sum(
					array_map(
						function ( $device ) {
							return count( $device->iot_devices );
						},
						$devices
					)
				);
				echo esc_html(
					wp_json_encode(
						array(
							'devices_count'     => $device_count,
							'total_iot_devices' => $total_iot_count,
							'has_devices'       => ! empty( $devices ),
						),
						JSON_PRETTY_PRINT
					)
				);
				echo '</pre>';
				?>
			</details>
		<?php endif; ?>

		</div>
	</div>
</div>
