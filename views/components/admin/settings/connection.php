<?php 

/**
 * Component: Connection 
 * 
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections\Settings_Connection $connection
 */

// Determine connection status (you can enhance this logic based on your needs)
$connection_status = !empty($api_key) && !empty($api_secret) && !empty($mac_address) ? 'active' : 'inactive';
$connection_id = !empty($key) ? $key : '__new_';
?>

<div class="connection-card connection--<?php echo esc_attr($connection_status); ?>" id="connection-<?php echo esc_attr($connection_id); ?>">
    
    <!-- Connection View State -->
    <div class="connection__view">
        <div class="connection__header">
            <div class="connection__title-group">
                <h3 class="connection__title">
                    <?php echo esc_html($name ?: __('Unnamed Connection', 'ecowitt-weather-block')); ?>
                </h3>
                <div class="connection__status">
                    <?php 
                    if ($connection_status === 'active') {
                        esc_html_e('Connected', 'ecowitt-weather-block');
                    } else {
                        esc_html_e('Not Configured', 'ecowitt-weather-block');
                    }
                    ?>
                </div>
            </div>
            
            <div class="connection__actions">
                <?php if ($connection_status === 'active') : ?>
                    <button class="btn btn--sm btn--outline" data-action="edit-connection" data-connection="<?php echo esc_attr($connection_id); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <?php esc_html_e('Edit', 'ecowitt-weather-block'); ?>
                    </button>
                    <button class="btn btn--sm btn--danger btn--outline" data-action="delete-connection" data-connection="<?php echo esc_attr($connection_id); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <?php esc_html_e('Delete', 'ecowitt-weather-block'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="connection__content">
            <?php if (!empty($description)) : ?>
                <p class="connection__description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>

            <div class="connection__details">
                <?php if (!empty($api_key)) : ?>
                    <div class="connection__detail">
                        <span class="detail__label"><?php esc_html_e('API Key:', 'ecowitt-weather-block'); ?></span>
                        <code class="detail__value detail__value--masked" title="<?php echo esc_attr(substr($api_key, 0, 4) . '••••••••'); ?>">
                            <?php echo esc_html(substr($api_key, 0, 4) . '••••••••'); ?>
                        </code>
                    </div>
                <?php endif; ?>

                <?php if (!empty($api_secret)) : ?>
                    <div class="connection__detail">
                        <span class="detail__label"><?php esc_html_e('API Secret:', 'ecowitt-weather-block'); ?></span>
                        <code class="detail__value detail__value--masked" title="<?php echo esc_attr(substr($api_secret, 0, 4) . '••••••••'); ?>">
                            <?php echo esc_html(substr($api_secret, 0, 4) . '••••••••'); ?>
                        </code>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mac_address)) : ?>
                    <div class="connection__detail">
                        <span class="detail__label"><?php esc_html_e('MAC Address:', 'ecowitt-weather-block'); ?></span>
                        <code class="detail__value"><?php echo esc_html($mac_address); ?></code>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($connection_status === 'active') : ?>
            <div class="connection__footer">
                <div class="connection__meta">
                    <span class="meta-item">
                        <strong><?php esc_html_e('Last Updated:', 'ecowitt-weather-block'); ?></strong>
                        <?php echo esc_html(date_i18n(get_option('date_format'), time())); ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Connection Edit State -->
    <div class="connection__edit" style="display: none;">
        <div class="connection__form">
            <input type="hidden" name="connection_key[<?php echo esc_attr($connection_id); ?>]" value="<?php echo esc_attr($key); ?>">
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label form-label--required" for="connection-<?php echo esc_attr($connection_id); ?>-name">
                        <?php esc_html_e('Connection Name', 'ecowitt-weather-block'); ?>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="connection-<?php echo esc_attr($connection_id); ?>-name" 
                           name="connection_name[<?php echo esc_attr($connection_id); ?>]" 
                           value="<?php echo esc_attr($name); ?>" 
                           placeholder="<?php esc_attr_e('My Weather Station', 'ecowitt-weather-block'); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label form-label--optional" for="connection-<?php echo esc_attr($connection_id); ?>-description">
                        <?php esc_html_e('Description', 'ecowitt-weather-block'); ?>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="connection-<?php echo esc_attr($connection_id); ?>-description" 
                           name="connection_description[<?php echo esc_attr($connection_id); ?>]" 
                           value="<?php echo esc_attr($description); ?>" 
                           placeholder="<?php esc_attr_e('Weather station in my backyard', 'ecowitt-weather-block'); ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label form-label--required" for="connection-<?php echo esc_attr($connection_id); ?>-api_key">
                        <?php esc_html_e('API Key', 'ecowitt-weather-block'); ?>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="connection-<?php echo esc_attr($connection_id); ?>-api_key" 
                           name="connection_api_key[<?php echo esc_attr($connection_id); ?>]" 
                           value="<?php echo esc_attr($api_key); ?>"
                           placeholder="<?php esc_attr_e('Your Ecowitt API Key', 'ecowitt-weather-block'); ?>">
                    <p class="form-help">
                        <?php esc_html_e('Get your API key from your Ecowitt account dashboard.', 'ecowitt-weather-block'); ?>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label form-label--required" for="connection-<?php echo esc_attr($connection_id); ?>-api_secret">
                        <?php esc_html_e('API Secret', 'ecowitt-weather-block'); ?>
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="connection-<?php echo esc_attr($connection_id); ?>-api_secret" 
                           name="connection_api_secret[<?php echo esc_attr($connection_id); ?>]" 
                           value="<?php echo esc_attr($api_secret); ?>"
                           placeholder="<?php esc_attr_e('Your Ecowitt API Secret', 'ecowitt-weather-block'); ?>">
                    <p class="form-help">
                        <?php esc_html_e('Keep your API secret secure and do not share it publicly.', 'ecowitt-weather-block'); ?>
                    </p>
                </div>
            </div>

            <div class="form-grid form-grid--single">
                <div class="form-group">
                    <label class="form-label form-label--required" for="connection-<?php echo esc_attr($connection_id); ?>-mac_address">
                        <?php esc_html_e('MAC Address', 'ecowitt-weather-block'); ?>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="connection-<?php echo esc_attr($connection_id); ?>-mac_address" 
                           name="connection_mac_address[<?php echo esc_attr($connection_id); ?>]" 
                           value="<?php echo esc_attr($mac_address); ?>"
                           placeholder="<?php esc_attr_e('AA:BB:CC:DD:EE:FF', 'ecowitt-weather-block'); ?>"
                           >
                    <p class="form-help">
                        00:1A:2B:3C:4D:5E
                        <?php esc_html_e('The MAC address of your weather station device.', 'ecowitt-weather-block'); ?>
                    </p>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn--success" name="save_connection" data-action="save-connection">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    <?php esc_html_e('Save Connection', 'ecowitt-weather-block'); ?>
                </button>
                
                <button type="button" class="btn btn--secondary" data-action="cancel-edit" data-connection="<?php echo esc_attr($connection_id); ?>">
                    <?php esc_html_e('Cancel', 'ecowitt-weather-block'); ?>
                </button>

                <?php if (!empty($key)) : ?>
                    <button type="button" class="btn btn--danger btn--outline ml-auto" data-action="delete-connection" data-connection="<?php echo esc_attr($connection_id); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <?php esc_html_e('Delete', 'ecowitt-weather-block'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>