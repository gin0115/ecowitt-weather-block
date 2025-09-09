<?php

/**
 * Tests for the Device_Service class.
 *
 * @since 0.1.0
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\Tests\Unit\Ecowitt\Api\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PinkCrab\Perique\Application\App_Config;
use PinkCrab\Ecowitt_Weather_Block\Http\Response;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\DTO\V3\Device;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Connection\Connection;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service;
use PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Ecowitt_Http_Service;

/**
 * Tests for the Device_Service class.
 *
 * @group unit
 * @group ecowitt
 * @group device_service
 */
class Test_Device_Service extends \WP_UnitTestCase {

	/**
	 * Mock HTTP service.
	 *
	 * @var MockObject&Ecowitt_Http_Service
	 */
	private $mock_http_service;

	/**
	 * App Config instance for testing.
	 *
	 * @var App_Config
	 */
	private App_Config $config;

	/**
	 * Device Service instance under test.
	 *
	 * @var Device_Service
	 */
	private Device_Service $device_service;

	/**
	 * Sample connection for testing.
	 *
	 * @var Connection
	 */
	private Connection $sample_connection;

	public function set_up(): void {
		parent::set_up();

		// Create mock HTTP service
		$this->mock_http_service = $this->createMock( Ecowitt_Http_Service::class );

		// Create real App_Config instance with minimal test configuration
		$test_config = array(
			'additional' => array(
				'ecowitt_api_base' => 'https://api.ecowitt.net/api/v3',
			),
		);
		$this->config = new App_Config( $test_config );

		// Create Device Service instance
		$this->device_service = new Device_Service( $this->mock_http_service, $this->config );

		// Create sample connection
		$this->sample_connection = new Connection(
			'test_key_123',
			'app_key_456',
			'api_key_789',
			'00:11:22:33:44:55',
			'Test Weather Station',
			'My Test Station'
		);
	}

	/**
	 * Helper method to create a mock API response.
	 *
	 * @param array $devices Array of device data.
	 * @param int $page_num Current page number.
	 * @param int $total_pages Total number of pages.
	 * @param int $total Total number of devices.
	 * @return string JSON response.
	 */
	private function create_api_response( array $devices, int $page_num = 1, int $total_pages = 1, int $total = null ): string {
		$total = $total ?? count( $devices );

		return wp_json_encode(
			array(
				'code' => 0,
				'msg'  => 'success',
				'data' => array(
					'total'     => $total,
					'totalPage' => $total_pages,
					'pageNum'   => $page_num,
					'list'      => $devices,
				),
			)
		);
	}

	/**
	 * Helper method to create sample device data.
	 *
	 * @param int $device_id Device ID.
	 * @param string $name Device name.
	 * @return array Device data array.
	 */
	private function create_device_data( int $device_id, string $name = 'Weather Station' ): array {
		return array(
			'id'             => $device_id,
			'name'           => $name,
			'mac'            => sprintf( '00:11:22:33:44:%02d', $device_id ),
			'imei'           => sprintf( '12345678901234%d', $device_id ),
			'type'           => 1,
			'date_zone_id'   => 'America/New_York',
			'createtime'     => 1642248600 + $device_id,
			'longitude'      => -74.006 + ( $device_id * 0.001 ),
			'latitude'       => 40.7128 + ( $device_id * 0.001 ),
			'stationtype'    => 'GW1100A_V2.2.8',
			'iotdevice_list' => array(),
		);
	}

	/**
	 * @testdox It should be possible to create a Device_Service instance.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::__construct
	 */
	public function test_can_create_device_service_instance(): void {
		$this->assertInstanceOf( Device_Service::class, $this->device_service );
	}

