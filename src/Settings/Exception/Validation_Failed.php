<?php 

declare(strict_types=1);

/**
 * Exception thrown when validation of settings fails.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 *
 * @since 0.1.0
 */

namespace PinkCrab\Ecowitt_Weather_Block\Settings\Exception;

/**
 * Validation failed exception.
 */
class Validation_Failed extends \RuntimeException {
    protected array $errors;

    public function __construct( array $errors ) {
        parent::__construct('Validation failed');
        $this->errors = $errors;
    }

    public function get_errors(): array {
        return $this->errors;
    }
}