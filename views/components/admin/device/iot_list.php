<?php

/**
 * Template: IOT Device List Component
 * 
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\IOT_List $this The component instance
 * @var array<int, PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\IOT> $iot_devices Array of IOT device components
 */

?>

<div class="ecowitt-iot-devices-section">
    <div class="ecowitt-iot-devices-section__header">
        <h3 class="ecowitt-iot-devices-section__title"><?php esc_html_e('IOT Devices', 'ecowitt-weather-block'); ?></h3>
        <?php if (!empty($iot_devices)) : ?>
            <div class="iot-devices-stats">
                <span class="iot-devices-count">
                    <?php 
                    $iot_count = count($iot_devices);
                    printf(
                        /* translators: %d: number of IOT devices */
                        esc_html(_n('%d Device', '%d Devices', $iot_count, 'ecowitt-weather-block')),
                        absint($iot_count)
                    );
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="ecowitt-iot-devices-section__content">
        <div class="iot-devices-container">
        <?php if (!empty($iot_devices)) : ?>
            <div class="iot-devices-list">
                <?php foreach ($iot_devices as $iot_device): ?>
                    <?php $this->component($iot_device); ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
        <div class="iot-devices-empty iot-devices-list">
            <div class="empty-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </div>
            <h4 class="empty-title"><?php esc_html_e( 'No IOT Devices Found', 'ecowitt-weather-block' ); ?></h4>
            <p class="empty-description">
                <?php esc_html_e( 'No IOT devices were found. IOT devices are additional sensors connected to your weather station.', 'ecowitt-weather-block' ); ?>
            </p>
        </div>
        <?php endif; ?>

        <?php // Debug info - remove in production ?>
        <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
            <details class="mt-lg">
                <summary>Debug Information</summary>
                <?php 
                echo '<pre>';
                $iot_count = count($iot_devices);
                echo esc_html(wp_json_encode([
                    'iot_devices_count' => $iot_count,
                    'has_iot_devices' => !empty($iot_devices),
                ], JSON_PRETTY_PRINT));
                echo '</pre>';
                ?>
            </details>
        <?php endif; ?>

        </div>
    </div>
</div>
