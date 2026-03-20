<?php

/**
 * Component: Generic Measurement Type
 *
 * @var PinkCrab\Perique\Interfaces\Renderable $this The renderable instance
 * @var string $value Measurement value
 * @var string $unit Measurement unit
 * @var DateTime|null $timestamp Measurement timestamp
 * @var string $label Measurement label
 * @var string $icon Emoji icon for the measurement type
 */

?>

<div class="measurement-card">
	<div class="measurement-card__label">
		<?php echo esc_html( $label ); ?>
	</div>
	<?php if ( ! empty( $value ) && $value !== '--' ) : ?>
		<div class="measurement-card__value">
			<?php echo esc_html( $icon ); ?> <strong><?php echo esc_html( $value ); ?></strong><?php if ( ! empty( $unit ) ) : ?><small><?php echo esc_html( $unit ); ?></small><?php endif; ?>
		</div>
	<?php else : ?>
		<div class="measurement-card__value"><?php echo esc_html( $icon ); ?> --</div>
	<?php endif; ?>
</div>
