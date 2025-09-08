<?php

/**
 * The Settings_Page class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Settings\Page;

use PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader;
use PinkCrab\Enqueue\Enqueue;
use PinkCrab\Perique_Admin_Menu\Page\Page;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Perique_Admin_Menu\Page\Menu_Page;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings;
use PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections\Settings_Connections;
use PinkCrab\Perique\Application\App;

/**
 * The Settings_Page class.
 */
class Settings_Page extends Menu_Page {

	/**
	 * Holds the App Config.
	 *
	 * @var App_Config
	 */
	protected App_Config $app_config;

	/**
	 * Access to the Settings
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Page Handler for callbacks.
	 *
	 * @var Page_Handler
	 */
	protected Page_Handler $page_handler;

	/**
	 * Creates an instance of the Settings_Page.
	 *
	 * @param App_Config $app_config
	 * @param Settings   $settings
	 * @param Page_Handler $page_handler
	 */
	public function __construct( App_Config $app_config, Settings $settings, Page_Handler $page_handler ) {
		$this->app_config   = $app_config;
		$this->settings     = $settings;
		$this->page_handler = $page_handler;

		// Set the page details.
		$this->page_slug  = 'ecowitt-weather-block-settings';
		$this->menu_title = 'Ecowitt Weather Block';
		$this->page_title = 'Ecowitt Weather Block Settings';
		$this->position   = 10;

		$this->view_template = 'admin.settings.page';
		$this->view_data     = array(
			'app_config'     => $this->app_config,
			'settings'       => $this->settings,
			'page'           => $this,
			'connections'    => new Settings_Connections( $this->settings->connections()->all() ),
			'form_nonce_key' => $this->page_handler::FORM_NONCE_KEY,
			'submission_key' => $this->page_handler::SUBMISSION_KEY,
			'notifications'  => $this->page_handler->get_notifications(),
		);
	}

	/**
	 * Callback for enqueuing scripts and styles at a page level.
	 *
	 * @param Page $page
	 * @return void
	 */
	public function enqueue( Page $page ): void {

		// Assert that the view is set.
		assert( $this->view instanceof \PinkCrab\Perique\Services\View\View );

		Enqueue::script( 'ecowittSettings' )
			->src( Asset_Loader::assets_url() . 'js/settings.js' )
			->ver( $this->app_config->version() )
			->localize(
				array(
					'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
					'nonce'              => wp_create_nonce( 'ecowitt_settings_nonce' ),
					'connectionTemplate' => $this->view->render(
						'components.admin.settings.connection',
						array(
							'key'                    => '{key}',
							'connection_id'          => '{key}',
							'application_key'        => '{application_key}',
							'api_key'                => '{api_key}',
							'mac_address'            => '{mac_address}',
							'description'            => '{description}',
							'name'                   => '{name}',
							'application_key_masked' => '{application_key_masked}',
							'api_key_masked'         => '{api_key_masked}',
						),
						false
					),
				)
			)
			->register();
	}

	/**
	 * Callback for the pre-load of the page
	 *
	 * @param Page $page
	 * @return void
	 */
	public function load( Page $page ): void {
		// Check if the form was submitted.
		if ( isset( $_POST[ $this->page_handler::SUBMISSION_KEY ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, Only checking if set.
			$this->page_handler->handle_form_submission( $this );
			// Reload the settings.
			$this->settings = $this->page_handler->get_settings();
		}

		$dev = App::make( \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::class );
		dd(
			$this->settings->connections()->all(),
			$dev->get_all_devices( $this->settings->connections()->all()[2] )
		);
	}
}
