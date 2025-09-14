<?php

/**
 * Test class for Conversion Exception classes.
 *
 * @package PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion\Exception
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Observation\Conversion\Exception;

use WP_UnitTestCase;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Conversion_Exception;
use PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Unit_Conversion_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// @codeCoverageIgnoreEnd

/**
 * Test class for Conversion Exception classes.
 *
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Conversion_Exception
 * @covers \PinkCrab\Ecowitt_Weather_Block\Observation\Conversion\Exception\Unit_Conversion_Exception
 */
class Test_Conversion_Exceptions extends WP_UnitTestCase {

    /**
     * Test that Conversion_Exception extends Exception.
     */
    public function test_conversion_exception_extends_exception(): void {
        $exception = new Conversion_Exception( 'Test message' );
        $this->assertInstanceOf( \Exception::class, $exception );
    }

    /**
     * Test that Unit_Conversion_Exception extends Conversion_Exception.
     */
    public function test_unit_conversion_exception_extends_conversion_exception(): void {
        $exception = new Unit_Conversion_Exception( 'Test message' );
        $this->assertInstanceOf( Conversion_Exception::class, $exception );
        $this->assertInstanceOf( \Exception::class, $exception );
    }

    /**
     * Test basic exception functionality.
     */
    public function test_basic_exception_functionality(): void {
        $message = 'Test exception message';
        $code = 123;
        $exception = new Conversion_Exception( $message, $code );
        
        $this->assertSame( $message, $exception->getMessage() );
        $this->assertSame( $code, $exception->getCode() );
    }

    /**
     * Test Unit_Conversion_Exception basic functionality.
     */
    public function test_unit_conversion_exception_basic_functionality(): void {
        $message = 'Unit conversion failed';
        $code = 456;
        $exception = new Unit_Conversion_Exception( $message, $code );
        
        $this->assertSame( $message, $exception->getMessage() );
        $this->assertSame( $code, $exception->getCode() );
    }

