<?php

use PinkCrab\Ecowitt_Weather_Block\View\Component\Admin\Device\Device_List;

/**
 * Device Page Template.
 */
dump( get_defined_vars() );
?>

<div class="wrap ecowitt-admin">
	<div class="ecowitt-admin__header">
		<h1 class="ecowitt-admin__title"><?php echo esc_html( $page->page_title() ); ?></h1>
	</div>
	<?php $this->component( new Device_List( $devices, $connection ) ); ?>
</div>