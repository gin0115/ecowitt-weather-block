<?php

/**
 * Tests for the Connections class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\Connection;

use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections;

/**
 * Tests for the Connections class.
 * 
 * @group unit
 * @group ecowitt
 * @group connections
 */
class Test_Connections extends \WP_UnitTestCase {

    /**
     * Sample connection data for testing.
     * 
     * @var array<string, array<string, string>>
     */
    private array $sample_connections;

    public function set_up(): void {
        parent::set_up();
        
        $this->sample_connections = array(
            'connection1' => array(
                'key'         => 'conn_1',
                'api_key'     => 'api_key_1',
                'api_secret'  => 'api_secret_1',
                'mac_address' => '00:11:22:33:44:55',
                'description' => 'First weather station',
                'name'        => 'Station One',
            ),
            'connection2' => array(
                'key'         => 'conn_2',
                'api_key'     => 'api_key_2',
                'api_secret'  => 'api_secret_2',
                'mac_address' => 'aa:bb:cc:dd:ee:ff',
                'description' => 'Second weather station',
                'name'        => 'Station Two',
            ),
        );
    }

    /**
     * @testdox It should be possible to create an empty Connections instance.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::__construct
     */
    public function test_can_create_empty_connections(): void {
        $connections = new Connections();
        $this->assertInstanceOf( Connections::class, $connections );
        $this->assertEmpty( $connections->all() );
    }

    /**
     * @testdox It should be possible to create Connections instance with Connection objects.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::__construct
     */
    public function test_can_create_connections_with_objects(): void {
        $connection1 = new Connection(
            $this->sample_connections['connection1']['key'],
            $this->sample_connections['connection1']['api_key'],
            $this->sample_connections['connection1']['api_secret'],
            $this->sample_connections['connection1']['mac_address'],
            $this->sample_connections['connection1']['description'],
            $this->sample_connections['connection1']['name']
        );

        $connections = new Connections( array( $connection1 ) );
        $this->assertCount( 1, $connections->all() );
    }

    /**
     * @testdox It should filter out non-Connection objects from constructor.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::__construct
     */
    public function test_constructor_filters_invalid_objects(): void {
        $connection = new Connection( 'key', 'api', 'secret', 'mac', 'desc', 'name' );
        $invalid_objects = array( $connection, 'string', 123, array(), new \stdClass() );

        $connections = new Connections( $invalid_objects );
        $this->assertCount( 1, $connections->all() );
    }

    /**
     * @testdox It should create Connections from array data with required fields.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::from_array
     */
    public function test_can_create_from_array(): void {
        $data = array(
            $this->sample_connections['connection1'],
            $this->sample_connections['connection2'],
        );

        $connections = Connections::from_array( $data );
        $this->assertInstanceOf( Connections::class, $connections );
        $this->assertCount( 2, $connections->all() );
    }

    /**
     * @testdox It should handle missing optional fields when creating from array.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::from_array
     */
    public function test_from_array_handles_missing_optional_fields(): void {
        $data = array(
            array(
                'key'         => 'test_key',
                'api_key'     => 'test_api',
                'api_secret'  => 'test_secret',
                'mac_address' => '00:11:22:33:44:55',
                // Missing description and name
            ),
        );

        $connections = Connections::from_array( $data );
        $this->assertCount( 1, $connections->all() );
        
        $connection = $connections->get( 'test_key' );
        $this->assertInstanceOf( Connection::class, $connection );
        $this->assertSame( '', $connection->description() );
        $this->assertSame( '', $connection->name() );
    }

    /**
     * @testdox It should filter out invalid array data when creating from array.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::from_array
     */
    public function test_from_array_filters_invalid_data(): void {
        $data = array(
            $this->sample_connections['connection1'], // Valid
            'invalid_string',                          // Invalid - not array
            array( 'key' => 'incomplete' ),           // Invalid - missing required fields
            array(                                     // Invalid - missing required fields
                'key'        => 'test',
                'api_key'    => 'test',
                // Missing api_secret and mac_address
            ),
            $this->sample_connections['connection2'], // Valid
        );

        $connections = Connections::from_array( $data );
        $this->assertCount( 2, $connections->all() );
    }

    /**
     * @testdox It should be possible to add a new connection.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::add
     */
    public function test_can_add_connection(): void {
        $connections = new Connections();
        
        $connections->add(
            $this->sample_connections['connection1']['key'],
            $this->sample_connections['connection1']['api_key'],
            $this->sample_connections['connection1']['api_secret'],
            $this->sample_connections['connection1']['mac_address'],
            $this->sample_connections['connection1']['description'],
            $this->sample_connections['connection1']['name']
        );

        $this->assertCount( 1, $connections->all() );
        $this->assertTrue( $connections->has( $this->sample_connections['connection1']['key'] ) );
    }

