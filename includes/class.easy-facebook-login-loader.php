<?php
/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    EasyFacebookLogin
 * @subpackage EasyFacebookLogin/includes
 * @author     André Olsen
 */

if (!class_exists('EasyFacebookLoginLoader')) {
	class EasyFacebookLoginLoader {
		
		/**
		 * The array of actions registered with WordPress.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @var    array $actions The actions registered with WordPress to fire when the plugin loads.
		 */
		protected $actions;

		/**
		 * The array of filters registered with WordPress.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @var    array $filters The filters registered with WordPress to fire when the plugin loads.
		 */
		protected $filters;

		/**
		 * The array of filters to be removed with WordPress.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @var    array $filters The filters removed from WordPress when the plugin loads.
		 */
		protected $remove_filters;

		/**
		 * Initialize the collections used to maintain the actions and filters.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			$this->actions = array();
			$this->filters = array();
			$this->remove_filters = array();
			$this->addAction('init', $this, 'removeFilters');
		}

		/**
		 * Add a new action to the collection to be registered with WordPress.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param string $hook          The name of the WordPress action that is being registered.
		 * @param object $component     A reference to the instance of the object on which the action is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      The priority at which the function should be fired.
		 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
		 */
		public function addAction($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
			$this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
		}

		/**
		 * Add a new filter to the collection to be registered with WordPress.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param string $hook          The name of the WordPress filter that is being registered.
		 * @param object $component     A reference to the instance of the object on which the filter is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      Optional. The priority at which the function should be fired.
		 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback.
		 */
		public function addFilter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
			$this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
		}

		/**
		 * Remove a filter in the registered with WordPress
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param string $hook          The name of the WordPress filter that is being registered.
		 * @param object $component     A reference to the instance of the object on which the filter is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      Optional. The priority at which the function should be fired.
		 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback.
		 */
		public function removeFilter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
			$this->remove_filters = $this->add($this->remove_filters, $hook, $component, $callback, $priority, $accepted_args);
		}

		/**
		 * A utility function that is used to register the actions and hooks into a single
		 * collection.
		 *
		 * @since  1.0.0
		 * @access private
		 *
		 * @param array  $hooks         The collection of hooks that is being registered (that is, actions or filters).
		 * @param string $hook          The name of the WordPress filter that is being registered.
		 * @param object $component     A reference to the instance of the object on which the filter is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      The priority at which the function should be fired.
		 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
		 *
		 * @return array                 The collection of actions and filters registered with WordPress.
		 */
		private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
			$hooks[] = array(
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args,
			);

			return $hooks;
		}

		/**
		 * Register the filters and actions with WordPress.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function run() {
			foreach($this->filters as $hook) {
				add_filter($hook['hook'], ($hook['component'] != null ? array($hook['component'], $hook['callback']) : $hook['callback']), $hook['priority'], $hook['accepted_args']);
			}

			foreach($this->actions as $hook) {
				add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
			}
		}

		/**
		 * Register the filters and actions with WordPress.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function removeFilters() {
			foreach($this->remove_filters as $hook) {
				$this->removeFilter($hook['hook'], ($hook['component'] != null ? array($hook['component'], $hook['callback']) : $hook['callback']), $hook['priority']);
			}
		}
	}
}
