<?php

/**
 * Test class for Ecowitt API Exception classes.
 *
 * @package PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\Exception
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\Exception;

use WP_UnitTestCase;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Ecowitt_Exception;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Connection_Exception;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Device_Exception;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Http_Exception;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Observation_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// @codeCoverageIgnoreEnd

/**
 * Test class for Ecowitt API Exception classes.
 *
 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Ecowitt_Exception
 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Connection_Exception
 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Device_Exception
 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Http_Exception
 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Exception\Observation_Exception
 */
class Test_Ecowitt_Exceptions extends WP_UnitTestCase {

    /**
     * Test that Ecowitt_Exception extends Exception.
     */
    public function test_ecowitt_exception_extends_exception(): void {
        $exception = new Ecowitt_Exception( 'Test message' );
        $this->assertInstanceOf( \Exception::class, $exception );
    }

    /**
     * Test that all specific exceptions extend Ecowitt_Exception.
     */
    public function test_all_exceptions_extend_ecowitt_exception(): void {
        $exceptions = [
            new Connection_Exception( 'Test' ),
            new Device_Exception( 'Test' ),
            new Http_Exception( 'Test' ),
            new Observation_Exception( 'Test' ),
        ];

        foreach ( $exceptions as $exception ) {
            $this->assertInstanceOf( Ecowitt_Exception::class, $exception );
            $this->assertInstanceOf( \Exception::class, $exception );
        }
    }

    /**
     * Test basic Ecowitt_Exception functionality.
     */
    public function test_ecowitt_exception_basic_functionality(): void {
        $message = 'Test exception message';
        $code = 123;
        $exception = new Ecowitt_Exception( $message, $code );
        
        $this->assertSame( $message, $exception->getMessage() );
        $this->assertSame( $code, $exception->getCode() );
    }

    // ===== CONNECTION EXCEPTION TESTS =====

    /**
     * Test Connection_Exception missing_connection static factory method.
     */
    public function test_connection_exception_missing_connection(): void {
        $exception = Connection_Exception::missing_connection();
        
        $this->assertInstanceOf( Connection_Exception::class, $exception );
        $this->assertStringContainsString( 'No connection has been set. Please use with_connection() method first.', $exception->getMessage() );
    }

    /**
     * Test Connection_Exception invalid_connection static factory method.
     */
    public function test_connection_exception_invalid_connection(): void {
        $reason = 'Invalid API key';
        $exception = Connection_Exception::invalid_connection( $reason );
        
        $this->assertInstanceOf( Connection_Exception::class, $exception );
        $this->assertStringContainsString( 'Invalid connection: Invalid API key', $exception->getMessage() );
    }

