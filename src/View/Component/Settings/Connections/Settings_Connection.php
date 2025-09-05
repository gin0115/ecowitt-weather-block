<?php

/**
 * The settings page, connection component.
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

	public string $key         = '';
	public string $api_key     = '';
	public string $api_secret  = '';
	public string $mac_address = '';
	public string $description = '';
	public string $name        = '';

	/**
	 * Creates an instance of the Setting_Connection_Component.
	 *
	 * @param Connection $connection
	 */
	public function __construct( ?Connection $connection = null ) {
		if ( is_null( $connection ) ) {
			return;
		}

		$this->key         = $connection->key();
		$this->api_key     = $connection->api_key();
		$this->api_secret  = $connection->api_secret();
		$this->mac_address = $connection->mac_address();
		$this->description = $connection->description();
		$this->name        = $connection->name();
	}
}
