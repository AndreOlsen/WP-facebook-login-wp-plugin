<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    EasyFacebookLogin
 * @subpackage EasyFacebookLogin/admin
 * @author     André Olsen
 */

if (!class_exists('EasyFacebookLoginAdmin')) {
	class EasyFacebookLoginAdmin {

		/**
		 * The ID of this plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since  1.0.0
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version     The version of this plugin.
		 */
		public function __construct($plugin_name, $version) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;
		}

		/**
		 * Displays the settings link on the plugin page
		 *
		 * @since  1.0.0
		 *
		 * @param $links
		 *
		 * @return mixed
		 */
		public function displayPluginSettingsLink($links) {
			// Compose settings link
			$settings_link = '<a href="' . admin_url('options-general.php?page=options-efl') . '">' . __('Settings', 'easy-facebook-login') . '</a>';
			// Display settings link before deactivate
			array_unshift($links, $settings_link);

			return $links;
		}

		/**
		 * Add Easy Facebook Login settings menu
		 *
		 * @since 1.0.0
		 */
		public function addPluginMenu() {
			\add_options_page(
				'Easy Facebook Login',
				'Easy Facebook Login',
				'manage_options',
				'options-efl',
				array(
					$this,
					'displayPluginSettings',
				)
			);
		}

		/**
		 * Display plugin admin settings
		 *
		 * @since 1.0.0
		 */
		public function displayPluginSettings() {
			if(isset($_POST['action']))
				if(\wp_verify_nonce($_POST['_wpnonce']))
					$this->saveAdminSettings();
				else
					$this->submitNotice('Nonce expired, refresh the page and try again.', 'error');

			$args = array(
				'app_id'     => \get_option('efl-app-id'),
				'secret_key' => \get_option('efl-secret-key'),
				'login_id'   => \get_option('efl-login-id'),
				'logout_id'  => \get_option('efl-logout-id'),
			);

			$partial = __DIR__ . '/partials/settings.php';

			if(!file_exists($partial)) {
				_e('Partial template file is missing. Ensure the file is in easy-facebook-login/admin/partials/', 'easy-facebook-login');
				exit();
			}

			extract($args);

			ob_start();
			include($partial);
			$template = ob_get_clean();

			echo $template;
		}

		/**
		 * Save admin settings
		 *
		 * @since 1.0.0
		 */
		private function saveAdminSettings() {
			\update_option('efl-app-id', $_POST['efl_app-id']);
			\update_option('efl-secret-key', $_POST['efl_secret-key']);
			\update_option('efl-login-id', $_POST['efl_login-id']);
			\update_option('efl-logout-id', $_POST['efl_logout-id']);

			$this->submitNotice('Changes saved successfully.', 'success');
		}

		/**
		 * Markup and display of admin notices.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text        Text to display in the notice.
		 * @param string $notice_type Type of notice. error/success/warning/info
		 */
		private function submitNotice($text, $notice_type) {
			?>
			<div class="notice notice-<?php echo $notice_type; ?>">
				<p><?php _e($text, 'easy-facebook-login'); ?></p>
			</div>
			<?php
		}
	}
}