    /**
     * Test Connection_Exception invalid_connection with special characters.
     */
    public function test_connection_exception_invalid_connection_with_special_characters(): void {
        $reason = '<script>alert("xss")</script>Invalid connection';
        $exception = Connection_Exception::invalid_connection( $reason );
        
        $this->assertInstanceOf( Connection_Exception::class, $exception );
        $this->assertStringContainsString( 'Invalid connection:', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    // ===== DEVICE EXCEPTION TESTS =====

    /**
     * Test Device_Exception device_not_found static factory method.
     */
    public function test_device_exception_device_not_found(): void {
        $device_id = 'device_123';
        $exception = Device_Exception::device_not_found( $device_id );
        
        $this->assertInstanceOf( Device_Exception::class, $exception );
        $this->assertStringContainsString( "Device with ID 'device_123' not found.", $exception->getMessage() );
    }

    /**
     * Test Device_Exception device_not_found with special characters.
     */
    public function test_device_exception_device_not_found_with_special_characters(): void {
        $device_id = '<script>alert("xss")</script>device_123';
        $exception = Device_Exception::device_not_found( $device_id );
        
        $this->assertInstanceOf( Device_Exception::class, $exception );
        $this->assertStringContainsString( 'Device with ID', $exception->getMessage() );
        $this->assertStringContainsString( 'not found.', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    /**
     * Test Device_Exception invalid_device static factory method.
     */
    public function test_device_exception_invalid_device(): void {
        $reason = 'Device is offline';
        $exception = Device_Exception::invalid_device( $reason );
        
        $this->assertInstanceOf( Device_Exception::class, $exception );
        $this->assertStringContainsString( 'Invalid device: Device is offline', $exception->getMessage() );
    }

    /**
     * Test Device_Exception operation_failed static factory method.
     */
    public function test_device_exception_operation_failed(): void {
        $operation = 'fetch_data';
        $reason = 'Network timeout';
        $exception = Device_Exception::operation_failed( $operation, $reason );
        
        $this->assertInstanceOf( Device_Exception::class, $exception );
        $this->assertStringContainsString( "Device operation 'fetch_data' failed: Network timeout", $exception->getMessage() );
    }

    /**
     * Test Device_Exception operation_failed with special characters.
     */
    public function test_device_exception_operation_failed_with_special_characters(): void {
        $operation = '<script>alert("xss")</script>fetch_data';
        $reason = 'Network<script> timeout';
        $exception = Device_Exception::operation_failed( $operation, $reason );
        
        $this->assertInstanceOf( Device_Exception::class, $exception );
        $this->assertStringContainsString( 'Device operation', $exception->getMessage() );
        $this->assertStringContainsString( 'failed:', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    // ===== HTTP EXCEPTION TESTS =====

    /**
     * Test Http_Exception request_failed static factory method.
     */
    public function test_http_exception_request_failed(): void {
        $status_code = 404;
        $message = 'Not Found';
        $exception = Http_Exception::request_failed( $status_code, $message );
        
        $this->assertInstanceOf( Http_Exception::class, $exception );
        $this->assertStringContainsString( 'HTTP request failed with status 404: Not Found', $exception->getMessage() );
    }

    /**
     * Test Http_Exception request_failed with special characters.
     */
    public function test_http_exception_request_failed_with_special_characters(): void {
        $status_code = 500;
        $message = '<script>alert("xss")</script>Internal Server Error';
        $exception = Http_Exception::request_failed( $status_code, $message );
        
        $this->assertInstanceOf( Http_Exception::class, $exception );
        $this->assertStringContainsString( 'HTTP request failed with status 500:', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    /**
     * Test Http_Exception timeout static factory method.
     */
    public function test_http_exception_timeout(): void {
        $exception = Http_Exception::timeout();
        
        $this->assertInstanceOf( Http_Exception::class, $exception );
        $this->assertStringContainsString( 'HTTP request timed out.', $exception->getMessage() );
    }

    /**
     * Test Http_Exception authentication_failed static factory method.
     */
    public function test_http_exception_authentication_failed(): void {
        $exception = Http_Exception::authentication_failed();
        
        $this->assertInstanceOf( Http_Exception::class, $exception );
        $this->assertStringContainsString( 'Authentication failed. Please check your API credentials.', $exception->getMessage() );
    }

    /**
     * Test Http_Exception rate_limited static factory method.
     */
    public function test_http_exception_rate_limited(): void {
        $exception = Http_Exception::rate_limited();
        
        $this->assertInstanceOf( Http_Exception::class, $exception );
        $this->assertStringContainsString( 'API rate limit exceeded. Please try again later.', $exception->getMessage() );
    }

    // ===== OBSERVATION EXCEPTION TESTS =====

    /**
     * Test Observation_Exception observation_not_found static factory method.
     */
    public function test_observation_exception_observation_not_found(): void {
        $device_id = 'device_456';
        $exception = Observation_Exception::observation_not_found( $device_id );
        
        $this->assertInstanceOf( Observation_Exception::class, $exception );
        $this->assertStringContainsString( "No observations found for device 'device_456'.", $exception->getMessage() );
    }

    /**
     * Test Observation_Exception observation_not_found with special characters.
     */
    public function test_observation_exception_observation_not_found_with_special_characters(): void {
        $device_id = '<script>alert("xss")</script>device_456';
        $exception = Observation_Exception::observation_not_found( $device_id );
        
        $this->assertInstanceOf( Observation_Exception::class, $exception );
        $this->assertStringContainsString( 'No observations found for device', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    /**
     * Test Observation_Exception invalid_date_range static factory method.
     */
    public function test_observation_exception_invalid_date_range(): void {
        $reason = 'Start date is after end date';
        $exception = Observation_Exception::invalid_date_range( $reason );
        
        $this->assertInstanceOf( Observation_Exception::class, $exception );
        $this->assertStringContainsString( 'Invalid date range: Start date is after end date', $exception->getMessage() );
    }

    /**
     * Test Observation_Exception retrieval_failed static factory method.
     */
    public function test_observation_exception_retrieval_failed(): void {
        $reason = 'Database connection failed';
        $exception = Observation_Exception::retrieval_failed( $reason );
        
        $this->assertInstanceOf( Observation_Exception::class, $exception );
        $this->assertStringContainsString( 'Failed to retrieve observations: Database connection failed', $exception->getMessage() );
    }

    /**
     * Test Observation_Exception retrieval_failed with special characters.
     */
    public function test_observation_exception_retrieval_failed_with_special_characters(): void {
        $reason = '<script>alert("xss")</script>Database connection failed';
        $exception = Observation_Exception::retrieval_failed( $reason );
        
        $this->assertInstanceOf( Observation_Exception::class, $exception );
        $this->assertStringContainsString( 'Failed to retrieve observations:', $exception->getMessage() );
        // Note: This exception doesn't use sanitize_text_field, so XSS content will be present
        $this->assertStringContainsString( '<script>', $exception->getMessage() );
    }

    // ===== EDGE CASE TESTS =====

    /**
     * Test all static factory methods with empty strings.
     */
    public function test_static_factory_methods_with_empty_strings(): void {
        // Test Connection_Exception with empty reason
        $exception1 = Connection_Exception::invalid_connection( '' );
        $this->assertStringContainsString( 'Invalid connection:', $exception1->getMessage() );
        
        // Test Device_Exception with empty strings
        $exception2 = Device_Exception::device_not_found( '' );
        $this->assertStringContainsString( "Device with ID '' not found.", $exception2->getMessage() );
        
        $exception3 = Device_Exception::invalid_device( '' );
        $this->assertStringContainsString( 'Invalid device:', $exception3->getMessage() );
        
        $exception4 = Device_Exception::operation_failed( '', '' );
        $this->assertStringContainsString( "Device operation '' failed:", $exception4->getMessage() );
        
        // Test Http_Exception with empty message
        $exception5 = Http_Exception::request_failed( 500, '' );
        $this->assertStringContainsString( 'HTTP request failed with status 500:', $exception5->getMessage() );
        
        // Test Observation_Exception with empty strings
        $exception6 = Observation_Exception::observation_not_found( '' );
        $this->assertStringContainsString( "No observations found for device ''.", $exception6->getMessage() );
        
        $exception7 = Observation_Exception::invalid_date_range( '' );
        $this->assertStringContainsString( 'Invalid date range:', $exception7->getMessage() );
        
        $exception8 = Observation_Exception::retrieval_failed( '' );
        $this->assertStringContainsString( 'Failed to retrieve observations:', $exception8->getMessage() );
    }

    /**
     * Test all static factory methods with very long strings.
     */
    public function test_static_factory_methods_with_long_strings(): void {
        $long_string = str_repeat( 'a', 1000 );
        
        // Test Connection_Exception with long reason
        $exception1 = Connection_Exception::invalid_connection( $long_string );
        $this->assertStringContainsString( 'Invalid connection:', $exception1->getMessage() );
        
        // Test Device_Exception with long strings
        $exception2 = Device_Exception::device_not_found( $long_string );
        $this->assertStringContainsString( 'Device with ID', $exception2->getMessage() );
        $this->assertStringContainsString( 'not found.', $exception2->getMessage() );
        
        $exception3 = Device_Exception::invalid_device( $long_string );
        $this->assertStringContainsString( 'Invalid device:', $exception3->getMessage() );
        
        $exception4 = Device_Exception::operation_failed( $long_string, $long_string );
        $this->assertStringContainsString( 'Device operation', $exception4->getMessage() );
        $this->assertStringContainsString( 'failed:', $exception4->getMessage() );
        
        // Test Http_Exception with long message
        $exception5 = Http_Exception::request_failed( 500, $long_string );
        $this->assertStringContainsString( 'HTTP request failed with status 500:', $exception5->getMessage() );
        
        // Test Observation_Exception with long strings
        $exception6 = Observation_Exception::observation_not_found( $long_string );
        $this->assertStringContainsString( 'No observations found for device', $exception6->getMessage() );
        
        $exception7 = Observation_Exception::invalid_date_range( $long_string );
        $this->assertStringContainsString( 'Invalid date range:', $exception7->getMessage() );
        
        $exception8 = Observation_Exception::retrieval_failed( $long_string );
        $this->assertStringContainsString( 'Failed to retrieve observations:', $exception8->getMessage() );
    }

    /**
     * Test exception chaining with previous exception.
     */
    public function test_exception_chaining(): void {
        $previous = new \Exception( 'Previous exception' );
        $exception = new Ecowitt_Exception( 'Ecowitt exception failed', 0, $previous );
        
        $this->assertSame( $previous, $exception->getPrevious() );
        $this->assertSame( 'Ecowitt exception failed', $exception->getMessage() );
    }

    /**
     * Test that all static factory methods return the correct exception type.
     */
    public function test_all_static_factory_methods_return_correct_type(): void {
        $exceptions = [
            Connection_Exception::missing_connection(),
            Connection_Exception::invalid_connection( 'test' ),
            Device_Exception::device_not_found( 'test' ),
            Device_Exception::invalid_device( 'test' ),
            Device_Exception::operation_failed( 'test', 'test' ),
            Http_Exception::request_failed( 500, 'test' ),
            Http_Exception::timeout(),
            Http_Exception::authentication_failed(),
            Http_Exception::rate_limited(),
            Observation_Exception::observation_not_found( 'test' ),
            Observation_Exception::invalid_date_range( 'test' ),
            Observation_Exception::retrieval_failed( 'test' ),
        ];
        
        foreach ( $exceptions as $exception ) {
            $this->assertInstanceOf( Ecowitt_Exception::class, $exception );
            $this->assertInstanceOf( \Exception::class, $exception );
        }
    }

    /**
     * Test exception message formatting with various edge cases.
     */
    public function test_exception_message_formatting_edge_cases(): void {
        // Test with numeric strings
        $exception1 = Device_Exception::device_not_found( '123' );
        $this->assertStringContainsString( "Device with ID '123' not found.", $exception1->getMessage() );
        
        // Test with mixed alphanumeric
        $exception2 = Device_Exception::operation_failed( 'op123', 'reason456' );
        $this->assertStringContainsString( "Device operation 'op123' failed: reason456", $exception2->getMessage() );
        
        // Test with underscores and hyphens
        $exception3 = Observation_Exception::observation_not_found( 'device_name-123' );
        $this->assertStringContainsString( "No observations found for device 'device_name-123'.", $exception3->getMessage() );
        
        // Test with special characters that should be preserved
        $exception4 = Http_Exception::request_failed( 400, 'Bad Request: Invalid JSON' );
        $this->assertStringContainsString( 'HTTP request failed with status 400: Bad Request: Invalid JSON', $exception4->getMessage() );
    }

    /**
     * Test HTTP status code handling in Http_Exception.
     */
    public function test_http_exception_status_code_handling(): void {
        $status_codes = [ 200, 301, 400, 401, 403, 404, 500, 502, 503 ];
        
        foreach ( $status_codes as $status_code ) {
            $exception = Http_Exception::request_failed( $status_code, 'Test message' );
            $this->assertStringContainsString( "HTTP request failed with status {$status_code}:", $exception->getMessage() );
        }
    }

    /**
     * Test that all exceptions can be caught by their base class.
     */
    public function test_exception_catching_by_base_class(): void {
        $exceptions = [
            new Connection_Exception( 'Connection error' ),
            new Device_Exception( 'Device error' ),
            new Http_Exception( 'HTTP error' ),
            new Observation_Exception( 'Observation error' ),
        ];
        
        foreach ( $exceptions as $exception ) {
            try {
                throw $exception;
            } catch ( Ecowitt_Exception $e ) {
                $this->assertInstanceOf( Ecowitt_Exception::class, $e );
            } catch ( \Exception $e ) {
                $this->fail( 'Exception should have been caught by Ecowitt_Exception' );
            }
        }
    }
}
