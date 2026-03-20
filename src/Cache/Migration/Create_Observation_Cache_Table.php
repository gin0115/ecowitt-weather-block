<?php

/**
 * Migration for the observation cache table.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Cache\Migration;

use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Perique\Migration\Migration;
use PinkCrab\Table_Builder\Schema;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Creates the ecowitt_observation_cache table.
 */
class Create_Observation_Cache_Table extends Migration {

	/**
	 * App config.
	 *
	 * @var App_Config
	 */
	private App_Config $app_config;

	/**
	 * Constructor.
	 *
	 * @param App_Config $app_config The app config.
	 */
	public function __construct( App_Config $app_config ) {
		$this->app_config = $app_config;
		parent::__construct();
	}

	/**
	 * Get the table name.
	 *
	 * @return string
	 */
	public function table_name(): string {
		return $this->app_config->db_tables( 'observation_cache' );
	}

	/**
	 * Define the table schema.
	 *
	 * @param Schema $schema The schema builder.
	 * @return void
	 */
	public function schema( Schema $schema ): void {
		$schema->column( 'id' )->unsigned_int( 11 )->auto_increment();
		$schema->column( 'station' )->varchar( 100 );
		$schema->column( 'mac' )->varchar( 20 );
		$schema->column( 'timestamp' )->unsigned_int( 11 );
		$schema->column( 'data' )->text();
		$schema->column( 'created_at' )->datetime();

		$schema->index( 'id' )->primary();
		$schema->index( 'station' );
		$schema->index( 'mac' );
		$schema->index( 'timestamp' );
	}

	/**
	 * Drop on deactivation.
	 *
	 * @return boolean
	 */
	public function drop_on_deactivation(): bool {
		return false;
	}

	/**
	 * Drop on uninstall.
	 *
	 * @return boolean
	 */
	public function drop_on_uninstall(): bool {
		return true;
	}
}
