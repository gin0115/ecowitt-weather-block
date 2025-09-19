<?php

/**
 * The sett public string $key             = '';
	public string $application_key = '';
	public string $api_key         = '';
	public string $mac_address     = '';
	public string $description     = '';
	public string $name            = '';age, connection component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

/**
 * The settings page, connection component.
 *
 * @view admin.settings.connection
 */
class Settings_Connection extends Component {

	public string $key              = '';
	public string $application_key  = '';
	public string $api_key          = '';
	public string $mac_address      = '';
	public string $description      = '';
	public string $name             = '';
	public string $device_base_path = '';

	/**
	 * Creates an instance of the Setting_Connection_Component.
	 *
	 * @param Connection $connection Connection.
	 * @param string $device_base_path Path to the base of the device page.
	 */
	public function __construct( Connection $connection, string $device_base_path ) {
		$this->key              = $connection->key();
		$this->application_key  = $connection->application_key();
		$this->api_key          = $connection->api_key();
		$this->mac_address      = $connection->mac_address();
		$this->description      = $connection->description();
		$this->name             = $connection->name();
		$this->device_base_path = $device_base_path;
	}
}
