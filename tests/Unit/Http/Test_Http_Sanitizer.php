<?php

/**
 * Tests for the Http_Sanitizer class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Http;

use PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer;

/**
 * Tests for the Http_Sanitizer class.
 *
 * @group unit
 * @group http
 */
class Test_Http_Sanitizer extends \WP_UnitTestCase {

	/**
	 * @testdox It should sanitize header names by removing invalid characters and converting to lowercase
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_header_name
	 */
	public function test_sanitize_header_name(): void {
		// Valid header names should be converted to lowercase
		$this->assertSame( 'content-type', Http_Sanitizer::sanitize_header_name( 'Content-Type' ) );
		$this->assertSame( 'x-custom-header', Http_Sanitizer::sanitize_header_name( 'X-Custom-Header' ) );
		$this->assertSame( 'authorization', Http_Sanitizer::sanitize_header_name( 'AUTHORIZATION' ) );

		// Invalid characters should be removed
		$this->assertSame( 'x-customscript', Http_Sanitizer::sanitize_header_name( 'X-Custom<script>' ) );
		$this->assertSame( 'x-test', Http_Sanitizer::sanitize_header_name( 'X-Test@#$%' ) );
		$this->assertSame( 'accept', Http_Sanitizer::sanitize_header_name( 'Accept*()' ) );

		// Empty strings should return empty
		$this->assertSame( '', Http_Sanitizer::sanitize_header_name( '' ) );
		$this->assertSame( '', Http_Sanitizer::sanitize_header_name( '@#$%' ) );

		// Numbers and valid characters should be preserved
		$this->assertSame( 'x-test-123_header', Http_Sanitizer::sanitize_header_name( 'X-Test-123_Header' ) );
	}

	/**
	 * @testdox It should sanitize header values by removing control characters
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_header_value
	 */
	public function test_sanitize_header_value(): void {
		// Valid values should be returned as-is (after trimming)
		$this->assertSame( 'application/json', Http_Sanitizer::sanitize_header_value( 'application/json' ) );
		$this->assertSame( 'Bearer token123', Http_Sanitizer::sanitize_header_value( '  Bearer token123  ' ) );
		$this->assertSame( 'value"with&quotes', Http_Sanitizer::sanitize_header_value( 'value"with&quotes' ) );

		// Control characters should be removed (except TAB which is allowed)
		$this->assertSame( 'normalvalue', Http_Sanitizer::sanitize_header_value( "normal\x00value" ) );
		$this->assertSame( 'testvalue', Http_Sanitizer::sanitize_header_value( "test\x01\x02value" ) );
		$this->assertSame( 'helloworld', Http_Sanitizer::sanitize_header_value( "hello\x7Fworld" ) );

		// TAB should be preserved
		$this->assertSame( "hello\tworld", Http_Sanitizer::sanitize_header_value( "hello\tworld" ) );

		// Empty values should return false
		$this->assertNull( Http_Sanitizer::sanitize_header_value( '' ) );
		$this->assertNull( Http_Sanitizer::sanitize_header_value( '   ' ) );
		$this->assertNull( Http_Sanitizer::sanitize_header_value( "\x00\x01\x02" ) );
	}

