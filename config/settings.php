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
	'url'  => array(
		'assets' => plugins_url( '/', __DIR__ ) . 'assets/build/',
	),
	'path' => array(
		'assets' => dirname( __DIR__, 1 ) . '/assets/build/',
	),
);
