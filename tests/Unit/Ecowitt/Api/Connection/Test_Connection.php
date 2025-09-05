<?php

/**
 * Tests for the Connection class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\Connection;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;

/**
 * Tests for the Connection class.
 * 
 * @group unit
 * @group ecowitt
 * @group connection
 */
class Test_Connection extends \WP_UnitTestCase {

    /**
     * Sample connection data for testing.
     * 
     * @var array<string, string>
     */
    private array $sample_data;

    public function set_up(): void {
        parent::set_up();
        
        $this->sample_data = array(
            'key'         => 'test_key_123',
            'api_key'     => 'api_key_456',
            'api_secret'  => 'api_secret_789',
            'mac_address' => '00:11:22:33:44:55',
            'description' => 'Test weather station',
            'name'        => 'My Weather Station',
        );
    }

    /**
     * @testdox It should be possible to create a Connection instance with all required parameters.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::__construct
     */
    public function test_can_create_connection_instance(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertInstanceOf( Connection::class, $connection );
    }

    /**
     * @testdox It should return the correct key value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::key
     */
    public function test_can_get_key(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['key'], $connection->key() );
    }

    /**
     * @testdox It should return the correct API key value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::api_key
     */
    public function test_can_get_api_key(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['api_key'], $connection->api_key() );
    }

    /**
     * @testdox It should return the correct API secret value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::api_secret
     */
    public function test_can_get_api_secret(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['api_secret'], $connection->api_secret() );
    }

    /**
     * @testdox It should return the correct MAC address value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::mac_address
     */
    public function test_can_get_mac_address(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['mac_address'], $connection->mac_address() );
    }

    /**
     * @testdox It should return the correct description value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::description
     */
    public function test_can_get_description(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['description'], $connection->description() );
    }

    /**
     * @testdox It should return the correct name value.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::name
     */
    public function test_can_get_name(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertSame( $this->sample_data['name'], $connection->name() );
    }

    /**
     * @testdox It should escape HTML attributes in constructor parameters.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::__construct
     */
    public function test_constructor_escapes_html_attributes(): void {
        $malicious_data = array(
            'key'         => '<script>alert("xss")</script>',
            'api_key'     => '">alert("xss")<',
            'api_secret'  => '&lt;script&gt;',
            'mac_address' => '"onclick="alert(1)"',
            'description' => '<img src=x onerror=alert(1)>',
            'name'        => '<b>bold</b>',
        );

        $connection = new Connection(
            $malicious_data['key'],
            $malicious_data['api_key'],
            $malicious_data['api_secret'],
            $malicious_data['mac_address'],
            $malicious_data['description'],
            $malicious_data['name']
        );

        // Check that all values are properly escaped
        $this->assertSame( esc_attr( $malicious_data['key'] ), $connection->key() );
        $this->assertSame( esc_attr( $malicious_data['api_key'] ), $connection->api_key() );
        $this->assertSame( esc_attr( $malicious_data['api_secret'] ), $connection->api_secret() );
        $this->assertSame( esc_attr( $malicious_data['mac_address'] ), $connection->mac_address() );
        $this->assertSame( esc_attr( $malicious_data['description'] ), $connection->description() );
        $this->assertSame( esc_attr( $malicious_data['name'] ), $connection->name() );
    }

    /**
     * @testdox It should implement JsonSerializable and return correct array structure.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::jsonSerialize
     */
    public function test_implements_json_serializable(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $this->assertInstanceOf( \JsonSerializable::class, $connection );

        $serialized = $connection->jsonSerialize();
        $this->assertIsArray( $serialized );
        
        // Check all expected keys are present
        $expected_keys = array( 'key', 'api_key', 'api_secret', 'mac_address', 'description', 'name' );
        foreach ( $expected_keys as $key ) {
            $this->assertArrayHasKey( $key, $serialized );
        }

        // Check values match
        $this->assertSame( $this->sample_data['key'], $serialized['key'] );
        $this->assertSame( $this->sample_data['api_key'], $serialized['api_key'] );
        $this->assertSame( $this->sample_data['api_secret'], $serialized['api_secret'] );
        $this->assertSame( $this->sample_data['mac_address'], $serialized['mac_address'] );
        $this->assertSame( $this->sample_data['description'], $serialized['description'] );
        $this->assertSame( $this->sample_data['name'], $serialized['name'] );
    }

    /**
     * @testdox It should be possible to JSON encode the Connection object.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::jsonSerialize
     */
    public function test_can_json_encode_connection(): void {
        $connection = new Connection(
            $this->sample_data['key'],
            $this->sample_data['api_key'],
            $this->sample_data['api_secret'],
            $this->sample_data['mac_address'],
            $this->sample_data['description'],
            $this->sample_data['name']
        );

        $json = json_encode( $connection );
        $this->assertIsString( $json );
        $this->assertNotFalse( $json );

        $decoded = json_decode( $json, true );
        $this->assertIsArray( $decoded );
        
        // Verify the decoded data matches our sample data
        foreach ( $this->sample_data as $key => $value ) {
            $this->assertArrayHasKey( $key, $decoded );
            $this->assertSame( $value, $decoded[$key] );
        }
    }

    /**
     * @testdox It should handle empty string values correctly.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::__construct
     */
    public function test_handles_empty_string_values(): void {
        $connection = new Connection( '', '', '', '', '', '' );

        $this->assertSame( '', $connection->key() );
        $this->assertSame( '', $connection->api_key() );
        $this->assertSame( '', $connection->api_secret() );
        $this->assertSame( '', $connection->mac_address() );
        $this->assertSame( '', $connection->description() );
        $this->assertSame( '', $connection->name() );
    }

    /**
     * @testdox It should handle special characters and unicode correctly.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection::__construct
     */
    public function test_handles_special_characters(): void {
        $special_data = array(
            'key'         => 'key_with_üñïçødé',
            'api_key'     => 'api-key/with+symbols@domain.com',
            'api_secret'  => 'secret_with_$pecial_ch@rs!',
            'mac_address' => 'aa:bb:cc:dd:ee:ff',
            'description' => 'Weather station with émojis 🌡️🌦️',
            'name'        => 'Statiön Météo',
        );

        $connection = new Connection(
            $special_data['key'],
            $special_data['api_key'],
            $special_data['api_secret'],
            $special_data['mac_address'],
            $special_data['description'],
            $special_data['name']
        );

        // All values should be properly escaped but preserve valid characters
        $this->assertSame( esc_attr( $special_data['key'] ), $connection->key() );
        $this->assertSame( esc_attr( $special_data['api_key'] ), $connection->api_key() );
        $this->assertSame( esc_attr( $special_data['api_secret'] ), $connection->api_secret() );
        $this->assertSame( esc_attr( $special_data['mac_address'] ), $connection->mac_address() );
        $this->assertSame( esc_attr( $special_data['description'] ), $connection->description() );
        $this->assertSame( esc_attr( $special_data['name'] ), $connection->name() );
    }
}
