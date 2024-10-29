<?php
	
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       https://profiles.wordpress.org/ravigadhiyawp/
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/admin
	 */
	
	/**
	 * The admin-specific functionality of the plugin.
	 * Defines the plugin name, version, and hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/admin
	 * @author     Ravi Gadhiya. <ravi.gadhiya@hktechnolab.com>
	 */
	class Ajax_Login_Registration_Admin
	{
		
		/**
		 * The ID of this plugin.
		 *
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;
		
		/**
		 * The version of this plugin.
		 *
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;
		
		/**
		 * Initialize the class and set its properties.
		 *
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version )
		{			
			$this->plugin_name 		= $plugin_name;
			$this->version     		= $version;
		}

		/**
		 * Create Custom Menu for the admin area.
		 */
		public function setup_ajax_login_registration_menu()
		{
			global $pagenow;
			//Add 'Ajax Login Registration' menu 
			add_action( 'admin_menu', array( $this, 'ajax_login_registration_menu_page' ));
			
			// Add option to admin can active/deactive user
			add_action( 'show_user_profile', array( $this, 'ajax_login_registration_add_profile_fields' ) );
			add_action( 'edit_user_profile', array( $this, 'ajax_login_registration_add_profile_fields' ) );

			// Save option of user status
			add_action( 'personal_options_update', array( $this, 'ajax_login_registration_update_profile_fields' ) );
			add_action( 'edit_user_profile_update', array( $this, 'ajax_login_registration_update_profile_fields' ) );
		}

		public function ajax_login_registration_menu_page()
		{
			add_menu_page( 'Ajax Login Registration', 'Signup / Signin', 'activate_plugins', 'ajax-login-registration', array($this,'ajax_login_registration_menu'), 'dashicons-admin-users' );
			add_submenu_page( 'ajax-login-registration', 'Ajax Login Registration Documentation', 'Documentation', 'activate_plugins', 'ajax-login-registration-documentation', array($this,'ajax_login_registration_sub_menu') );
		}
		public function ajax_login_registration_menu()
		{
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ajax-login-registration-setting.php';
		}
		public function ajax_login_registration_sub_menu()
		{
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ajax-login-registration-setting-documentation.php';
		}

		/**
		 * Create user profile field
		 * 
		 **/
		public function ajax_login_registration_add_profile_fields( $user ) {

			$user_status_ary = array( 0 => 'Inactive', 1 => 'Active' );
			$user_status     = esc_attr( get_the_author_meta( 'is_activated', $user->ID ) );
			echo '<h2>' . __( 'User Information', 'ajax-login-registration' ) . '</h2>';
			echo '<table class="form-table">';
				echo '<tbody>';
					echo '<tr>';
						echo '<th><label for="user_status">' . __( 'User Status', 'ajax-login-registration' ) . '</label></th>';
						echo '<td>';
							if( ! empty( $user_status_ary ) ) {
								echo '<select name="user_status" id="user_status">';
								foreach ( $user_status_ary as $key => $value ) {
									if( $user_status == $key ) {
										echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';	
									} else {
										echo '<option value="' . $key . '">' . $value . '</option>';
									}
								}
								echo '</select>';
							}
						echo '</td>';
					echo '</tr>';
				echo '</tbody>';
			echo '</table>';
		}


		/**
		 * Save user profile field
		 * 
		 **/
		public function ajax_login_registration_update_profile_fields( $user_id ) {

			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}
			update_user_meta( $user_id, 'is_activated', intval( $_POST['user_status'] ) );
		}


	}