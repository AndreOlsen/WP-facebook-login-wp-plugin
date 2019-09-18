<?php
	/**
	 * The core plugin class.
	 *
	 * This is used to define admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    EasyFaceookLogin
	 * @subpackage EasyFaceookLogin/includes
	 * @author     YetAnotherWPDev
	 */

	if(!class_exists('EasyFaceookLogin')) {
		class EasyFaceookLogin {

			/**
			 * The unique identifier of this plugin.
			 *
			 * @since  1.0.0
			 * @access protected
			 * @var    string $plugin_name The string used to uniquely identify this plugin.
			 */
			protected $plugin_name;

			/**
			 * The current version of the plugin.
			 *
			 * @since  1.0.0
			 * @access protected
			 * @var    string $version The current version of the plugin.
			 */
			protected $version;

			/**
			 * @var \EasyFacebookLoginLoader
			 */
			protected $loader;

			/**
			 * Define the core functionality of the plugin.
			 *
			 * Set the plugin name and the plugin version that can be used throughout the plugin.
			 * Load the dependencies, define the locale, and set the hooks for the admin area and
			 * the public-facing side of the site.
			 *
			 * @since  1.0.0
			 * @access public
			 */
			public function __construct() {
				$this->setPluginInfo();
				$this->loadDependencies();
//				$this->set_locale();
				$this->defineAdminHooks();
				$this->definePublicHooks();
			}

			/**
			 * Set the Plugin info
			 *
			 * @since  1.0.0
			 * @access private
			 */
			private function setPluginInfo() {
				$plugin = \get_file_data(WP_PLUGIN_DIR . '/easy-facebook-login/easy-facebook-login.php', array(
					'Version'    => 'Version',
					'TextDomain' => 'Text Domain'
				), 'plugin');

				$this->plugin_name = $plugin['TextDomain'];
				$this->version = $plugin['Version'];
			}

			/**
			 * Load the required dependencies for this plugin.
			 *
			 * Create an instance of the loader which will be used to register the hooks with WordPress.
			 *
			 * @since  1.0.0
			 * @access private
			 */
			private function loadDependencies() {
				/**
				 * The class responsible for orchestrating the actions and filters of the core plugin.
				 */
				require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class.easy-facebook-login-loader.php';
				$this->loader = new EasyFacebookLoginLoader();

				/**
				 * The class responsible for defining internationalization functionality of the plugin.
				 */
//				require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-advanced-qty-i18n.php';

				/**
				 * The class responsible for defining all actions that occur in the admin area.
				 */
				require_once \plugin_dir_path(dirname(__FILE__)) . 'admin/class.easy-facebook-login-admin.php';

				/**
				 * The class responsible for defining all actions that occur in the public-facing side of the site.
				 */
				require_once \plugin_dir_path(dirname(__FILE__)) . 'public/class.easy-facebook-login-public.php';
			}

			/**
			 * Register all of the hooks related to the admin area functionality
			 * of the plugin.
			 *
			 * @since  1.0.0
			 * @access private
			 */
			private function defineAdminHooks() {
				$plugin_admin = new EasyFacebookLoginAdmin($this->plugin_name, $this->version);

				// Display settings link on plugin
				$this->loader->addFilter('plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php', $plugin_admin, 'displayPluginSettingsLink');
				// Add to options menu
				$this->loader->addAction('admin_menu', $plugin_admin, 'addPluginMenu');
			}

			/**
			 * Register all of the hooks related to the public-facing functionality
			 * of the plugin.
			 *
			 * @since  1.0.0
			 * @access private
			 */
			private function definePublicHooks() {
				$plugin_public = new EasyFacebookLoginPublic($this->plugin_name, $this->version);

				$this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueFacebookScript');
			}

			/**
			 * Run the loader to execute all of the hooks with WordPress.
			 *
			 * @since  1.0.0
			 * @access public
			 */
			public function run() {
				$this->loader->run();
			}
		}
	}