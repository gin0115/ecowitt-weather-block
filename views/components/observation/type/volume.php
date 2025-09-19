<?php
/**
 * Volume Type Component View
 *
 * @var string $value
 * @var string $unit
 * @var string $timestamp
 * @var string $label
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd
?>

<div class="measurement-card">
    <div class="measurement-card__label">
        <?php echo esc_html( $label ); ?>
    </div>
    <?php if ( ! empty( $value ) && $value !== '--' ) : ?>
        <div class="measurement-card__value">
            📦 <strong><?php echo esc_html( $value ); ?></strong><?php if ( ! empty( $unit ) ) : ?><small><?php echo esc_html( $unit ); ?></small><?php endif; ?>
        </div>
    <?php else : ?>
        <div class="measurement-card__value">📦 --</div>
    <?php endif; ?>
</div>