	/**
	 * @testdox It should sanitize complete header arrays
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers(): void {
		$headers = array(
			'Content-Type'      => 'application/json',
			'X-Custom<script>'  => 'value"with&quotes',
			'Authorization'     => '  Bearer token123  ',
			'X-Test@#$'         => "normal\x00value",
			''                  => 'empty-name',
			'Valid-Name'        => '',
			'UPPERCASE-HEADER'  => 'should-be-lowercase',
			'Control-Character' => "\x01\x02invalid",
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'content-type'      => 'application/json',
			'x-customscript'    => 'value"with&quotes',
			'authorization'     => 'Bearer token123',
			'x-test'            => 'normalvalue',
			'uppercase-header'  => 'should-be-lowercase',
			'control-character' => 'invalid',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should validate header names correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::is_valid_header_name
	 */
	public function test_is_valid_header_name(): void {
		// Valid header names
		$this->assertTrue( Http_Sanitizer::is_valid_header_name( 'Content-Type' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_name( 'X-Custom-Header' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_name( 'Authorization' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_name( 'X-Test-123_Header' ) );

		// Invalid header names
		$this->assertFalse( Http_Sanitizer::is_valid_header_name( 'X-Custom<script>' ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_name( 'X-Test@#$' ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_name( 'Accept*()' ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_name( '' ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_name( 'Header With Spaces' ) );
	}

	/**
	 * @testdox It should validate header values correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::is_valid_header_value
	 */
	public function test_is_valid_header_value(): void {
		// Valid header values
		$this->assertTrue( Http_Sanitizer::is_valid_header_value( 'application/json' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_value( 'Bearer token123' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_value( 'value"with&quotes' ) );
		$this->assertTrue( Http_Sanitizer::is_valid_header_value( "hello\tworld" ) ); // TAB is allowed

		// Invalid header values (contain control characters)
		$this->assertFalse( Http_Sanitizer::is_valid_header_value( "normal\x00value" ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_value( "test\x01value" ) );
		$this->assertFalse( Http_Sanitizer::is_valid_header_value( "hello\x7Fworld" ) );
	}

	/**
	 * @testdox It should sanitize URLs correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_url
	 */
	public function test_sanitize_url(): void {
		// Valid URLs
		$this->assertSame( 'https://api.example.com/test', Http_Sanitizer::sanitize_url( 'https://api.example.com/test' ) );
		$this->assertSame( 'http://localhost:8080/api', Http_Sanitizer::sanitize_url( '  http://localhost:8080/api  ' ) );
		$this->assertSame( 'https://example.com/path?param=value', Http_Sanitizer::sanitize_url( 'https://example.com/path?param=value' ) );

		// Invalid URLs
		$this->assertNull( Http_Sanitizer::sanitize_url( 'not-a-url' ) );
		$this->assertNull( Http_Sanitizer::sanitize_url( '' ) );
		$this->assertNull( Http_Sanitizer::sanitize_url( 'ftp://example.com' ) ); // Wrong scheme
		$this->assertNull( Http_Sanitizer::sanitize_url( 'javascript:alert()' ) ); // Wrong scheme
	}

	/**
	 * @testdox It should sanitize HTTP methods correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_http_method
	 */
	public function test_sanitize_http_method(): void {
		// Valid methods (should be converted to uppercase)
		$this->assertSame( 'GET', Http_Sanitizer::sanitize_http_method( 'get' ) );
		$this->assertSame( 'POST', Http_Sanitizer::sanitize_http_method( 'post' ) );
		$this->assertSame( 'PUT', Http_Sanitizer::sanitize_http_method( '  put  ' ) );
		$this->assertSame( 'DELETE', Http_Sanitizer::sanitize_http_method( 'DELETE' ) );
		$this->assertSame( 'PATCH', Http_Sanitizer::sanitize_http_method( 'Patch' ) );
		$this->assertSame( 'HEAD', Http_Sanitizer::sanitize_http_method( 'head' ) );
		$this->assertSame( 'OPTIONS', Http_Sanitizer::sanitize_http_method( 'options' ) );

		// Invalid methods
		$this->assertNull( Http_Sanitizer::sanitize_http_method( 'INVALID' ) );
		$this->assertNull( Http_Sanitizer::sanitize_http_method( '' ) );
		$this->assertNull( Http_Sanitizer::sanitize_http_method( 'TRACE' ) ); // Not in allowed list
		$this->assertNull( Http_Sanitizer::sanitize_http_method( 'CONNECT' ) ); // Not in allowed list
	}

	/**
	 * Data provider for different header scenarios.
	 *
	 * @return array<string, array<array<string, string>, array<string, string>>>
	 */
	public function header_scenarios_provider(): array {
		return array(
			'standard headers'                => array(
				array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer token123',
					'Accept'        => 'application/json',
				),
				array(
					'content-type'  => 'application/json',
					'authorization' => 'Bearer token123',
					'accept'        => 'application/json',
				),
			),
			'headers with special characters' => array(
				array(
					'X-API-Key<script>' => 'api-key-123',
					'X-Custom@Header'   => 'custom-value',
					'Content-Length'    => '1024',
				),
				array(
					'x-api-keyscript' => 'api-key-123',
					'x-customheader'  => 'custom-value',
					'content-length'  => '1024',
				),
			),
			'headers with control characters' => array(
				array(
					'X-Test-Header'  => "value\x00with\x01control",
					'X-Valid-Header' => 'normal-value',
					'X-Tab-Header'   => "value\twith\ttabs",
				),
				array(
					'x-test-header'  => 'valuewithcontrol',
					'x-valid-header' => 'normal-value',
					'x-tab-header'   => "value\twith\ttabs",
				),
			),
		);
	}

	/**
	 * @testdox It should handle different header scenarios correctly
	 * @dataProvider header_scenarios_provider
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_different_header_scenarios( array $input, array $expected ): void {
		$result = Http_Sanitizer::sanitize_headers( $input );
		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should sanitize array header values by joining with commas
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_array_values(): void {
		$headers = array(
			'Content-Type'    => 'application/json',
			'Accept'          => array( 'application/json', 'text/html', 'application/xml' ),
			'X-Custom-Header' => array( 'value1', 'value2', 'value3' ),
			'Authorization'   => array( 'Bearer token1', 'Bearer token2' ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'content-type'    => 'application/json',
			'accept'          => 'application/json, text/html, application/xml',
			'x-custom-header' => 'value1, value2, value3',
			'authorization'   => 'Bearer token1, Bearer token2',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should sanitize array header values and filter out invalid ones
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_mixed_array_values(): void {
		$headers = array(
			'Accept'           => array( 'application/json', '', 'text/html', '   ', 'application/xml' ),
			'X-Test-Header'    => array( 'valid-value', "invalid\x00value", 'another-valid' ),
			'X-Control-Header' => array( "value\x01with\x02control", 'normal-value', '' ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'accept'           => 'application/json, text/html, application/xml',
			'x-test-header'    => 'valid-value, invalidvalue, another-valid',
			'x-control-header' => 'valuewithcontrol, normal-value',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle arrays with all invalid values by excluding the header
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_all_invalid_array_values(): void {
		$headers = array(
			'Valid-Header'  => 'valid-value',
			'X-All-Empty'   => array( '', '   ', "\x00\x01\x02" ),
			'X-All-Control' => array( "value\x00with\x01control", "another\x7Finvalid" ),
			'X-Mixed-Valid' => array( 'valid', '', 'also-valid' ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'valid-header'  => 'valid-value',
			'x-all-control' => 'valuewithcontrol, anotherinvalid',
			'x-mixed-valid' => 'valid, also-valid',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle empty arrays by excluding the header
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_empty_arrays(): void {
		$headers = array(
			'Valid-Header'  => 'valid-value',
			'X-Empty-Array' => array(),
			'X-Another'     => array( 'valid-value' ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'valid-header' => 'valid-value',
			'x-another'    => 'valid-value',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle arrays with numeric keys correctly
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_numeric_array_keys(): void {
		$headers = array(
			'Accept' => array(
				0 => 'application/json',
				1 => 'text/html',
				2 => 'application/xml',
			),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'accept' => 'application/json, text/html, application/xml',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle arrays with mixed data types by converting to strings
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_mixed_data_types(): void {
		$headers = array(
			'X-Mixed-Types' => array( 'string', 123, 45.67, true, false ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'x-mixed-types' => 'string, 123, 45.67, 1',
		);

		$this->assertSame( $expected, $result );
	}

	/**
	 * @testdox It should handle arrays with whitespace-only values by filtering them out
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Http\Http_Sanitizer::sanitize_headers
	 */
	public function test_sanitize_headers_with_whitespace_array_values(): void {
		$headers = array(
			'X-Whitespace' => array( '  ', "\t", "\n", 'valid-value', '   ' ),
		);

		$result = Http_Sanitizer::sanitize_headers( $headers );

		$expected = array(
			'x-whitespace' => 'valid-value',
		);

		$this->assertSame( $expected, $result );
	}
}
