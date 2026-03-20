<?php

/**
 * Generic measurement type view component.
 *
 * Replaces all individual type components (Temperature, Humidity, etc.)
 * with a single class that handles icon resolution via the measurement type.
 *
 * @since 0.2.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type;

use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Generic measurement type view component.
 */
class Measurement_Type extends Component {

	/**
	 * Icon mapping from measurement type to emoji.
	 */
	public const ICONS = array(
		'temperature'     => "\xF0\x9F\x8C\xA1\xEF\xB8\x8F",
		'humidity'        => "\xF0\x9F\x92\xA7",
		'wind_speed'      => "\xF0\x9F\x92\xA8",
		'wind_direction'  => "\xF0\x9F\xA7\xAD",
		'pressure'        => "\xF0\x9F\x8C\xA1\xEF\xB8\x8F",
		'rainfall'        => "\xF0\x9F\x8C\xA7\xEF\xB8\x8F",
		'rain_rate'       => "\xF0\x9F\x8C\xA7\xEF\xB8\x8F",
		'solar_radiation' => "\xE2\x98\x80\xEF\xB8\x8F",
		'uv_index'        => "\xF0\x9F\x95\xB6\xEF\xB8\x8F",
		'battery'         => "\xF0\x9F\x94\x8B",
		'voltage'         => "\xF0\x9F\x94\x8C",
		'percentage'      => "\xF0\x9F\x93\x8A",
		'distance'        => "\xF0\x9F\x93\x8F",
		'count'           => "\xF0\x9F\x94\xA2",
		'co2'             => "\xF0\x9F\x8C\xAC\xEF\xB8\x8F",
		'air_quality'     => "\xF0\x9F\x8C\xAB\xEF\xB8\x8F",
		'soil_moisture'   => "\xF0\x9F\x8C\xB1",
		'leaf_wetness'    => "\xF0\x9F\x8D\x83",
		'power'           => "\xE2\x9A\xA1",
		'energy'          => "\xE2\x9A\xA1",
		'flow_rate'       => "\xF0\x9F\x92\xA7",
		'volume'          => "\xF0\x9F\x93\xA6",
	);

	/**
	 * @var string
	 */
	public $value;

	/**
	 * @var string
	 */
	public $unit;

	/**
	 * @var \DateTime|null
	 */
	public $timestamp;

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var string
	 */
	public $icon;

	/**
	 * Creates an instance of the Measurement Type Component.
	 *
	 * @param Base_Measurement $measurement Measurement instance
	 * @param string           $label       Measurement label
	 * @param string           $icon        Emoji icon to display
	 */
	public function __construct( Base_Measurement $measurement, string $label, string $icon = '' ) {
		$this->value     = esc_html( $measurement->get_value() );
		$this->unit      = esc_html( $measurement->get_unit() );
		$this->timestamp = $measurement->get_timestamp();
		$this->label     = $label;
		$this->icon      = $icon;
	}

	/**
	 * Resolve the template path for this component.
	 *
	 * @return string
	 */
	public function template(): string {
		return 'observation.type.measurement';
	}

	/**
	 * Create a Measurement_Type with the icon auto-resolved from the measurement type.
	 *
	 * @param Base_Measurement $measurement Measurement instance
	 * @param string           $label       Measurement label
	 * @return self
	 */
	public static function for_type( Base_Measurement $measurement, string $label ): self {
		$icon = self::ICONS[ $measurement->get_type() ] ?? '';
		return new self( $measurement, $label, $icon );
	}
}
