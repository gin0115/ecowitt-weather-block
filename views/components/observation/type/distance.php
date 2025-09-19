<?php

/**
 * Component: Distance Measurement
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var string $value Distance value
 * @var string $unit Distance unit
 * @var DateTime|null $timestamp Measurement timestamp
 * @var string $label Distance label
 */

?>

<div class="measurement-card">
	<div class="measurement-card__label">
		<?php echo esc_html( $label ); ?>
	</div>
	<?php if ( ! empty( $value ) && $value !== '--' ) : ?>
		<div class="measurement-card__value">
			📏 <strong><?php echo esc_html( $value ); ?></strong><?php if ( ! empty( $unit ) ) : ?><small><?php echo esc_html( $unit ); ?></small><?php endif; ?>
		</div>
	<?php else : ?>
		<div class="measurement-card__value">📏 --</div>
	<?php endif; ?>
</div>