    /**
     * @testdox It should be possible to add a connection with default optional parameters.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::add
     */
    public function test_can_add_connection_with_defaults(): void {
        $connections = new Connections();
        
        $connections->add( 'key', 'api', 'secret', 'mac' );

        $this->assertCount( 1, $connections->all() );
        $connection = $connections->get( 'key' );
        $this->assertSame( '', $connection->description() );
        $this->assertSame( '', $connection->name() );
    }

    /**
     * @testdox It should find the correct index for a connection by key.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::index
     */
    public function test_can_find_connection_index(): void {
        $connections = new Connections();
        $connections->add( 'first', 'api1', 'secret1', 'mac1' );
        $connections->add( 'second', 'api2', 'secret2', 'mac2' );

        $this->assertSame( 0, $connections->index( 'first' ) );
        $this->assertSame( 1, $connections->index( 'second' ) );
        $this->assertNull( $connections->index( 'nonexistent' ) );
    }

    /**
     * @testdox It should correctly check if a connection exists.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::has
     */
    public function test_can_check_if_connection_exists(): void {
        $connections = new Connections();
        $connections->add( 'existing_key', 'api', 'secret', 'mac' );

        $this->assertTrue( $connections->has( 'existing_key' ) );
        $this->assertFalse( $connections->has( 'nonexistent_key' ) );
    }

    /**
     * @testdox It should retrieve a connection by key.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::get
     */
    public function test_can_get_connection_by_key(): void {
        $connections = new Connections();
        $connections->add(
            $this->sample_connections['connection1']['key'],
            $this->sample_connections['connection1']['api_key'],
            $this->sample_connections['connection1']['api_secret'],
            $this->sample_connections['connection1']['mac_address'],
            $this->sample_connections['connection1']['description'],
            $this->sample_connections['connection1']['name']
        );

        $connection = $connections->get( $this->sample_connections['connection1']['key'] );
        $this->assertInstanceOf( Connection::class, $connection );
        $this->assertSame( $this->sample_connections['connection1']['key'], $connection->key() );
    }

    /**
     * @testdox It should return null when getting a non-existent connection.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::get
     */
    public function test_get_returns_null_for_nonexistent_connection(): void {
        $connections = new Connections();
        $this->assertNull( $connections->get( 'nonexistent' ) );
    }

    /**
     * @testdox It should return all connections as an array.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::all
     */
    public function test_can_get_all_connections(): void {
        $connections = new Connections();
        $connections->add( 'key1', 'api1', 'secret1', 'mac1' );
        $connections->add( 'key2', 'api2', 'secret2', 'mac2' );

        $all = $connections->all();
        $this->assertIsArray( $all );
        $this->assertCount( 2, $all );
        
        foreach ( $all as $connection ) {
            $this->assertInstanceOf( Connection::class, $connection );
        }
    }

    /**
     * @testdox It should remove a connection by key.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::remove
     */
    public function test_can_remove_connection(): void {
        $connections = new Connections();
        $connections->add( 'key1', 'api1', 'secret1', 'mac1' );
        $connections->add( 'key2', 'api2', 'secret2', 'mac2' );

        $this->assertCount( 2, $connections->all() );
        $this->assertTrue( $connections->has( 'key1' ) );

        $connections->remove( 'key1' );

        $this->assertCount( 1, $connections->all() );
        $this->assertFalse( $connections->has( 'key1' ) );
        $this->assertTrue( $connections->has( 'key2' ) );
    }

    /**
     * @testdox It should handle removing a non-existent connection gracefully.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::remove
     */
    public function test_remove_handles_nonexistent_connection(): void {
        $connections = new Connections();
        $connections->add( 'existing', 'api', 'secret', 'mac' );

        $this->assertCount( 1, $connections->all() );
        
        // Should not throw error or change anything
        $connections->remove( 'nonexistent' );
        
        $this->assertCount( 1, $connections->all() );
        $this->assertTrue( $connections->has( 'existing' ) );
    }

    /**
     * @testdox It should implement JsonSerializable and return connections array.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::jsonSerialize
     */
    public function test_implements_json_serializable(): void {
        $connections = new Connections();
        $connections->add( 'key1', 'api1', 'secret1', 'mac1', 'desc1', 'name1' );
        $connections->add( 'key2', 'api2', 'secret2', 'mac2', 'desc2', 'name2' );

        $this->assertInstanceOf( \JsonSerializable::class, $connections );

        $serialized = $connections->jsonSerialize();
        $this->assertIsArray( $serialized );
        $this->assertCount( 2, $serialized );
        
        foreach ( $serialized as $connection ) {
            $this->assertInstanceOf( Connection::class, $connection );
        }
    }

