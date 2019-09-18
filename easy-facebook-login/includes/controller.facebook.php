<?php

	/**
	 * Class FacebookController
	 *
	 * This class is responsible for settings, scripts and accompanying
	 * logic needed for facebook login to work with wordpress.
	 *
	 */
	class FacebookController {


		/**
		 * Register actions
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function registerActions() {
			//TODO: Rewrite hooks
			Loader::addAction('init', $this, 'init');
			Loader::addAction('wp_enqueue_scripts', $this, 'enqueueFacebookScript');
			Loader::addAction('wp_ajax_login_with_fb', $this, 'checkLogin');
			Loader::addAction('wp_ajax_nopriv_login_with_fb', $this, 'checkLogin');
		}
	}