	/**
	 * @testdox It should successfully retrieve devices from a single page response.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_single_page(): void {
		$device_data = array(
			$this->create_device_data( 1, 'Device 1' ),
			$this->create_device_data( 2, 'Device 2' ),
		);

		$response_body = $this->create_api_response( $device_data, 1, 1, 2 );
		$mock_response = new Response( $response_body, 200 );

		// Expected URL format: {base}/device/list?application_key={app_key}&api_key={api_key}&limit={limit}&page={page}
		$expected_url  = 'https://api.ecowitt.net/api/v3/device/list?application_key=app_key_456&api_key=api_key_789&limit=100&page=1';

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->with( $expected_url, array() )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 2, $devices );
		$this->assertContainsOnlyInstancesOf( Device::class, $devices );
		$this->assertSame( 1, $devices[0]->id );
		$this->assertSame( 'Device 1', $devices[0]->name );
		$this->assertSame( 2, $devices[1]->id );
		$this->assertSame( 'Device 2', $devices[1]->name );
	}

	/**
	 * @testdox It should handle paginated responses and collect all devices.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_multiple_pages(): void {
		$page1_devices = array(
			$this->create_device_data( 1, 'Device 1' ),
			$this->create_device_data( 2, 'Device 2' ),
		);

		$page2_devices = array(
			$this->create_device_data( 3, 'Device 3' ),
		);

		$page1_response = $this->create_api_response( $page1_devices, 1, 2, 3 );
		$page2_response = $this->create_api_response( $page2_devices, 2, 2, 3 );

		$expected_url_page1 = 'https://api.ecowitt.net/api/v3/device/list?application_key=app_key_456&api_key=api_key_789&limit=100&page=1';
		$expected_url_page2 = 'https://api.ecowitt.net/api/v3/device/list?application_key=app_key_456&api_key=api_key_789&limit=100&page=2';

		$this->mock_http_service->expects( $this->exactly( 2 ) )
			->method( 'request' )
			->withConsecutive(
				array( $expected_url_page1, array() ),
				array( $expected_url_page2, array() )
			)
			->willReturnOnConsecutiveCalls(
				new Response( $page1_response, 200 ),
				new Response( $page2_response, 200 )
			);

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 3, $devices );
		$this->assertContainsOnlyInstancesOf( Device::class, $devices );
		$this->assertSame( 1, $devices[0]->id );
		$this->assertSame( 'Device 1', $devices[0]->name );
		$this->assertSame( 2, $devices[1]->id );
		$this->assertSame( 'Device 2', $devices[1]->name );
		$this->assertSame( 3, $devices[2]->id );
		$this->assertSame( 'Device 3', $devices[2]->name );
	}

	/**
	 * @testdox It should handle custom limit parameter correctly.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_custom_limit(): void {
		$device_data = array( $this->create_device_data( 1, 'Device 1' ) );
		$response_body = $this->create_api_response( $device_data, 1, 1, 1 );
		$mock_response = new Response( $response_body, 200 );

		$expected_url  = 'https://api.ecowitt.net/api/v3/device/list?application_key=app_key_456&api_key=api_key_789&limit=50&page=1';

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->with( $expected_url, array() )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection, 50 );

		$this->assertCount( 1, $devices );
		$this->assertInstanceOf( Device::class, $devices[0] );
	}

	/**
	 * @testdox It should handle API error responses gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_api_error(): void {
		$error_response = wp_json_encode(
			array(
				'code' => 1001,
				'msg'  => 'Invalid API key',
				'data' => null,
			)
		);

		$mock_response = new Response( $error_response, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 0, $devices );
		$this->assertIsArray( $devices );
	}

	/**
	 * @testdox It should handle invalid JSON responses gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_invalid_json(): void {
		$invalid_json = '{"invalid": json response';
		$mock_response = new Response( $invalid_json, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 0, $devices );
		$this->assertIsArray( $devices );
	}

	/**
	 * @testdox It should handle missing data structure in response gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_missing_data_structure(): void {
		$response_without_list = wp_json_encode(
			array(
				'code' => 0,
				'msg'  => 'success',
				'data' => array(
					'total'     => 0,
					'totalPage' => 1,
					'pageNum'   => 1,
					// 'list' is missing
				),
			)
		);

		$mock_response = new Response( $response_without_list, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 0, $devices );
		$this->assertIsArray( $devices );
	}

	/**
	 * @testdox It should handle invalid list data type gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_invalid_list_data_type(): void {
		$response_with_invalid_list = wp_json_encode(
			array(
				'code' => 0,
				'msg'  => 'success',
				'data' => array(
					'total'     => 1,
					'totalPage' => 1,
					'pageNum'   => 1,
					'list'      => 'not_an_array',
				),
			)
		);

		$mock_response = new Response( $response_with_invalid_list, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 0, $devices );
		$this->assertIsArray( $devices );
	}

	/**
	 * @testdox It should handle empty device list gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_empty_list(): void {
		$response_body = $this->create_api_response( array(), 1, 1, 0 );
		$mock_response = new Response( $response_body, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 0, $devices );
		$this->assertIsArray( $devices );
	}

	/**
	 * @testdox It should properly encode special characters in API keys.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_url_encodes_keys(): void {
		$special_connection = new Connection(
			'test_key',
			'app_key_with+special&chars',
			'api_key_with=symbols',
			'00:11:22:33:44:55',
			'Test Station',
			'My Test Station'
		);

		$device_data = array( $this->create_device_data( 1 ) );
		$response_body = $this->create_api_response( $device_data );
		$mock_response = new Response( $response_body, 200 );

		$expected_url  = 'https://api.ecowitt.net/api/v3/device/list?application_key=app_key_with%2Bspecial%26amp%3Bchars&api_key=api_key_with%3Dsymbols&limit=100&page=1';

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->with( $expected_url, array() )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $special_connection );

		$this->assertCount( 1, $devices );
	}

	/**
	 * @testdox It should handle response with missing pagination fields gracefully.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_missing_pagination_fields(): void {
		$device_data = array( $this->create_device_data( 1 ) );

		$response_without_pagination = wp_json_encode(
			array(
				'code' => 0,
				'msg'  => 'success',
				'data' => array(
					'total' => 1,
					// 'totalPage' and 'pageNum' are missing
					'list'  => $device_data,
				),
			)
		);

		$mock_response = new Response( $response_without_pagination, 200 );

		$this->mock_http_service->expects( $this->once() )
			->method( 'request' )
			->willReturn( $mock_response );

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 1, $devices );
		$this->assertInstanceOf( Device::class, $devices[0] );
		$this->assertSame( 1, $devices[0]->id );
	}

	/**
	 * @testdox It should handle three-page pagination correctly.
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::get_all_devices
	 * @covers \PinkCrab\Ecowitt_Weather_Block\Ecowitt\Api\Service\Device_Service::collect_devices_recursively
	 */
	public function test_get_all_devices_three_pages(): void {
		$page1_devices = array( $this->create_device_data( 1 ) );
		$page2_devices = array( $this->create_device_data( 2 ) );
		$page3_devices = array( $this->create_device_data( 3 ) );

		$page1_response = $this->create_api_response( $page1_devices, 1, 3, 3 );
		$page2_response = $this->create_api_response( $page2_devices, 2, 3, 3 );
		$page3_response = $this->create_api_response( $page3_devices, 3, 3, 3 );

		$this->mock_http_service->expects( $this->exactly( 3 ) )
			->method( 'request' )
			->willReturnOnConsecutiveCalls(
				new Response( $page1_response, 200 ),
				new Response( $page2_response, 200 ),
				new Response( $page3_response, 200 )
			);

		$devices = $this->device_service->get_all_devices( $this->sample_connection );

		$this->assertCount( 3, $devices );
		$this->assertSame( 1, $devices[0]->id );
		$this->assertSame( 2, $devices[1]->id );
		$this->assertSame( 3, $devices[2]->id );
	}
}
