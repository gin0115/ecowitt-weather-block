<?php

/**
 * Tests for the WordPress HTTP Client class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Http;

use PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client;
use PinkCrab\Ecowitt_Weather_Block\Http\Response;
use PinkCrab\Ecowitt_Weather_Block\Tests\TestCase\HTTP_Request_TestCase;

/**
 * Tests for the WordPress HTTP Client class.
 *
 * @group unit
 * @group http
 */
class Test_WordPress_Client extends HTTP_Request_TestCase {

	/**
	 * @testdox It should be possible to make a successful GET request and receive a valid response.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_successful_get_request(): void {
		// Set up the mock response data
		$expected_body    = '{"success": true, "data": "test data"}';
		$expected_status  = 200;
		$expected_headers = array(
			'content-type'    => 'application/json',  // Headers are now lowercase after sanitization
			'x-custom-header' => 'custom-value',
		);

		$this->set_mock_response(
			$expected_body,
			$expected_status,
			array(
				'Content-Type'    => 'application/json',  // Original response headers
				'X-Custom-Header' => 'custom-value',
			)
		);

		// Create the client and make a request
		$client   = new WordPress_Client();
		$response = $client->request(
			'GET',
			'https://api.example.com/test',
			array( 'Authorization' => 'Bearer token123' ),
			null,
			array( 'timeout' => 30 )
		);

		// Assert the response is correct
		$this->assertInstanceOf( Response::class, $response );
		$this->assertSame( $expected_body, $response->body() );
		$this->assertSame( $expected_status, $response->status_code() );
		$this->assertSame( $expected_headers, $response->headers() );
	}

	/**
	 * Data provider for valid HTTP methods.
	 *
	 * @return array<string, array<string>>
	 */
	public function valid_http_methods_provider(): array {
		return array(
			'GET method'      => array( 'GET' ),
			'POST method'     => array( 'POST' ),
			'PUT method'      => array( 'PUT' ),
			'DELETE method'   => array( 'DELETE' ),
			'PATCH method'    => array( 'PATCH' ),
			'HEAD method'     => array( 'HEAD' ),
			'OPTIONS method'  => array( 'OPTIONS' ),
			'lowercase get'   => array( 'get' ),
			'mixed case post' => array( 'PoSt' ),
		);
	}

	/**
	 * @testdox It should accept all valid HTTP methods: $method
	 * @dataProvider valid_http_methods_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_valid_http_methods( string $method ): void {
		$this->set_mock_response( '{"success": true}', 200, array() );

		$client   = new WordPress_Client();
		$response = $client->request( $method, 'https://api.example.com/test' );

		$this->assertInstanceOf( Response::class, $response );
		$this->assertSame( 200, $response->status_code() );
	}

	/**
	 * Data provider for invalid HTTP methods.
	 *
	 * @return array<string, array<string>>
	 */
	public function invalid_http_methods_provider(): array {
		return array(
			'INVALID method' => array( 'INVALID' ),
			'TRACE method'   => array( 'TRACE' ),
			'CONNECT method' => array( 'CONNECT' ),
			'empty string'   => array( '' ),
			'random string'  => array( 'FOOBAR' ),
		);
	}

	/**
	 * @testdox It should reject invalid HTTP methods: $method
	 * @dataProvider invalid_http_methods_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_invalid_http_methods( string $method ): void {
		$client   = new WordPress_Client();
		$response = $client->request( $method, 'https://api.example.com/test' );

		$this->assertInstanceOf( Response::class, $response );
		$this->assertSame( 'Invalid HTTP method', $response->body() );
		$this->assertSame( 400, $response->status_code() );
	}

	/**
	 * Data provider for headers that need sanitization.
	 *
	 * @return array<string, array<array<string, string>, array<string, string>>>
	 */
	public function headers_escaping_provider(): array {
		return array(
			'basic headers'             => array(
				array( 'Content-Type' => 'application/json' ),
				array( 'content-type' => 'application/json' ),
			),
			'headers with quotes'       => array(
				array( 'X-Custom' => 'value"with"quotes' ),
				array( 'x-custom' => 'value"with"quotes' ),
			),
			'headers with ampersand'    => array(
				array( 'X-Data' => 'foo&bar' ),
				array( 'x-data' => 'foo&bar' ),
			),
			'headers with less than'    => array(
				array( 'X-Math' => '5<10' ),
				array( 'x-math' => '5<10' ),
			),
			'headers with greater than' => array(
				array( 'X-Compare' => '10>5' ),
				array( 'x-compare' => '10>5' ),
			),
			'multiple unsafe headers'   => array(
				array(
					'X-Quote' => 'test"value',
					'X-Amp'   => 'test&value',
					'X-Lt'    => 'test<value',
				),
				array(
					'x-quote' => 'test"value',
					'x-amp'   => 'test&value',
					'x-lt'    => 'test<value',
				),
			),
		);
	}

