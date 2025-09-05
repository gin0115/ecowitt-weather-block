<?php

/**
 * Handles the saving and loading of the settings.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Settings;

/**
 * Handles the saving and loading of the settings.
 */
class Settings_Repository {

	/**
	 * The key used to store the settings.
	 *
	 * @var string
	 */
	protected string $settings_key = 'ecowitt_weather_block_settings';


	/**
	 * Saves the settings.
	 *
	 * @param Settings $settings
	 *
	 * @return Settings|null
	 */
	public function save( Settings $settings ): ?Settings {
		update_option( $this->settings_key, json_encode( $settings ) );
		return $this->load();
	}

	/**
	 * Loads the settings.
	 *
	 * @return Settings|null
	 */
	public function load(): ?Settings {
		$settings = get_option( $this->settings_key, null );
		return $settings ? Settings::from_json( $settings ) : null;
	}
}
