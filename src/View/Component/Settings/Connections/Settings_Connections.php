<?php

/**
 * The settings page, connections component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Settings\Connections;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

/**
 * The settings page, connections component.
 *
 * @view admin.settings.connections
 */
class Settings_Connections extends Component {

	/**
	 * The connections.
	 *
	 * @var array<int, Settings_Connection>
	 */
	protected array $connections = array();

	/**
	 * Creates an instance of the Setting_Connections_Component.
	 *
	 * @param array<int, mixed> $connections
	 */
	public function __construct( array $connections, string $device_base_path ) {
		$connections       = array_filter( $connections, fn( $connection ) => $connection instanceof Connection );
		$this->connections = array_map( fn( $connection ) => new Settings_Connection( $connection, $device_base_path ), $connections );
	}
}
