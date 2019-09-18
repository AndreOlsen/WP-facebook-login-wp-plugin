<?php
	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @package    EasyFacebookLogin
	 * @subpackage EasyFacebookLogin/public
	 * @author     YetAnotherWPDev
	 */

	if(!class_exists('EasyFacebookLoginPublic')) {
		class EasyFacebookLoginPublic {

			/**
			 * The ID of this plugin.
			 *
			 * @since  1.0.0
			 * @access private
			 * @var    string
			 */
			private $plugin_name;

			/**
			 * The version of this plugin.
			 *
			 * @since  1.0.0
			 * @access private
			 * @var    string
			 */
			private $version;

			/**
			 * Initialize the class and set its properties.
			 *
			 * @since 1.0.0
			 *
			 * @param string $plugin_name The name of the plugin.
			 * @param string $version     The version of this plugin.
			 */
			public function __construct($plugin_name, $version) {
				$this->plugin_name = $plugin_name;
				$this->version = $version;
			}



			/**
			 * Check if a user is from facebook
			 *
			 * @since  1.0.0
			 * @access public
			 *
			 * @param int|null $user_id
			 *
			 * @return bool
			 */
			public static function isFacebookUser($user_id = null) {
				if(!isset($user_id))
					$user_id = \wp_get_current_user()->ID;

				if($user_id === 0)
					return false;

				return !empty(\get_user_meta($user_id, '_fb_user_id', true));
			}

			/**
			 * Set properties from filters
			 *
			 * @since  1.0.0
			 * @access public
			 */
			public function init() {
				$api_info = array(
					'fb_app_id'           => $this->fb_app_id,
					'fb_button_id_login'  => $this->fb_button_id_login,
					'fb_button_id_logout' => $this->fb_button_id_logout,
				);

				$api_info = \apply_filters('easy-fb-login/api-info', $api_info);

				if(!empty($api_info) && !empty($api_info['fb_app_id']) && !empty($api_info['fb_button_id_login']) && !empty($api_info['fb_button_id_logout'])) {
					$this->fb_app_id = $api_info['fb_app_id'];
					$this->fb_button_id_login = $api_info['fb_button_id_login'];
					$this->fb_button_id_logout = $api_info['fb_button_id_logout'];
				}
			}

			/**
			 * Register, localize and enqueue scripts for facebook login
			 *
			 * @since  1.0.0
			 */
			public function enqueueFacebookScript() {
				// Register script
				\wp_register_script('fb_login', site_url() . '/wp-content/plugins/easy-facebook-login/assets/js/easy-facebook-login.js', array('jquery'), '1.0.0', true);

				// Localize script
				\wp_localize_script('fb_login', 'fb_login_object', array(
					'ajax_url'       => \admin_url('admin-ajax.php'),
					'appID'          => \get_option('efl-app-id'),
					'loginBtnElem'   => \get_option('efl-login-id'),
					'logoutBtnElem'  => \get_option('efl-logout-id'),
					'isUserLoggedIn' => \is_user_logged_in(),
				));

				// Enqueue script
				\wp_enqueue_script('fb_login');
			}

			/**
			 * Check if user is already logged in, when landing on website
			 *
			 * @since  1.0.0
			 *
			 * @return null
			 */
			public function checkLogin() {
				if(\is_user_logged_in())
					\wp_send_json_success('logged_in');

				/** @var \WP_User|\WP_Error $user */
				$user = $this->fbLogin();

				if(\is_wp_error($user)) {
					\wp_send_json_error($user->get_error_message());
				} else if(is_bool($user) && !$user) {
					\wp_send_json_error(__('An error occured when logging you in, refresh the page and try again.', 'easy-facebook-login'));
				} else if(is_a($user, 'WP_User')) {
					\wp_send_json_success();
				}

				exit();
			}

			/**
			 * Log user in, either by creating the user first
			 * or just plainly logging in
			 *
			 * @since  1.0.0
			 *
			 * @return bool|int|\WP_Error|\WP_User
			 */
			public function fbLogin() {
				$login_data = $_POST['user_data'];

				$args = array(
					'meta_key'    => '_fb_user_id',
					'meta_value'  => isset($login_data['_fb_user_id']) ? $login_data['_fb_user_id'] : '',
					'number'      => 1,
					'count_total' => false,
				);

				$user = \get_users($args);

				// User exists, log in
				if(!empty($user)) {
					return static::signUserIn($user[0]->ID);
				} else if(empty($user)) { // Doesn't exist, create & log in
					$args = array(
						'display_name' => $login_data['name'],
						'first_name'   => $login_data['first_name'],
						'last_name'    => $login_data['last_name'],
						'nickname'     => $login_data['name'],
						'user_email'   => $login_data['email'],
						'user_login'   => self::cleanString($login_data['name']),
						'user_pass'    => \wp_generate_password(),
					);

					// Create user
					$user_id = \wp_insert_user($args);

					if(\is_wp_error($user_id))
						return $user_id;

					// Save FB user id
					\update_user_meta($user_id, '_fb_user_id', $login_data['id']);

					// Sign user in
					return static::signUserIn($user_id);
				}
			}

			/**
			 * Signs the user into WordPress by setting user id as current user and setting the auth cookie.
			 *
			 * @since  1.0.0
			 *
			 * @param int $user_id
			 *
			 * @return bool|\WP_User
			 */
			public static function signUserIn($user_id) {
				// Get user data to log in
				$user = \get_userdata($user_id);

				if(is_a($user, 'WP_User')) {
					\wp_authenticate_username_password($user, $user->user_login, $user->user_pass);
					\wp_set_current_user($user->ID);
					\wp_set_auth_cookie($user->ID);
				}

				return $user;
			}

			/**
			 * Makes a string lowercase, replaces
			 * whitespace with hyphens and removes
			 * double hyphens if they happen.
			 *
			 * @since  1.0.0
			 *
			 * @param string $string
			 *
			 * @return string|string[]|null
			 */
			public static function cleanString($string) {
				// Make lower case
				$string = strtolower($string);
				// Replace spaces with hyphens
				$string = str_replace(' ', '-', $string);

				// Remove double hyphens and return
				return preg_replace('/-+/', '-', $string);
			}

		}
	}