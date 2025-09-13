<?php

/**
 * All classes which should be initiated on plugin load.
 *
 * @see https://perique.info/core/App/setup#registration
 * @since 0.1.0
 *
 * @return array<class-string>
 */

return array(
	\PinkCrab\Ecowitt_Weather_Block\Settings\Page\Settings_Page::class,
	\PinkCrab\Ecowitt_Weather_Block\Admin\Asset_Loader::class,
	\PinkCrab\Ecowitt_Weather_Block\Admin\Page\Device_Page::class,
	\PinkCrab\Ecowitt_Weather_Block\Admin\Page\Ajax\Live_Observation_Ajax::class,
);
