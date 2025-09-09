<?php

/**
 * Ecowitt HTTP Service.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service;

use PinkCrab\Ecowitt_Weather_Block\Http\Client_Interface;
use PinkCrab\Perique\Application\App_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ecowitt HTTP Service.
 */
class Ecowitt_Http_Service {

	/**
	 * Connection.
	 *
	 * @var Client_Interface
	 */
	private $client;

	/**
	 * App Config.
	 *
	 * @var App_Config
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @param Client_Interface $client
	 */
	public function __construct( Client_Interface $client, App_Config $config ) {
		$this->client = $client;
		$this->config = $config;
	}

	/**
	 * Make request to Ecowitt API.
	 *
	 * @param string                $endpoint
	 * @param array<string, string> $headers
	 * @param string|null           $body
	 * @param array<string, mixed>  $options
	 * @return \PinkCrab\Ecowitt_Weather_Block\Http\Response
	 */
	public function request( string $endpoint, array $headers = array(), ?string $body = null, array $options = array() ): \PinkCrab\Ecowitt_Weather_Block\Http\Response {
		return $this->client->request( 'GET', $endpoint, $headers, $body, $options );
	}
}
