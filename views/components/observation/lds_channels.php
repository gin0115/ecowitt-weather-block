<?php
/**
 * LDS Channels Group Component View
 *
 * @var PinkCrab\Ecowitt_Weather_Block\View\Component\Observation\LDS_Channels $lds_channels
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd
?>

<div class="measurement-section">
    <h3 class="device__title"><?php esc_html_e( 'LDS Channels', 'pinkcrab-weather-block' ); ?></h3>
    <div class="device-cards">
        <?php foreach ( $lds_channels->channels as $channel ) : ?>
            <div class="channel-group">
                <h4 class="channel-title"><?php echo esc_html( $channel['title'] ); ?></h4>
                <div class="channel-measurements">
                    <?php foreach ( $channel['measurements'] as $measurement ) : ?>
                        <?php $this->component( $measurement ); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
