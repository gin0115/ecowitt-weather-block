<?php

/**
 * Page template for the settings page.
 *
 * @param PinkCrab\Perique\Application\App_Config                    $app_config The App Config.
 * @param PinkCrab\Ecowitt_Weather_Block\Settings\Page\Settings_Page $page       The Settings Page.
 * @param PinkCrab\Ecowitt_Weather_Block\Settings\Settings           $settings   The Settings.
 * @param string                                                     $form_nonce_key The nonce for the form.
 * @param string                                                     $submission_key The submission key for the form.
 */
?>
<div class="wrap ecowitt-admin">
	<div class="ecowitt-admin__header">
		<h1 class="ecowitt-admin__title"><?php echo esc_html( $page->page_title() ); ?></h1>
	</div>
	
	<form method="post" action="<?php echo esc_url( menu_page_url( $page->slug(), false ) ); ?>">
		<input type="hidden" name="page" value="<?php echo esc_attr( $page->slug() ); ?>" />
		<input type="hidden" name="<?php echo esc_attr( $submission_key ); ?>" value="1" />
		<?php wp_nonce_field( $form_nonce_key, $form_nonce_key ); ?>
		<div class="ecowitt-admin__content">
			<?php $this->component( $connections ); ?> 
			
			<?php submit_button(); ?>
		</div>
	</form>
</div>