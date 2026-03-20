<?php

use PinkCrab\Ecowitt_Weather_Block\Utilities\Utils;

/**
 * Component: Device
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var int $id Device ID
 * @var string $name Device name
 * @var string $mac Device MAC address
 * @var string $imei Device IMEI
 * @var int $type Device type (1=weather detector, 2=camera)
 * @var string $date_zone_id Timezone ID
 * @var int $createtime Creation timestamp
 * @var float $longitude Longitude coordinate
 * @var float $latitude Latitude coordinate
 * @var string $stationtype Station type
 * @var string $connection_id Connection ID
 * @var array<int, PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\IOT> $iot_devices Array of IOT device components
 */
// Determine device status based on available data
$device_status  = ! empty( $name ) && ! empty( $mac ) ? 'active' : 'inactive';
$device_id_attr = 'device-' . absint( $id );
?>

<div class="device-card device--<?php echo esc_attr( $device_status ); ?>" id="<?php echo esc_attr( $device_id_attr ); ?>">
	
	<div class="device__header">
		<div class="device__title-group">
			<h3 class="device__title">
				<?php echo esc_html( $name ?: __( 'Unnamed Device', 'pinkcrab-weather-block' ) ); ?>
			</h3>
			<div class="device__type">
				<span class="type-badge type-badge--<?php echo esc_attr( strtolower( str_replace( ' ', '-', $type_label ) ) ); ?>">
					<?php echo esc_html( $type_label ); ?>
				</span>
			</div>
		</div>
		
		<div class="device__meta">
			<div class="device__id">
				<span class="meta-label"><?php esc_html_e( 'ID:', 'pinkcrab-weather-block' ); ?></span>
				<code class="meta-value"><?php echo esc_html( $id ); ?></code>
			</div>
		</div>
	</div>

	<div class="device__content">
		<div class="device__details">
			
			<?php if ( ! empty( $mac ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'MAC Address:', 'pinkcrab-weather-block' ); ?></span>
					<code class="detail__value"><?php echo esc_html( $mac ); ?></code>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $imei ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'IMEI:', 'pinkcrab-weather-block' ); ?></span>
					<code class="detail__value"><?php echo esc_html( $imei ); ?></code>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $stationtype ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Station Type:', 'pinkcrab-weather-block' ); ?></span>
					<span class="detail__value"><?php echo esc_html( $stationtype ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $date_zone_id ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Timezone:', 'pinkcrab-weather-block' ); ?></span>
					<span class="detail__value"><?php echo esc_html( $date_zone_id ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $longitude !== 0.0 || $latitude !== 0.0 ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Location:', 'pinkcrab-weather-block' ); ?></span>
					<span class="detail__value">
						<?php
						printf(
							/* translators: %1$s: latitude, %2$s: longitude */
							esc_html__( 'Lat: %1$s, Lng: %2$s', 'pinkcrab-weather-block' ),
							esc_html( number_format( $latitude, 6 ) ),
							esc_html( number_format( $longitude, 6 ) )
						);
						?>
					</span>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<?php if ( ! empty( $iot_devices ) ) : ?>
		<div class="device__iot-section">
			<div class="device__iot-header">
				<h4 class="device__iot-title">
					<?php
					$iot_count = count( $iot_devices );
					printf(
						/* translators: %d: number of IOT devices */
						esc_html( _n( '%d IOT Device', '%d IOT Devices', $iot_count, 'pinkcrab-weather-block' ) ),
						absint( $iot_count )
					);
					?>
				</h4>
			</div>
			<div class="device__iot-list">
				<?php foreach ( $iot_devices as $iot_device ) : ?>
					<?php $this->component( $iot_device ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<!-- Observation Section -->
	<div class="device__observation-section">
		<div class="device__observation-header">
			<h4 class="device__observation-title">
				<?php esc_html_e( 'Current Observations', 'pinkcrab-weather-block' ); ?>
			</h4>
		</div>
		
		<div class="device__observation-content">
			<div class="observation-placeholder">
				<div class="observation-placeholder__icon">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M3 15h4l3 2 3-4 4 1v-4"/>
						<path d="M21 6v6"/>
						<path d="M21 21v-6"/>
					</svg>
				</div>
				<p class="observation-placeholder__text">
					<?php esc_html_e( 'No observation data loaded. Click the button below to fetch current weather data.', 'pinkcrab-weather-block' ); ?>
				</p>
			</div>
			
			<div class="observation-actions">
				<button type="button" 
						class="btn btn--primary observation-fetch-btn" 
						data-device="<?php echo esc_attr( $mac ); ?>"
						data-connection="<?php echo esc_attr( $connection_id ); ?>"
						data-action="fetch-observation">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M1 4v6h6"/>
						<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
					</svg>
					<?php esc_html_e( 'Get Observation', 'pinkcrab-weather-block' ); ?>
				</button>
				
				<div class="observation-loading" style="display: none;">
					<div class="loading-spinner"></div>
					<span class="loading-text"><?php esc_html_e( 'Fetching observation data...', 'pinkcrab-weather-block' ); ?></span>
				</div>
			</div>
			
			<!-- This will be populated via AJAX -->
			<div class="observation-data" style="display: none;">
				<!-- Observation component will be rendered here via AJAX -->
			</div>
		</div>
	</div>

	<div class="device__footer">
		<div class="device__timestamp">
			<?php if ( ! empty( $creation_date ) ) : ?>
				<span class="timestamp-label"><?php esc_html_e( 'Created:', 'pinkcrab-weather-block' ); ?></span>
				<time class="timestamp-value" datetime="<?php echo esc_attr( gmdate( 'c', $createtime ) ); ?>">
					<?php echo esc_html( $creation_date ); ?>
				</time>
			<?php endif; ?>
		</div>
	</div>

</div>
