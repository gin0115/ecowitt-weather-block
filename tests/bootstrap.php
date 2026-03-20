<?php
/**
 * PHPUnit bootstrap file
 */

// Suppress deprecation notices from WordPress core and vendor libraries
// that are not yet compatible with PHP 8.5+, and WP 6.8+ notices about
// wp_is_block_theme() being called before theme directory is registered.
// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler
set_error_handler(
	static function ( $errno, $errstr ) {
		if ( $errno === E_DEPRECATED && (
			str_contains( $errstr, 'Non-canonical cast' )
			|| str_contains( $errstr, 'ReflectionProperty::setAccessible()' )
			|| str_contains( $errstr, 'array_key_exists()' )
		) ) {
			return true;
		}
		if ( ( $errno === E_NOTICE || $errno === E_USER_NOTICE ) && str_contains( $errstr, 'wp_is_block_theme' ) ) {
			return true;
		}
		return false;
	},
	E_DEPRECATED | E_NOTICE | E_USER_NOTICE
);

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Give access to tests_add_filter() function.
require_once getenv( 'WP_PHPUNIT__DIR' ) . '/includes/functions.php';

// Load all environment variables into $_ENV
try {
	$dotenv = Dotenv\Dotenv::createUnsafeImmutable( __DIR__ );
	$dotenv->load();
} catch (\Throwable $th) {
	// Do nothing if fails to find env as not used in pipeline.
}

define( 'FIXTURES_PATH', __DIR__ . '/Fixtures' );

// Start up the WP testing environment.
require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';