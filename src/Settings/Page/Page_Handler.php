<?php

/**
 * The Page_Handler class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Settings\Page;

use PinkCrab\Perique_Admin_Menu\Page\Page;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings;
use PinkCrab\Ecowitt_Weather_Block\Admin\Notifications;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings_Repository;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections;
use PinkCrab\Ecowitt_Weather_Block\Settings\Exception\Validation_Failed;

class Page_Handler {
	public const FORM_NONCE_KEY = 'ecowitt_settings_nonce';
	public const SUBMISSION_KEY = 'ecowitt_settings_submit';

	/**
	 * Server Request
	 *
	 * @var ServerRequestInterface
	 */
	private ServerRequestInterface $request;

	/**
	 * The Settings Repository.
	 *
	 * @var Settings_Repository
	 */
	private Settings_Repository $settings;

	/**
	 * The form notifications.
	 *
	 * @var Notifications
	 */
	private Notifications $notifications;

    /**
     * Backup of settings before changes.
     * 
     * @var Settings|null
     */
	private ?Settings $backup_settings = null;

	/**
	 * Constructor.
	 *
	 * @param ServerRequestInterface $request
	 * @param Settings_Repository    $settings
	 * @param Notifications          $notifications
	 */
	public function __construct( ServerRequestInterface $request, Settings_Repository $settings, Notifications $notifications ) {
		$this->request       = $request;
		$this->settings      = $settings;
		$this->notifications = $notifications;
	}

    /**
     * Get the notifications instance.
     * 
     * @return Notifications
     */
    public function get_notifications(): Notifications {
        return $this->notifications;
    }

	/**
	 * Handle the save action.
	 *
	 * @param Page $page
	 * @return void
	 */
	public function handle_form_submission( Page $page ): void {
		// Check that form has been submitted (use ServerRequestInterface to get POST data
		$post_data = $this->request->getParsedBody();
		// \dd('post data', $post_data, $this, $_POST);
        if ( ! isset( $post_data[ self::SUBMISSION_KEY ] ) ) {
			throw new Validation_Failed( array( 'No submission key found.' ) );
		}
		// Verify nonce.
		if ( ! isset( $post_data[ self::FORM_NONCE_KEY ] ) || ! wp_verify_nonce( $post_data[ self::FORM_NONCE_KEY ], self::FORM_NONCE_KEY ) ) {
			throw new Validation_Failed( array( 'Invalid nonce.' ) );
		}

        // Backup current settings in case of failure.
        $this->backup_settings = $this->settings->load();

		// Process connections.
		$connections = $this->get_connections($post_data);
		$connections = Connections::from_array( $connections );
		$settings    = new Settings( $connections );
		
        // If the settings are the same, no need to save.
        if ( md5( json_encode( $settings ) ) === md5( json_encode( $this->backup_settings ) ) ) {
            $this->notifications->add_info( 'No changes detected, settings not updated.' );
            return;
        }
        
        
        $updated_settings = $this->settings->save( $settings );
		if ( ! $updated_settings ) {
			$this->notifications->add_error( 'Failed to save settings, reverting' );
			if ( $this->backup_settings ) {
				$this->settings->save( $this->backup_settings );
			}
			return;
		}

        // If 
		$this->notifications->add_success( 'Updated Connections' );

		// $updated_connections = $this->settings->upsert_connections( $connections );
		// if( $updated_connections ) {
		//     $this->notifications->add_success( 'Connections updated successfully.' );
		// }
	}

	/**
	 * Get the connections from the request.
	 *
	 * @return array<string, array<string, string>>
	 */
	public function get_connections( array $post_data ): array {
		$keys          = $post_data['connection_key'] ?? array();
		$names         = $post_data['connection_name'] ?? array();
		$descriptions  = $post_data['connection_description'] ?? array();
		$api_keys      = $post_data['connection_api_key'] ?? array();
		$api_secrets   = $post_data['connection_api_secret'] ?? array();
		$mac_addresses = $post_data['connection_mac_address'] ?? array();

		if ( 0 === count( $keys ) ) {
			return array();
		}

		// Itterate over the keys and build the connections array.
		$connections = array();
		foreach ( $keys as $id => $key ) {
            // If id starts with __new_, generate a unique key.
            if ( str_starts_with( (string) $id, '__new_' ) ) {
                $key = $this->get_unique_key( $names[ $id ] ?? 'connection', $connections );
            }
            
            // dd($key);
			$connections[ $key ] = array(
				'key'         => \esc_attr( $key ),
				'name'        => \esc_attr( $names[ $id ] ?? '' ),
				'description' => \esc_attr( $descriptions[ $id ] ?? '' ),
				'api_key'     => \esc_attr( $api_keys[ $id ] ?? '' ),
				'api_secret'  => \esc_attr( $api_secrets[ $id ] ?? '' ),
				'mac_address' => \esc_attr( $mac_addresses[ $id ] ?? '' ),
			);
		}

		return $connections;
	}

    /**
     * Get a unique key for a new connection.
     *
     * @param string $name The name of the connection.
     * @param array<string, array<string, string>> $connections
     * 
     * @return string
     */
    private function get_unique_key( string $name, array $connections ): string {
        $base_key = sanitize_title( $name );
        $key = $base_key;
        $i = 1;
        while ( isset( $connections[ $key ] ) ) {
            $key = $base_key . '_' . $i;
            $i++;
        }
        return $key;
    }
}