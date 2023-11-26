<?php
/**
 * @wordpress-plugin
 * Plugin Name:      Easy Facebook Login
 * Description:      Lets your users login with facebook.
 * Version:          1.0.0
 * Author:           André Winther Olsen
 * Author URI:		 https://andreolsen.github.io
 * Text Domain:      easy-facebook-login
 * Domain Path:      /languages/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) die;

/**
 * Activation
 *
 * @since 1.0.0
 */
if (!function_exists('easyFacebookLoginActivation')) {
	function easyFacebookLoginActivation() {
		require_once \plugin_dir_path(__FILE__) . 'includes/class.easy-facebook-login-activator.php';
		EasyFacebookLoginActivator::activate();
	}

	\register_activation_hook(__FILE__, 'easyFacebookLoginActivation');
}

/**
 * Deactivation
 *
 * @since 1.0.0
 */
if (!function_exists('easyFacebookLoginDeactivation')) {
	function easyFacebookLoginDeactivation() {
		require_once \plugin_dir_path(__FILE__) . 'includes/class.easy-facebook-login-deactivator.php';
		EasyFacebookLoginDeactivator::deactivate();
	}

	\register_deactivation_hook(__FILE__, 'easyFacebookLoginDeactivation');
}

/**
 * Run plugin
 *
 * @since 1.0.0
 */
if (!function_exists('runEasyFacebookLogin')) {
	require \plugin_dir_path(__FILE__) . 'includes/class.easy-facebook-login.php';

	function runEasyFacebookLogin() {
		$plugin = new EasyFaceookLogin();
		$plugin->run();
	}

	runEasyFacebookLogin();
}
