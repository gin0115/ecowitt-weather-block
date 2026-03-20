<?php

/**
 * Component: IOT Device
 * 
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var string $name IOT device name
 * @var string $default_title IOT device default title
 * @var string $device_id IOT device ID
 * @var string $version IOT device version
 * @var string $createtime IOT device creation time
 * @var array<string, mixed> $additional_data Additional IOT device data
 */

$iot_status = !empty($device_id) ? 'active' : 'inactive';
$safe_device_id = sanitize_title($device_id);
?>

<div class="iot-device iot-device--<?php echo esc_attr($iot_status); ?>" data-device-id="<?php echo esc_attr($safe_device_id); ?>">
    
    <div class="iot-device__header">
        <div class="iot-device__title-group">
            <h5 class="iot-device__title">
                <?php 
                $display_title = !empty($name) ? $name : $default_title;
                echo esc_html($display_title ?: __('Unnamed IOT Device', 'pinkcrab-weather-block')); 
                ?>
            </h5>
            <?php if (!empty($version)) : ?>
                <div class="iot-device__version">
                    <span class="version-badge">
                        <?php 
                        printf(
                            /* translators: %s: version number */
                            esc_html__('v%s', 'pinkcrab-weather-block'), 
                            esc_html($version)
                        ); 
                        ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="iot-device__content">
        <div class="iot-device__details">
            
            <?php if (!empty($device_id)) : ?>
                <div class="iot-device__detail">
                    <span class="detail__label"><?php esc_html_e('Device ID:', 'pinkcrab-weather-block'); ?></span>
                    <code class="detail__value detail__value--masked" title="<?php echo esc_attr($device_id); ?>">
                        <?php 
                        // Create masked device ID
                        $masked_id = '';
                        if (!empty($device_id)) {
                            $length = strlen($device_id);
                            if ($length <= 8) {
                                $masked_id = substr($device_id, 0, 2) . str_repeat('*', $length - 4) . substr($device_id, -2);
                            } else {
                                $masked_id = substr($device_id, 0, 4) . str_repeat('*', $length - 8) . substr($device_id, -4);
                            }
                        }
                        echo esc_html($masked_id); 
                        ?>
                    </code>
                </div>
            <?php endif; ?>

            <?php if (!empty($default_title) && $default_title !== $name) : ?>
                <div class="iot-device__detail">
                    <span class="detail__label"><?php esc_html_e('Default Title:', 'pinkcrab-weather-block'); ?></span>
                    <span class="detail__value"><?php echo esc_html($default_title); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($createtime) && is_numeric($createtime)) : ?>
                <div class="iot-device__detail">
                    <span class="detail__label"><?php esc_html_e('Created:', 'pinkcrab-weather-block'); ?></span>
                    <time class="detail__value" datetime="<?php echo esc_attr(date('c', (int)$createtime)); ?>">
                        <?php echo esc_html($this->creation_date()); ?>
                    </time>
                </div>
            <?php endif; ?>

            <?php if (!empty($additional_data)) : ?>
                <div class="iot-device__additional">
                    <details class="iot-device__additional-toggle">
                        <summary class="additional-toggle__label">
                            <?php esc_html_e('Additional Data', 'pinkcrab-weather-block'); ?>
                        </summary>
                        <div class="additional-data">
                            <?php foreach ($additional_data as $key => $value) : ?>
                                <div class="additional-data__item">
                                    <span class="additional-data__key"><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?>:</span>
                                    <span class="additional-data__value">
                                        <?php 
                                        if (is_array($value)) {
                                            echo esc_html(wp_json_encode($value, JSON_PRETTY_PRINT));
                                        } elseif (is_bool($value)) {
                                            echo esc_html($value ? __('Yes', 'pinkcrab-weather-block') : __('No', 'pinkcrab-weather-block'));
                                        } elseif (is_null($value)) {
                                            echo esc_html__('(null)', 'pinkcrab-weather-block');
                                        } else {
                                            echo esc_html((string)$value);
                                        }
                                        ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
