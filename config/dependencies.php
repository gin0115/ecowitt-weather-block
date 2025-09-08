<?php

/**
 * Returns all the custom DI dependencies for the plugin.
 *
 * @see https://perique.info/core/App/setup#registration
 * @since 0.1.0
 *
 * @return array<string, mixed>
 */

use Dice\Dice;
use PinkCrab\HTTP\HTTP_Helper;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings;
use PinkCrab\Ecowitt_Weather_Block\Http\Client_Interface;
use PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client;
use PinkCrab\Ecowitt_Weather_Block\Settings\Settings_Repository;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections;

return array(
    '*' =>[
        'substitutions' => [
             Settings::class => array(
                \Dice\Dice::INSTANCE => function(){
                    // Get the connections from settings.
                    $settings = new Settings_Repository();
                    $saved = $settings->load();
                    return $saved ?? new Settings( new Connections() );
                }
            ),
            ServerRequestInterface::class => array(
                \Dice\Dice::INSTANCE => function() {
                    return HTTP_Helper::global_server_request();
                }
            ),
            Client_Interface::class => array(
                \Dice\Dice::INSTANCE => function() {
                    return new WordPress_Client();
                }
            ),
        ],
    ]
);
