<?php
	
/**
 * @link              https://profiles.wordpress.org/ravigadhiyawp
 * @package           Ajax Login Registration
 * @wordpress-plugin
 * Plugin Name:       Ajax Login Registration
 * Plugin URI:        https://www.hktechnolab.com/
 * Description:       Ajax login & registration with email template confirmation
 * Version:           1.0.1
 * Author:            Ravi Gadhiya
 * Author URI:        https://profiles.wordpress.org/ravigadhiyawp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ajax-login-registration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )	{
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_ajax_login_registration()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ajax-login-registration-activator.php';
	Ajax_Login_Registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ajax_login_registration()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ajax-login-registration-deactivator.php';
	Ajax_Login_Registration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ajax_login_registration' );
register_deactivation_hook( __FILE__, 'deactivate_ajax_login_registration' );

/**
 * The core plugin class that is used to define internationalization,
 * public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ajax-login-registration.php';

/**
 * Begins execution of the plugin.
 */
function run_ajax_login_registration()
{
	$plugin = new Ajax_Login_Registration();
	$plugin->run();
}

run_ajax_login_registration();