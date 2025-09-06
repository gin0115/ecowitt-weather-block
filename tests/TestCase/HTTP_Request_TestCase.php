<?php

/**
 * Testcase for making HTTP Requests.
 *
 * @package PinkCrab\Ecowitt_Weather_Block\Tests
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\TestCase;

use WP_UnitTestCase;

/**
 * HTTP Request Test Case.
 */
class HTTP_Request_TestCase extends WP_UnitTestCase {

	/**
	 * Mock response body.
	 *
	 * @var string
	 */
	protected string $mock_response_body = '';

	/**
	 * Mock response status code.
	 */
	protected int $mock_response_status = 200;

	/**
	 * Mock response headers.
	 *
	 * @var array<string, string>
	 */
	protected array $mock_response_headers = array();

	/**
	 * Response callable, this is optional and can be used to provide dynamic responses.
	 *
	 * @var (callable(string $body, int $status, array $headers):array|\WP_Error)|null
	 */
	protected $response_callable = null;

	/**
	 * Sets the mock response data.
	 *
	 * @param string                $body    The response body.
	 * @param integer               $status  The response status code.
	 * @param array<string, string> $headers The response headers.
	 */
	protected function set_mock_response( string $body, int $status, array $headers ): void {
		$this->mock_response_body    = $body;
		$this->mock_response_status  = $status;
		$this->mock_response_headers = $headers;

		// Clear any callable.
		$this->response_callable = null;
	}

	/**
	 * Setup the test case.
	 */
	public function set_up() {
		parent::set_up();
		add_action( 'pre_http_request', array( $this, 'mock_request_response' ), 10, 3 );
	}

	/**
	 * Mock the request response.
	 *
	 * @param $ressponse ?
	 * @param $args
	 * @param $url
	 *
	 * @return array{body: string, response: array{code: int, message: string}, headers: array<string, string>, cookies: array}|\WP_Error
	 */
	public function mock_request_response( $response, $args, $url ) {
		// If we have a callable set, use that to generate the response.
		if ( is_callable( $this->response_callable ) ) {
			return call_user_func( $this->response_callable, $response, $args, $url );
		}

		return array(
			'body'     => $this->mock_response_body,
			'response' => array(
				'code'    => $this->mock_response_status,
				'message' => get_status_header_desc( $this->mock_response_status ),
			),
			'headers'  => $this->mock_response_headers,
			'cookies'  => array(),
		);
	}

	/**
	 * Teardown the test case.
	 */
	public function tear_down() {
		// Clears the mock response data.
		$this->mock_response_body    = '';
		$this->mock_response_status  = 200;
		$this->mock_response_headers = array();

		// Clear any callable.
		$this->response_callable = null;

		// Unset the filter.
		remove_action( 'pre_http_request', array( $this, 'mock_request_response' ), 10 );
		parent::tear_down();
	}
}
