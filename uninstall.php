<?php
/**
 * Fired during plugin uninstallation.
 *
 * @since      1.0.0
 *
 * @package    EasyFacebookLogin
 * @author     André Olsen
 */

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// Check user capability
if (!current_user_can('activate_plugins')) {
	wp_die('You do not have permission to uninstall this plugin.');
}

// Check if it's this plugin being deleted
if ($plugin != WP_UNINSTALL_PLUGIN) {
	return;
}

// Setting names
$options = array(
	'efl-app-id',
	'efl-secret-key',
	'efl-login-id',
	'efl-logout-id',
);

// Clean plugin options from database
foreach($options as $option) {
	// Delete option
	if (get_option($option)) {
		delete_option($option);
	}

	// Delete site options if multisite
	if (is_multisite()) {
		delete_site_option($option);
	}
}
