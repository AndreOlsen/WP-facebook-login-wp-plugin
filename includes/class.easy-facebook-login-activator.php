<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 *
 * @package    EasyFacebookLogin
 * @subpackage EasyFacebookLogin/includes
 * @author     André Olsen
 */

if (!class_exists('EasyFacebookLoginActivator')) {
	class EasyFacebookLoginActivator {
		
		/**
		 * Activation
		 *
		 * @since 1.0.0
		 */
		public static function activate() {
			// Check if user is allowed to do this
			if (!current_user_can('activate_plugins')) {
				wp_die('You do not have permission to activate this plugin.');
			}

			$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
			check_admin_referer("activate-plugin_{$plugin}");

			self::createDefaultValues();
		}

		/**
		 * Creates the default options
		 *
		 * @since 1.0.0
		 */
		private static function createDefaultValues() {
			if (!get_option('efl-login-id')) {
				update_option('efl-login-id', 'efl_login');
			}

			if (!get_option('efl-logout-id')) {
				update_option('efl-logout-id', 'elf_logout');
			}
		}
	}
}