    /**
     * Test unsupported_measurement_type static factory method.
     */
    public function test_unsupported_measurement_type_factory(): void {
        $measurement_type = 'invalid_type';
        $exception = Unit_Conversion_Exception::unsupported_measurement_type( $measurement_type );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'No conversion configuration found for measurement type: invalid_type', $exception->getMessage() );
    }

    /**
     * Test unsupported_measurement_type with special characters.
     */
    public function test_unsupported_measurement_type_with_special_characters(): void {
        $measurement_type = '<script>alert("xss")</script>';
        $exception = Unit_Conversion_Exception::unsupported_measurement_type( $measurement_type );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringNotContainsString( '<script>', $exception->getMessage() );
        $this->assertStringContainsString( 'No conversion configuration found for measurement type:', $exception->getMessage() );
    }

    /**
     * Test unsupported_unit static factory method.
     */
    public function test_unsupported_unit_factory(): void {
        $unit = 'invalid_unit';
        $measurement_type = 'temperature';
        $exception = Unit_Conversion_Exception::unsupported_unit( $unit, $measurement_type );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'Unit \'invalid_unit\' is not supported for measurement type \'temperature\'', $exception->getMessage() );
    }

    /**
     * Test unsupported_unit with special characters.
     */
    public function test_unsupported_unit_with_special_characters(): void {
        $unit = '<script>alert("xss")</script>';
        $measurement_type = 'temperature<script>';
        $exception = Unit_Conversion_Exception::unsupported_unit( $unit, $measurement_type );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringNotContainsString( '<script>', $exception->getMessage() );
        $this->assertStringContainsString( 'Unit \'', $exception->getMessage() );
        $this->assertStringContainsString( 'is not supported for measurement type', $exception->getMessage() );
    }

    /**
     * Test missing_conversion_formula static factory method.
     */
    public function test_missing_conversion_formula_factory(): void {
        $unit = 'celsius';
        $direction = 'to_base';
        $exception = Unit_Conversion_Exception::missing_conversion_formula( $unit, $direction );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'No to_base conversion formula found for unit: celsius', $exception->getMessage() );
    }

    /**
     * Test missing_conversion_formula with from_base direction.
     */
    public function test_missing_conversion_formula_from_base(): void {
        $unit = 'fahrenheit';
        $direction = 'from_base';
        $exception = Unit_Conversion_Exception::missing_conversion_formula( $unit, $direction );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'No from_base conversion formula found for unit: fahrenheit', $exception->getMessage() );
    }

    /**
     * Test missing_conversion_formula with special characters.
     */
    public function test_missing_conversion_formula_with_special_characters(): void {
        $unit = '<script>alert("xss")</script>';
        $direction = 'to_base<script>';
        $exception = Unit_Conversion_Exception::missing_conversion_formula( $unit, $direction );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringNotContainsString( '<script>', $exception->getMessage() );
        $this->assertStringContainsString( 'No', $exception->getMessage() );
        $this->assertStringContainsString( 'conversion formula found for unit:', $exception->getMessage() );
    }

    /**
     * Test invalid_configuration static factory method.
     */
    public function test_invalid_configuration_factory(): void {
        $reason = 'Missing base unit configuration';
        $exception = Unit_Conversion_Exception::invalid_configuration( $reason );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'Invalid conversion configuration: Missing base unit configuration', $exception->getMessage() );
    }

    /**
     * Test invalid_configuration with special characters.
     */
    public function test_invalid_configuration_with_special_characters(): void {
        $reason = '<script>alert("xss")</script>Invalid config';
        $exception = Unit_Conversion_Exception::invalid_configuration( $reason );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringNotContainsString( '<script>', $exception->getMessage() );
        $this->assertStringContainsString( 'Invalid conversion configuration:', $exception->getMessage() );
    }

    /**
     * Test calculation_failed static factory method.
     */
    public function test_calculation_failed_factory(): void {
        $from_unit = 'celsius';
        $to_unit = 'fahrenheit';
        $reason = 'Division by zero';
        $exception = Unit_Conversion_Exception::calculation_failed( $from_unit, $to_unit, $reason );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringContainsString( 'Failed to convert from \'celsius\' to \'fahrenheit\': Division by zero', $exception->getMessage() );
    }

    /**
     * Test calculation_failed with special characters.
     */
    public function test_calculation_failed_with_special_characters(): void {
        $from_unit = '<script>alert("xss")</script>celsius';
        $to_unit = 'fahrenheit<script>';
        $reason = 'Invalid<script> calculation';
        $exception = Unit_Conversion_Exception::calculation_failed( $from_unit, $to_unit, $reason );
        
        $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
        $this->assertStringNotContainsString( '<script>', $exception->getMessage() );
        $this->assertStringContainsString( 'Failed to convert from', $exception->getMessage() );
        $this->assertStringContainsString( 'to', $exception->getMessage() );
    }

    /**
     * Test all static factory methods with empty strings.
     */
    public function test_static_factory_methods_with_empty_strings(): void {
        // Test unsupported_measurement_type with empty string
        $exception1 = Unit_Conversion_Exception::unsupported_measurement_type( '' );
        $this->assertStringContainsString( 'No conversion configuration found for measurement type:', $exception1->getMessage() );
        
        // Test unsupported_unit with empty strings
        $exception2 = Unit_Conversion_Exception::unsupported_unit( '', '' );
        $this->assertStringContainsString( 'Unit \'\' is not supported for measurement type \'\'', $exception2->getMessage() );
        
        // Test missing_conversion_formula with empty strings
        $exception3 = Unit_Conversion_Exception::missing_conversion_formula( '', '' );
        $this->assertStringContainsString( 'No  conversion formula found for unit:', $exception3->getMessage() );
        
        // Test invalid_configuration with empty string
        $exception4 = Unit_Conversion_Exception::invalid_configuration( '' );
        $this->assertStringContainsString( 'Invalid conversion configuration:', $exception4->getMessage() );
        
        // Test calculation_failed with empty strings
        $exception5 = Unit_Conversion_Exception::calculation_failed( '', '', '' );
        $this->assertStringContainsString( 'Failed to convert from \'\' to \'\':', $exception5->getMessage() );
    }

    /**
     * Test all static factory methods with very long strings.
     */
    public function test_static_factory_methods_with_long_strings(): void {
        $long_string = str_repeat( 'a', 1000 );
        
        // Test unsupported_measurement_type with long string
        $exception1 = Unit_Conversion_Exception::unsupported_measurement_type( $long_string );
        $this->assertStringContainsString( 'No conversion configuration found for measurement type:', $exception1->getMessage() );
        
        // Test unsupported_unit with long strings
        $exception2 = Unit_Conversion_Exception::unsupported_unit( $long_string, $long_string );
        $this->assertStringContainsString( 'Unit \'', $exception2->getMessage() );
        $this->assertStringContainsString( 'is not supported for measurement type', $exception2->getMessage() );
        
        // Test missing_conversion_formula with long strings
        $exception3 = Unit_Conversion_Exception::missing_conversion_formula( $long_string, $long_string );
        $this->assertStringContainsString( 'No', $exception3->getMessage() );
        $this->assertStringContainsString( 'conversion formula found for unit:', $exception3->getMessage() );
        
        // Test invalid_configuration with long string
        $exception4 = Unit_Conversion_Exception::invalid_configuration( $long_string );
        $this->assertStringContainsString( 'Invalid conversion configuration:', $exception4->getMessage() );
        
        // Test calculation_failed with long strings
        $exception5 = Unit_Conversion_Exception::calculation_failed( $long_string, $long_string, $long_string );
        $this->assertStringContainsString( 'Failed to convert from', $exception5->getMessage() );
        $this->assertStringContainsString( 'to', $exception5->getMessage() );
    }

    /**
     * Test exception chaining with previous exception.
     */
    public function test_exception_chaining(): void {
        $previous = new \Exception( 'Previous exception' );
        $exception = new Unit_Conversion_Exception( 'Unit conversion failed', 0, $previous );
        
        $this->assertSame( $previous, $exception->getPrevious() );
        $this->assertSame( 'Unit conversion failed', $exception->getMessage() );
    }

    /**
     * Test that all static factory methods return the correct exception type.
     */
    public function test_all_static_factory_methods_return_correct_type(): void {
        $exceptions = [
            Unit_Conversion_Exception::unsupported_measurement_type( 'test' ),
            Unit_Conversion_Exception::unsupported_unit( 'test', 'test' ),
            Unit_Conversion_Exception::missing_conversion_formula( 'test', 'test' ),
            Unit_Conversion_Exception::invalid_configuration( 'test' ),
            Unit_Conversion_Exception::calculation_failed( 'test', 'test', 'test' ),
        ];
        
        foreach ( $exceptions as $exception ) {
            $this->assertInstanceOf( Unit_Conversion_Exception::class, $exception );
            $this->assertInstanceOf( Conversion_Exception::class, $exception );
            $this->assertInstanceOf( \Exception::class, $exception );
        }
    }

    /**
     * Test exception message formatting with various edge cases.
     */
    public function test_exception_message_formatting_edge_cases(): void {
        // Test with numeric strings
        $exception1 = Unit_Conversion_Exception::unsupported_measurement_type( '123' );
        $this->assertStringContainsString( 'measurement type: 123', $exception1->getMessage() );
        
        // Test with mixed alphanumeric
        $exception2 = Unit_Conversion_Exception::unsupported_unit( 'unit123', 'type456' );
        $this->assertStringContainsString( 'Unit \'unit123\' is not supported for measurement type \'type456\'', $exception2->getMessage() );
        
        // Test with underscores and hyphens
        $exception3 = Unit_Conversion_Exception::missing_conversion_formula( 'unit_name', 'to_base' );
        $this->assertStringContainsString( 'No to_base conversion formula found for unit: unit_name', $exception3->getMessage() );
    }
}
