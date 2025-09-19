<?php

/**
 * The gas measurement component.
 *
 * @since 0.1.0
 */

declare(strict_types=1);

namespace PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\Type;

use PinkCrab\Ecowitt_Weather_Block\Observation\Measurement\Base_Measurement;
use PinkCrab\Perique\Services\View\Component\Component;

/**
 * The gas measurement component.
 *
 * @view observation.type.gas
 */
class Gas extends Component {

	/**
	 * value
	 *
	 * @var string
	 */
	public $value;

	/**
	 * unit
	 *
	 * @var string
	 */
	public $unit;

	/**
	 * timestamp
	 *
	 * @var DateTime|null
	 */
	public $timestamp;

	/**
	 * label
	 *
	 * @var string
	 */
	public $label;

	/**
	 * Creates an instance of the Gas Component.
	 *
	 * @param Base_Measurement $measurement Gas measurement instance
	 * @param string $label Gas label
	 */
	public function __construct( Base_Measurement $measurement, string $label ) {
		$this->value     = esc_html( $measurement->get_value() );
		$this->unit      = esc_html( $measurement->get_unit() );
		$this->timestamp = $measurement->get_timestamp();
		$this->label     = $label;
	}
}