	/**
	 * @testdox It should properly sanitize header names and values
	 * @dataProvider headers_escaping_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_headers_escaping( array $input_headers, array $expected_escaped ): void {
		// Use response_callable to capture the actual arguments passed
		$captured_args           = null;
		$this->response_callable = function ( $response, $args, $url ) use ( &$captured_args ) {
			$captured_args = $args;
			return array(
				'body'     => '{"success": true}',
				'response' => array(
					'code'    => 200,
					'message' => 'OK',
				),
				'headers'  => array(),
				'cookies'  => array(),
			);
		};

		$client = new WordPress_Client();
		$client->request( 'GET', 'https://api.example.com/test', $input_headers );

		// Verify that the headers were properly sanitized
		$this->assertSame( $expected_escaped, $captured_args['headers'] );
	}

	/**
	 * @testdox It should handle POST requests with body data
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_post_request_with_body(): void {
		$request_body  = '{"name": "test", "value": 123}';
		$captured_args = null;

		// Use response_callable to capture the actual arguments passed
		$this->response_callable = function ( $response, $args, $url ) use ( &$captured_args ) {
			$captured_args = $args;
			return array(
				'body'     => '{"created": true}',
				'response' => array(
					'code'    => 201,
					'message' => 'Created',
				),
				'headers'  => array(),
				'cookies'  => array(),
			);
		};

		$client   = new WordPress_Client();
		$response = $client->request(
			'POST',
			'https://api.example.com/create',
			array( 'Content-Type' => 'application/json' ),
			$request_body
		);

		$this->assertInstanceOf( Response::class, $response );
		$this->assertSame( '{"created": true}', $response->body() );
		$this->assertSame( 201, $response->status_code() );
		$this->assertSame( 'POST', $captured_args['method'] );
		$this->assertSame( $request_body, $captured_args['body'] );
	}

	/**
	 * @testdox It should handle custom timeout options
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_custom_timeout_option(): void {
		$captured_args = null;

		// Use response_callable to capture the actual arguments passed
		$this->response_callable = function ( $response, $args, $url ) use ( &$captured_args ) {
			$captured_args = $args;
			return array(
				'body'     => '{"success": true}',
				'response' => array(
					'code'    => 200,
					'message' => 'OK',
				),
				'headers'  => array(),
				'cookies'  => array(),
			);
		};

		$client = new WordPress_Client();
		$client->request(
			'GET',
			'https://api.example.com/test',
			array(),
			null,
			array( 'timeout' => 45 )
		);

		$this->assertSame( 45, $captured_args['timeout'] );
	}

	/**
	 * @testdox It should use default timeout when none provided
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_default_timeout(): void {
		$captured_args = null;

		// Use response_callable to capture the actual arguments passed
		$this->response_callable = function ( $response, $args, $url ) use ( &$captured_args ) {
			$captured_args = $args;
			return array(
				'body'     => '{"success": true}',
				'response' => array(
					'code'    => 200,
					'message' => 'OK',
				),
				'headers'  => array(),
				'cookies'  => array(),
			);
		};

		$client = new WordPress_Client();
		$client->request( 'GET', 'https://api.example.com/test' );

		$this->assertSame( 15, $captured_args['timeout'] );
	}

	/**
	 * @testdox It should handle WP_Error responses and return 500 status
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_wp_error_response(): void {
		// Use the response_callable to return a WP_Error
		$this->response_callable = function ( $response, $args, $url ) {
			return new \WP_Error( 'http_request_failed', 'A valid URL was not provided.' );
		};

		$client   = new WordPress_Client();
		$response = $client->request( 'GET', 'https://invalid-url' );

		$this->assertInstanceOf( Response::class, $response );
		$this->assertSame( 'A valid URL was not provided.', $response->body() );
		$this->assertSame( 500, $response->status_code() );
	}

	/**
	 * @testdox It should handle different response status codes
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\WordPress_Client::request
	 */
	public function test_different_status_codes(): void {
		$status_codes = array( 200, 201, 400, 404, 500 );

		foreach ( $status_codes as $status_code ) {
			$this->set_mock_response( '{"status": "' . $status_code . '"}', $status_code, array() );

			$client   = new WordPress_Client();
			$response = $client->request( 'GET', 'https://api.example.com/test' );

			$this->assertSame( $status_code, $response->status_code() );
		}
	}
}
