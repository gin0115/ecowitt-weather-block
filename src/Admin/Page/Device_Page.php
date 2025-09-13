<?php

/**
 * Device Page.
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Admin\Page;

use PinkCrab\Enqueue\Enqueue;
use PinkCrab\Perique_Admin_Menu\Page\Page;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Perique_Admin_Menu\Page\Menu_Page;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings;
use PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader;
use PinkCrab\Ecowitt_Weather_Block\Settings\Page\Page_Handler;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt;
use PinkCrab\Ecowitt_Weather_Block\Settings\Page\Settings_Page;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;


/**
 * Device Page.
 */

class Device_Page extends Menu_Page {

	/**
	 * App Config.
	 *
	 * @var App_Config
	 */
	protected App_Config $app_config;

	/**
	 * Settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Ecowitt.
	 *
	 * @var Ecowitt
	 */
	protected Ecowitt $ecowitt;

	/**
	 * Selected connection.
	 *
	 * @var Connection|null
	 */
	protected ?Connection $selected_connection = null;


	/**
	 * Constructor.
	 *
	 * @param App_Config $app_config
	 * @param Settings $settings
	 * @param Ecowitt $ecowitt
	 */
	public function __construct( App_Config $app_config, Settings $settings, Ecowitt $ecowitt ) {
		$this->app_config          = $app_config;
		$this->settings            = $settings;
		$this->ecowitt             = $ecowitt;
		$this->selected_connection = $this->get_selected_connection();

		// Set the page details.
		$this->page_slug   = $this->app_config->admin_page_slug . '-devices';
		$this->menu_title  = 'Devices';
		$this->page_title  = 'Ecowitt Weather Block Devices';
		$this->position    = 10;
		$this->parent_slug = $this->app_config->admin_page_slug;

		$this->view_template = 'admin.device.page';
		$this->view_data     = array(
			'app_config'    => $this->app_config,
			'settings'      => $this->settings,
			'page'          => $this,
			'connection_id' => $this->selected_connection ? $this->selected_connection->key() : '',
			'should_load'   => $this->should_load(),
			'connection'    => $this->selected_connection,
		);
	}

	/**
	 * Checks if the page should be loaded.
	 *
	 * @return bool
	 */
	public function should_load(): bool {
		return $this->get_selected_connection() && is_a( $this->get_selected_connection(), Connection::class );
	}

	/**
	 * Load page
	 *
	 * @param Page $page
	 * @return void
	 */
	public function load( Page $page ): void {
		$this->view_data['settings_page_url'] = admin_url( 'admin.php?page=' . $this->app_config->admin_page_slug );

		// Get the devices.
		$connection                    = $this->get_selected_connection();
		$devices                       = $connection ? $this->ecowitt->with_connection( $connection )->get_devices() : array();
		$this->view_data['devices']    = $devices;
		$this->view_data['connection'] = $connection;
	}

	/**
	 * Get selected connection.
	 *
	 * @return ?Connection
	 */
	public function get_selected_connection(): ?Connection {
		// Check if connection id is in url.
		if ( ! isset( $_GET['connection_id'] ) && is_string( $_GET['connection_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, Only checking if set.
			return null;
		}

		// @phpstan-ignore-next-line, issue with wp_strip_all_tags() here.
		$connection_id = sanitize_text_field(wp_strip_all_tags( $_GET['connection_id']) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, Can be accessed safely.

		$connections = $this->settings->connections()->all();
		foreach ( $connections as $connection ) {
			if ( sanitize_text_field( $connection->key() ) === $connection_id ) {
				return $connection;
			}
		}
		return null;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param Page $page
	 * @return void
	 */
	public function enqueue( Page $page ): void {
		Enqueue::script( 'ecowittDevice' )
			->src( Asset_Loader::assets_url() . 'js/device.js' )
			->ver( $this->app_config->version() )
			->localize(
				array(
					'ajaxUrl'               => admin_url( 'admin-ajax.php' ),
					'liveObservationAction' => $this->app_config->ajax_live_observation_action,
					'liveObservationNonce'  => wp_create_nonce( $this->app_config->ajax_live_observation_nonce ),
				)
			)
			->register();
	}
}