    /**
     * @testdox It should be possible to JSON encode the Connections object.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::jsonSerialize
     */
    public function test_can_json_encode_connections(): void {
        $connections = new Connections();
        $connections->add(
            $this->sample_connections['connection1']['key'],
            $this->sample_connections['connection1']['api_key'],
            $this->sample_connections['connection1']['api_secret'],
            $this->sample_connections['connection1']['mac_address'],
            $this->sample_connections['connection1']['description'],
            $this->sample_connections['connection1']['name']
        );

        $json = json_encode( $connections );
        $this->assertIsString( $json );
        $this->assertNotFalse( $json );

        $decoded = json_decode( $json, true );
        $this->assertIsArray( $decoded );
        $this->assertCount( 1, $decoded );
        
        // Check the structure of the decoded connection
        $connection_data = $decoded[0];
        $this->assertArrayHasKey( 'key', $connection_data );
        $this->assertArrayHasKey( 'api_key', $connection_data );
        $this->assertArrayHasKey( 'api_secret', $connection_data );
        $this->assertArrayHasKey( 'mac_address', $connection_data );
        $this->assertArrayHasKey( 'description', $connection_data );
        $this->assertArrayHasKey( 'name', $connection_data );
    }

    /**
     * @testdox It should maintain array indexes after removal operations.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::remove
     */
    public function test_array_indexing_after_removal(): void {
        $connections = new Connections();
        $connections->add( 'first', 'api1', 'secret1', 'mac1' );
        $connections->add( 'second', 'api2', 'secret2', 'mac2' );
        $connections->add( 'third', 'api3', 'secret3', 'mac3' );

        // Remove middle connection
        $connections->remove( 'second' );

        $this->assertCount( 2, $connections->all() );
        $this->assertTrue( $connections->has( 'first' ) );
        $this->assertFalse( $connections->has( 'second' ) );
        $this->assertTrue( $connections->has( 'third' ) );

        // Verify we can still access remaining connections
        $this->assertInstanceOf( Connection::class, $connections->get( 'first' ) );
        $this->assertInstanceOf( Connection::class, $connections->get( 'third' ) );
    }

    /**
     * @testdox It should escape HTML in array data when creating from array.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::from_array
     */
    public function test_from_array_escapes_html(): void {
        $malicious_data = array(
            array(
                'key'         => '<script>alert("xss")</script>',
                'api_key'     => '">alert("xss")<',
                'api_secret'  => '&lt;script&gt;',
                'mac_address' => '"onclick="alert(1)"',
                'description' => '<img src=x onerror=alert(1)>',
                'name'        => '<b>bold</b>',
            ),
        );

        $connections = Connections::from_array( $malicious_data );
        $connection = $connections->get( esc_attr( '<script>alert("xss")</script>' ) );

        $this->assertInstanceOf( Connection::class, $connection );
        // Verify all values are properly escaped
        $this->assertSame( esc_attr( $malicious_data[0]['key'] ), $connection->key() );
        $this->assertSame( esc_attr( $malicious_data[0]['api_key'] ), $connection->api_key() );
        $this->assertSame( esc_attr( $malicious_data[0]['api_secret'] ), $connection->api_secret() );
        $this->assertSame( esc_attr( $malicious_data[0]['mac_address'] ), $connection->mac_address() );
        $this->assertSame( esc_attr( $malicious_data[0]['description'] ), $connection->description() );
        $this->assertSame( esc_attr( $malicious_data[0]['name'] ), $connection->name() );
    }

    /**
     * @testdox It should handle complex workflow of adding, getting, and removing connections.
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::add
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::get
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::remove
     * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connections::has
     */
    public function test_complex_workflow(): void {
        $connections = new Connections();

        // Add multiple connections
        foreach ( $this->sample_connections as $data ) {
            $connections->add(
                $data['key'],
                $data['api_key'],
                $data['api_secret'],
                $data['mac_address'],
                $data['description'],
                $data['name']
            );
        }

        $this->assertCount( 2, $connections->all() );

        // Verify both exist
        $this->assertTrue( $connections->has( 'conn_1' ) );
        $this->assertTrue( $connections->has( 'conn_2' ) );

        // Get and verify connection details
        $conn1 = $connections->get( 'conn_1' );
        $this->assertSame( 'api_key_1', $conn1->api_key() );
        $this->assertSame( 'First weather station', $conn1->description() );

        // Remove one connection
        $connections->remove( 'conn_1' );
        $this->assertCount( 1, $connections->all() );
        $this->assertFalse( $connections->has( 'conn_1' ) );
        $this->assertTrue( $connections->has( 'conn_2' ) );

        // Add it back
        $connections->add( 'conn_1', 'new_api', 'new_secret', 'new_mac', 'Updated desc', 'Updated name' );
        $this->assertCount( 2, $connections->all() );
        $this->assertTrue( $connections->has( 'conn_1' ) );

        // Verify updated connection
        $updated_conn = $connections->get( 'conn_1' );
        $this->assertSame( 'new_api', $updated_conn->api_key() );
        $this->assertSame( 'Updated desc', $updated_conn->description() );
    }
}
