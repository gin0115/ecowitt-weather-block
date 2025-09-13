<?php

/**
 * Returns all the custom settings for the plugin.
 *
 * @see https://perique.info/core/App/setup#config
 * @since 0.1.0
 *
 * @return array<string, mixed>
 */
return array(
	'url'        => array(
		'assets' => plugins_url( '/', __DIR__ ) . 'assets/build/',
	),
	'path'       => array(
		'assets' => dirname( __DIR__, 1 ) . '/assets/build/',
	),
	'additional' => array(
		'ecowitt_api_base' => 'https://api.ecowitt.net/api/v3/',
		'admin_page_slug' => 'ecowitt-weather-block',
		'ajax_live_observation_action' => 'ecowitt_live_observation_action',
		'ajax_live_observation_nonce' => 'ecowitt_live_observation_nonce',
	),
);
