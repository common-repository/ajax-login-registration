<?php
	
	/**
	 * Define the internationalization functionality
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @link       https://profiles.wordpress.org/ravigadhiyawp/
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/includes
	 */
	
	/**
	 * Define the internationalization functionality.
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/includes
	 * @author     Ravi Gadhiya. <ravi.gadhiya@hktechnolab.com>
	 */
	class Ajax_Login_Registration_i18n
	{		
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain()
		{		
			load_plugin_textdomain( 'ajax-login-registration', FALSE, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
		}
	}