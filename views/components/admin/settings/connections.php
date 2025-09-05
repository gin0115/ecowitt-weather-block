<?php

/**
 * Template: Connections Component
 * 
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections\Settings_Connections $connections
 */

use PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections\Settings_Connection;

?>

<div class="ecowitt-settings-section">
    <div class="ecowitt-settings-section__header">
        <h2 class="ecowitt-settings-section__title"><?php esc_html_e('Weather Station Connections', 'ecowitt-weather-block'); ?></h2>
    </div>
    
    <div class="ecowitt-settings-section__content">
        <div class="connections-container">
        <?php if (!empty($connections)) : ?>
            <div class="connections-list">
                <?php foreach ($connections as $connection_key => $connection): ?>
                    <?php $this->component($connection); ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
        <div class="connections-empty connections-list">
            <div class="empty-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h3 class="empty-title"><?php esc_html_e( 'No Connections Found', 'ecowitt-weather-block' ); ?></h3>
            <p class="empty-description">
                <?php esc_html_e( 'You haven\'t added any Ecowitt weather station connections yet. Add your first connection to get started.', 'ecowitt-weather-block' ); ?>
            </p>
        </div>
    <?php endif; ?>

    <?php // Debug info - remove in production ?>
    <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
        <details class="mt-xl">
            <summary>Debug Information</summary>
            <?php dump( get_defined_vars() ); ?>
        </details>
    <?php endif; ?>

    <?php // New connection section ?>
    <div class="new-connection" id="new-connection">
        <div class="new-connection__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </div>
        <h3 class="new-connection__title"><?php esc_html_e( 'Add New Connection', 'ecowitt-weather-block' ); ?></h3>
        <p class="new-connection__description">
            <?php esc_html_e( 'Connect your Ecowitt weather station to start displaying weather data in your WordPress site.', 'ecowitt-weather-block' ); ?>
        </p>
                <button type="button" 
                class="btn btn--primary btn--large"
                data-action="show-new-connection-form">
            Add New Connection
        </button>
    </div>

    <?php // New connection form ?>
    <div class="new-connection-form" id="new-connection-form" style="display: none;">
        <div class="form-header">
            <h3 class="form-title"><?php esc_html_e( 'Add New Connection', 'ecowitt-weather-block' ); ?></h3>
            <p class="form-description">
                <?php esc_html_e( 'Enter your Ecowitt weather station details below to create a new connection.', 'ecowitt-weather-block' ); ?>
            </p>
        </div>
        
        <div class="form-content">
            <?php $this->component(new Settings_Connection(null)); ?>
        </div>
        </div>
    </div>
</div>
</div>