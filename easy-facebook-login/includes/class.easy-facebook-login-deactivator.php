<?php
	/**
	 * Fired during plugin deactivation.
	 *
	 * This class defines all code necessary to run during the plugin's deactivation.
	 *
	 * @since      1.0.0
	 * @package    EasyFacebookLogin
	 * @subpackage EasyFacebookLogin/includes
	 * @author     YetAnotherWPDev
	 */

	if(!class_exists('EasyFacebookLoginDeactivator')) {
		class EasyFacebookLoginDeactivator {

			/**
			 * Deactivation
			 *
			 * @since 1.0.0
			 */
			public static function deactivate() {
				// Check if user is allowed to do this
				if(!\current_user_can('activate_plugins'))
					\wp_die('You do not have permission to deactivate this plugin.');

				$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
				\check_admin_referer("deactivate-plugin_{$plugin}");
			}
		}
	}
