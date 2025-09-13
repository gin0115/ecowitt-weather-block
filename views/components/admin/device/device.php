<?php

/**
 * Component: Device
 *
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\Device $this The component instance
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
 * @var PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection $connection Connection object for API calls
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
				<?php echo esc_html( $name ?: __( 'Unnamed Device', 'ecowitt-weather-block' ) ); ?>
			</h3>
			<div class="device__type">
				<?php
				$type_label = match ( $type ) {
					1 => 'Weather Detector',
					2 => 'Camera',
					default => 'Unknown'
				};
				?>
				<span class="type-badge type-badge--<?php echo esc_attr( strtolower( str_replace( ' ', '-', $type_label ) ) ); ?>">
					<?php echo esc_html( $type_label ); ?>
				</span>
			</div>
		</div>
		
		<div class="device__meta">
			<div class="device__id">
				<span class="meta-label"><?php esc_html_e( 'ID:', 'ecowitt-weather-block' ); ?></span>
				<code class="meta-value"><?php echo esc_html( $id ); ?></code>
			</div>
		</div>
	</div>

	<div class="device__content">
		<div class="device__details">
			
			<?php if ( ! empty( $mac ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'MAC Address:', 'ecowitt-weather-block' ); ?></span>
					<code class="detail__value"><?php echo esc_html( $mac ); ?></code>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $imei ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'IMEI:', 'ecowitt-weather-block' ); ?></span>
					<code class="detail__value"><?php echo esc_html( $imei ); ?></code>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $stationtype ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Station Type:', 'ecowitt-weather-block' ); ?></span>
					<span class="detail__value"><?php echo esc_html( $stationtype ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $date_zone_id ) ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Timezone:', 'ecowitt-weather-block' ); ?></span>
					<span class="detail__value"><?php echo esc_html( $date_zone_id ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $longitude !== 0.0 || $latitude !== 0.0 ) : ?>
				<div class="device__detail">
					<span class="detail__label"><?php esc_html_e( 'Location:', 'ecowitt-weather-block' ); ?></span>
					<span class="detail__value">
						<?php
						printf(
							/* translators: %1$s: latitude, %2$s: longitude */
							esc_html__( 'Lat: %1$s, Lng: %2$s', 'ecowitt-weather-block' ),
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
						esc_html( _n( '%d IOT Device', '%d IOT Devices', $iot_count, 'ecowitt-weather-block' ) ),
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
				<?php esc_html_e( 'Current Observations', 'ecowitt-weather-block' ); ?>
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
					<?php esc_html_e( 'No observation data loaded. Click the button below to fetch current weather data.', 'ecowitt-weather-block' ); ?>
				</p>
			</div>
			
			<div class="observation-actions">
				<button type="button" 
						class="btn btn--primary observation-fetch-btn" 
						data-device-json="<?php echo esc_attr( wp_json_encode( $device ) ); ?>"
						data-connection-json="<?php echo esc_attr( wp_json_encode( $connection ) ); ?>"
						data-action="fetch-observation">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M1 4v6h6"/>
						<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
					</svg>
					<?php esc_html_e( 'Get Observation', 'ecowitt-weather-block' ); ?>
				</button>
				
				<div class="observation-loading" style="display: none;">
					<div class="loading-spinner"></div>
					<span class="loading-text"><?php esc_html_e( 'Fetching observation data...', 'ecowitt-weather-block' ); ?></span>
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
			<?php if ( $createtime > 0 ) : ?>
				<span class="timestamp-label"><?php esc_html_e( 'Created:', 'ecowitt-weather-block' ); ?></span>
				<time class="timestamp-value" datetime="<?php echo esc_attr( date( 'c', $createtime ) ); ?>">
					<?php echo esc_html( $this->creation_date() ); ?>
				</time>
			<?php endif; ?>
		</div>
	</div>

</div>
