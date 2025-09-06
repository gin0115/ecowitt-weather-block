<?php

/**
 * Tests for the Response class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Http;

use PinkCrab\Ecowitt_Weather_Block\Http\Response;

/**
 * Tests for the Response class.
 *
 * @group unit
 * @group http
 */
class Test_Response extends \WP_UnitTestCase {

	/**
	 * @testdox It should create a response with default values
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::body
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::status_code
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers
	 */
	public function test_create_response_with_defaults(): void {
		$body     = '{"success": true}';
		$response = new Response( $body );

		$this->assertSame( $body, $response->body() );
		$this->assertSame( 200, $response->status_code() );
		$this->assertSame( array(), $response->headers() );
	}

	/**
	 * @testdox It should create a response with all parameters
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::body
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::status_code
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers
	 */
	public function test_create_response_with_all_parameters(): void {
		$body        = '{"error": "Not found"}';
		$status_code = 404;
		$headers     = array(
			'Content-Type' => 'application/json',
			'X-Custom'     => 'value',
		);

		// Expected headers after sanitization (lowercase keys)
		$expected_headers = array(
			'content-type' => 'application/json',
			'x-custom'     => 'value',
		);

		$response = new Response( $body, $status_code, $headers );

		$this->assertSame( $body, $response->body() );
		$this->assertSame( $status_code, $response->status_code() );
		$this->assertSame( $expected_headers, $response->headers() );
	}

	/**
	 * Data provider for different status codes.
	 *
	 * @return array<string, array<int>>
	 */
	public function status_codes_provider(): array {
		return array(
			'200 OK'                    => array( 200 ),
			'201 Created'               => array( 201 ),
			'400 Bad Request'           => array( 400 ),
			'401 Unauthorized'          => array( 401 ),
			'404 Not Found'             => array( 404 ),
			'500 Internal Server Error' => array( 500 ),
		);
	}

	/**
	 * @testdox It should handle different status codes correctly: $status_code
	 * @dataProvider status_codes_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::status_code
	 */
	public function test_different_status_codes( int $status_code ): void {
		$response = new Response( 'test body', $status_code );

		$this->assertSame( $status_code, $response->status_code() );
	}

	/**
	 * @testdox It should sanitize invalid header names and values during construction
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers
	 */
	public function test_headers_are_sanitized(): void {
		$invalid_headers = array(
			'X-Custom<script>' => 'value"with&quotes',  // Invalid chars in name
			'Content-Type'     => 'application/json',   // Valid header
			'X-Test@#$'        => "normal\x00value",    // Invalid chars in both
			'Cache-Control'    => 'no-cache',           // Valid header
			''                 => 'empty-name',         // Empty name
			'Valid-Name'       => '',                   // Empty value
			'UPPERCASE-HEADER' => 'should-be-lowercase',
		);

		$expected_headers = array(
			'x-customscript'   => 'value"with&quotes',  // Name sanitized, value kept as-is
			'content-type'     => 'application/json',   // Lowercase
			'x-test'           => 'normalvalue',        // Both sanitized
			'cache-control'    => 'no-cache',           // Lowercase
			'uppercase-header' => 'should-be-lowercase', // Lowercase
			// Empty name and empty value entries should be excluded
		);

		$response = new Response( 'test', 200, $invalid_headers );

		$this->assertSame( $expected_headers, $response->headers() );
	}

	/**
	 * Data provider for different response body types.
	 *
	 * @return array<string, array<string>>
	 */
	public function response_bodies_provider(): array {
		return array(
			'JSON response' => array( '{"success": true, "data": [1, 2, 3]}' ),
			'XML response'  => array( '<?xml version="1.0"?><root><item>test</item></root>' ),
			'HTML response' => array( '<html><body><h1>Hello World</h1></body></html>' ),
			'plain text'    => array( 'Simple plain text response' ),
			'empty string'  => array( '' ),
			'with newlines' => array( "Line 1\nLine 2\nLine 3" ),
			'with quotes'   => array( 'Response with "quotes" and \'apostrophes\'' ),
		);
	}

	/**
	 * @testdox It should handle different response body types correctly
	 * @dataProvider response_bodies_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::body
	 */
	public function test_different_response_bodies( string $body ): void {
		$response = new Response( $body );

		$this->assertSame( $body, $response->body() );
	}

	/**
	 * @testdox It should handle string status codes by converting them to integers
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::status_code
	 */
	public function test_status_code_conversion(): void {
		// Test with string that contains a number
		$response1 = new Response( 'test', 404 );
		$this->assertSame( 404, $response1->status_code() );

		// Test with negative number (should be converted to positive via absint)
		$response2 = new Response( 'test', -404 );
		$this->assertSame( 404, $response2->status_code() );
	}

	/**
	 * @testdox It should handle empty headers array
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers
	 */
	public function test_empty_headers(): void {
		$response = new Response( 'test body', 200, array() );

		$this->assertSame( array(), $response->headers() );
	}

	/**
	 * @testdox It should provide escaped headers for HTML output
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers_escaped
	 */
	public function test_headers_escaped_for_html(): void {
		$headers = array(
			'Content-Type' => 'application/json',
			'X-Custom'     => 'value<>&"test',
		);

		$response = new Response( 'test', 200, $headers );
		$escaped  = $response->headers_escaped();

		$expected = array(
			'content-type' => 'application/json',
			'x-custom'     => 'value&lt;&gt;&amp;&quot;test',
		);

		$this->assertSame( $expected, $escaped );
	}

	/**
	 * @testdox It should maintain header structure while sanitizing values
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::__construct
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Response::headers
	 */
	public function test_headers_structure_maintained(): void {
		$headers = array(
			'Content-Type'      => 'application/json',
			'Cache-Control'     => 'no-cache',
			'X-Custom-Header<>' => 'value&test',
		);

		$response = new Response( 'test', 200, $headers );
		$result   = $response->headers();

		// Should have same number of headers
		$this->assertCount( 3, $result );

		// Should have all original keys (but sanitized to lowercase)
		$this->assertArrayHasKey( 'content-type', $result );
		$this->assertArrayHasKey( 'cache-control', $result );
		$this->assertArrayHasKey( 'x-custom-header', $result );

		// Values should be kept as-is (not escaped at Response level)
		$this->assertSame( 'application/json', $result['content-type'] );
		$this->assertSame( 'no-cache', $result['cache-control'] );
		$this->assertSame( 'value&test', $result['x-custom-header'] );
	}
}
