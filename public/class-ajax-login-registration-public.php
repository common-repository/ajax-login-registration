<?php
	
	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       https://profiles.wordpress.org/ravigadhiyawp/
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/public
	 */
	
	/**
	 * The public-facing functionality of the plugin.
	 * Defines the plugin name, version, and hooks to
	 *
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/public
	 * @author     Ravi Gadhiya. <ravi.gadhiya@hktechnolab.com>
	 */
	class Ajax_Login_Registration_Public
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
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version )
		{	
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}
		
		/**
		 * Register the stylesheets for the public-facing side of the site.
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/library-book-search-public.css', array (), $this->version, 'all' );
		}
		
		/**
		 * Register the JavaScript for the public-facing side of the site.
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.js', array ( 'jquery' ), $this->version, TRUE );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ajax-login-registration-public.js', array ( 'jquery' ), $this->version, TRUE );
			$localized_data['ajax_url']	= admin_url('admin-ajax.php');
			// Localize the script with new data
			wp_localize_script( $this->plugin_name, 'ajaxVar', $localized_data);
		}

		public function ajax_login_registration_add_shortcode()
		{
			//Ajax registration shortcode addded here
			add_shortcode('ajax-registration',array($this,'ajax_registration_view'));
			
			// executing ajax for registration
			add_action('wp_ajax_user_registration', array( $this, 'fn_user_registration'));
			add_action('wp_ajax_nopriv_user_registration', array( $this, 'fn_user_registration'));

			//Ajax login shortcode addded here
			add_shortcode('ajax-login',array($this,'ajax_login_view'));
			
			// executing ajax for login
			add_action('wp_ajax_user_login', array( $this, 'fn_user_login'));
			add_action('wp_ajax_nopriv_user_login', array( $this, 'fn_user_login'));

			// executing ajax for forgot password
			add_action('wp_ajax_user_forgot_password', array( $this, 'fn_user_forgot_password'));
			add_action('wp_ajax_nopriv_user_forgot_password', array( $this, 'fn_user_forgot_password'));

			// executing ajax for reset password
			add_action('wp_ajax_user_reset_password', array( $this, 'fn_user_reset_password'));
			add_action('wp_ajax_nopriv_user_reset_password', array( $this, 'fn_user_reset_password'));

		}

		// this member function display registration form and all books
		public function ajax_registration_view()
		{
			//Display Registration Form
			ob_start();
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/ajax-registration-form.php';
			return ob_get_clean();
		}

		// this member function display login form and all books
		public function ajax_login_view()
		{
			//Display Login Form
			ob_start();
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/ajax-login-form.php';
			return ob_get_clean();
		}

		//Generate Random String 
		public function generateRandomString($length = 20)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

		/**
		 * Create Custom email template function.
		 */
		function make_email_template($mail_content = "", $tokens = array())
		{
			$pattern = '[%s]';
			$map = array();
			foreach($tokens as $var => $value){
				$map[sprintf($pattern, $var)] = $value;
			}
			$mail_message = strtr($mail_content, $map);
			return $mail_message;
		}

		// registration ajax callback action
		public function fn_user_registration()
		{
			$response 		  = array();
			$username		  = $_POST['username'];
			$firstname		  = $_POST['firstname'];
			$lastname		  = $_POST['lastname'];
			$email			  = $_POST['email'];
			$signup_page	  = $_POST['signup_page'];
			$signup_nonce     = $_POST['signup_nonce'];
			 
			$response['errorStatus'] 			= false;
		    $response['hiddenError'] 			= false;
		    $response['errorusername'] 			= false;
		    $response['errorfirstname'] 		= false;
		    $response['errorlastname'] 			= false;
		    $response['erroremail'] 			= false;
		    $response['emailWarning'] 			= false;

		    if ( !wp_verify_nonce( $signup_nonce, 'signup-nonce' ) ){
		    	$response['errorStatus'] = true;
		    	$response['hiddenError'] = true;
		    	$response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
		    }
		    if( empty ( $username )){
		    	$response['errorStatus'] = true;
		    	$response['errorusername'] = true;
		    	$response['errorusernameMsg'] = __('Username is required.', 'ajax-login-registration');
			} else if( strlen($username) < 6 ){
				$response['errorStatus'] = true;
		        $response['errorusername'] = true;
		        $response['errorusernameMsg'] = __('Username should contain minimum 6 characters.', 'ajax-login-registration');
			} else if( strlen($username) > 18 ){
				$response['errorStatus'] = true;
		        $response['errorusername'] = true;
		        $response['errorusernameMsg'] = __('Username should contain maximum 15 characters.', 'ajax-login-registration');
			}else if(username_exists( $username )){
		    	$response['errorStatus'] = true;
		    	$response['errorusername'] = true;
		    	$response['errorusernameMsg'] = __('Username already exist.Try with different Username.', 'ajax-login-registration');
		    }
		    if( empty ( $firstname )){
		    	$response['errorStatus'] = true;
		    	$response['errorfirstname'] = true;
		    	$response['errorfirstnameMsg'] = __('Firstname is required.', 'ajax-login-registration');
			}
			if( empty ( $lastname )){
				$response['errorStatus'] = true;
				$response['errorlastname'] = true;
				$response['errorlastnameMsg'] = __('Lastname is required.', 'ajax-login-registration');
		    }
		    if( empty ( $email )){
		    	$response['errorStatus'] = true;
		    	$response['erroremail'] = true;
		    	$response['erroremailMsg'] = __('Email is required.', 'ajax-login-registration');
		    } else if(!is_email( $email )){
		    	$response['errorStatus'] = true;
		    	$response['erroremail'] = true;
		    	$response['erroremailMsg'] = __('Please enter a valid email address.', 'ajax-login-registration');
		    } else if(email_exists( $email )){
		    	$response['errorStatus'] = true;
		    	$response['erroremail'] = true;
		    	$response['erroremailMsg'] = __('Email already exist.Try with different Email address.', 'ajax-login-registration');
		    }

		    if($response['errorStatus'] == false){
		    	$new_user_id = wp_insert_user(array(
		    		'first_name' => ucfirst($firstname),
		    		'last_name'	 => ucfirst($lastname),
		    		'user_email' => $email,
		    		'user_login' => $username,
		    		'role'		 => 'subscriber'
		    	));

		    	if( !is_wp_error($new_user_id) ){
					if($new_user_id){
						$activation_token 		= $this->generateRandomString();
		                $activation_page_url 	= $signup_page.'?account-activation=yes&user_id='.$new_user_id.'&activation_token='.$activation_token;
						$activation_link 		= '<a href="'. $activation_page_url .'">Active Account</a>';
						update_user_meta( $new_user_id, 'activation_token', $activation_token );
						update_user_meta( $new_user_id, 'activation_token_time', time() );
						update_user_meta( $new_user_id, 'is_activated', 0 );
						update_user_meta( $new_user_id, 'user_active_status', 'pending' );
						$mail_header  = '<!DOCTYPE html>
										<html>
										<head>
											<title></title>
											<meta charset="utf-8">
										</head>
										<body style="margin: 0px; padding: 0px;">';
						$mail_footer  = '</body></html>';		
						
		                /******* User Email Template Start *********/
						$to 		  	= $email;
		                $subject 		= get_bloginfo('name').' - User Activation';				
						$user_headers[] = 'From: '. get_bloginfo('name') . '<'. get_option('admin_email') .'>';
		                $user_headers[] = 'Content-Type: text/html; charset=UTF-8';
						$mail_content   = $mail_header;
						if(get_option('registration_email')){
							$tokens = array(
								'FIRSTNAME' 		=> ucfirst($firstname),
								'LASTNAME'  		=> ucfirst($lastname),
								'ACTIVATION_LINK' 	=> $activation_link,
							);
							$mail_content .= $this->make_email_template( get_option('registration_email'), $tokens );
						} else {
							$mail_content .= '<p>Hello '.ucfirst($firstname).' '.ucfirst($lastname).',</p><p>Thanks you for your registration.</p><p>Please click below link to active account.</p><p><a href="'. $activation_page_url .'">Active Account</a></p>&nbsp;&nbsp;&nbsp;&nbsp;<p>Thanks &amp; Regards,<br/>'. get_bloginfo('name') .'</p>';
						}
						$mail_content  	  .= $mail_footer;
						$is_sent 		   = wp_mail( $to, $subject, $mail_content, $user_headers );
						/********* User Email Template End *********/

						/******* Admin Email Template Start *********/
						$admin_to 	  		 = apply_filters('alr_change_admin_email', get_option('admin_email'));
						$admin_from 	  	 = apply_filters('alr_change_registration_admin_email_from', get_bloginfo('name') .'<'. get_option('admin_email') .'>');
		                $admin_subject   	 = apply_filters('alr_change_registration_admin_email_sub', get_bloginfo('name').' - New User Registration');
						$admin_headers[] 	 = 'From: '. $admin_from;
		                $admin_headers[] 	 = 'Content-Type: text/html; charset=UTF-8';
						$admin_email_msg 	 = '<p>Hello Admin,</p><p>A new user is registered with follow details:</p><p><b>Name :</b> '.ucfirst($firstname).' '.ucfirst($lastname).'<br/><b>Email :</b> '.$email.'</p>&nbsp;&nbsp;&nbsp;&nbsp;<p>Thanks &amp; Regards,<br/>'. get_bloginfo('name') .'</p>';
						$admin_mail_content  = $mail_header;
						$admin_mail_content .= apply_filters('alr_change_admin_registration_email_msg', $admin_email_msg);
						$admin_mail_content .= $mail_footer;
						$is_sent1 			 = wp_mail( $admin_to, $admin_subject, $admin_mail_content, $admin_headers );
						/********* Admin Email Template End *********/

						if( $is_sent ){
							$response['errorStatus'] = false;
		                	$response['success'] = __('Thanks for your registration. Please check you email for account activation link.', 'ajax-login-registration');
						} else {
							$response['errorStatus'] = false;
							$response['emailWarning'] = true;
		                	$response['warning'] = __('There was an error trying to send account activation email. Please contact site admin to active your account.', 'ajax-login-registration');
						}
					}
				} else {
					$response['errorStatus'] = true;
		            $response['usersError'] = true;
		            $response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
				}				
			}
			echo json_encode($response);
			exit;
		}

		// login ajax callback action
		public function fn_user_login()
		{
			$response 		= array();
			$username		= $_POST['username'];
			$password		= $_POST['password'];
			$signin_nonce  	= $_POST['signin_nonce'];
			$response['redirecturl']    = "";
			$response['errorStatus'] 	= false;
		    $response['hiddenError'] 	= false;
		    $response['errorUsername'] 	= false;
		    $response['errorPassword']  = false;
		    $response['usersError'] 	= false;

		    if ( ! wp_verify_nonce( $signin_nonce, 'signin-nonce' ) ){
		    	$response['errorStatus'] 		= true;
		    	$response['hiddenError'] 		= true;
		    	$response['hiddenErrorMsg'] 	= __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
		    }    
		    if( empty ( $username )){
		    	$response['errorStatus'] 		= true;
		    	$response['errorUsername'] 		= true;
		    	$response['errorUsernameMsg']	= __('Username or Email Address is required.', 'ajax-login-registration');
		    }

		    if( empty ( $password )){
		        $response['errorStatus'] 		= true;
		        $response['errorPassword'] 		= true;
		        $response['errorPasswordMsg'] 	= __('Password is required.', 'ajax-login-registration');
		    }
				
			if($response['errorStatus'] == false) {
				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
	        		$user = get_user_by('email', $username);
	    		} else {
					$user = get_user_by('login', $username);
				}

				if ($user && wp_check_password( $password, $user->data->user_pass, $user->ID)) {
					if ( get_user_meta( $user->ID, 'is_activated', 1 ) != true ) {
						$response['errorStatus'] 	= true;
						$response['usersError'] 	= true;
						$response['usersErrorMsg'] 	= __('Your account may be inactive or blocked.', 'ajax-login-registration');
					} else {
						$creds = array('user_login' => $user->data->user_login, 'user_password' => $password );
						$user2 = wp_signon( $creds, false );
						wp_set_auth_cookie($user2->ID, true, false );
						do_action( 'wp_login', $user2->user_login );
						if( !is_wp_error($user2) ) {
							$response['redirecturl'] 	= '';
							$response['errorStatus'] 	= false;
							$response['success'] 	 	= __('Login successfully completed.', 'ajax-login-registration');
						} else {
							$response['errorStatus'] 	= true;
				            $response['usersError'] 	= true;
				            $response['usersErrorMsg'] 	= __('Email Address/Username or Password you entered is incorrect.', 'ajax-login-registration');
						}
					}
				} else {
					$response['errorStatus'] = true;
		            $response['usersError'] = true;
		            $response['usersErrorMsg'] = __('Email Address/Username or Password you entered is incorrect.', 'ajax-login-registration');
				}
			}
			echo json_encode($response);
			exit;
		}

		// forgot-password ajax callback action
		public function fn_user_forgot_password()
		{
			$response 					= array();
			$username					= $_POST['username'];
			$forgot_password_page		= $_POST['forgot_password_page'];
			$forgot_password_nonce  	= $_POST['forgot_password_nonce'];
			$response['redirecturl']    = '';
			$response['errorStatus'] 	= false;
		    $response['hiddenError'] 	= false;
		    $response['errorUsername'] 	= false;
		    $response['usersError'] 	= false;
		    $response['emailWarning'] 	= false;
		    		    
		    if ( ! wp_verify_nonce( $forgot_password_nonce, 'forgot-password' ) ){
		        $response['errorStatus'] 	= true;
		        $response['hiddenError'] 	= true;
		        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
		    }    
		    
		    if( empty ( $username )){
		        $response['errorStatus'] = true;
		        $response['errorUsername'] = true;
		        $response['errorUsernameMsg'] = __('Username or Email Address is required.', 'ajax-login-registration');
		    }
			
			if($response['errorStatus'] == false) {

				if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
	        		$user = get_user_by('email', $username);
	    		} else {
					$user = get_user_by('login', $username);
				}
				if ($user) {
					$currentPageUrl			= strtok( $forgot_password_page, '?' );
					$activation_token 		= $this->generateRandomString();
					$activation_page_url 	= $currentPageUrl.'?action=rp&user_id='.$user->ID.'&rp_activation_token='.$activation_token;
					$activation_link 		= '<a href="'. $activation_page_url .'">Reset Password</a>';
					update_user_meta( $user->ID, 'rp_activation_token', $activation_token );
					update_user_meta( $user->ID, 'rp_activation_time', time() );
					update_user_meta( $user->ID, 'rp_password_reset', 0 );
					$mail_header  = '<!DOCTYPE html>
									<html>
									<head>
										<title></title>
										<meta charset="utf-8">
									</head>
									<body style="margin: 0px; padding: 0px;">';
					$mail_footer  = '</body></html>';	

					/******* User Reset Email Template Start *********/
					$to				= $user->data->user_email;
	                $subject 		= get_bloginfo('name').' - Reset Password';				
					$user_headers[] = 'From: '. get_bloginfo('name') . '<'. get_option('admin_email') .'>';
	                $user_headers[] = 'Content-Type: text/html; charset=UTF-8';
					$mail_content   = $mail_header;
					$firstname 		= get_user_meta($user->ID, 'first_name', true);
					$lastname 		= get_user_meta($user->ID, 'last_name', true);
					if(get_option('reset_password_email')){
						$tokens = array(
							'FIRSTNAME' 			=> ucfirst( $firstname ),
							'LASTNAME'  			=> ucfirst( $lastname ),
							'RESET_PASSWORD_LINK' 	=> $activation_link,
						);
						$mail_content .= $this->make_email_template( get_option('reset_password_email'), $tokens );
					} else {
						$mail_content .= '<p>Hello '.ucfirst($firstname).' '.ucfirst($lastname).',</p><p>You has requested a password reset for the '. get_bloginfo('name') .' account.</p><p>If you ingnore this message, password wonts be chnage. To reset your password, click on below link.</p><p><a href="'. $activation_page_url .'">Reset Password</a></p>&nbsp;&nbsp;&nbsp;&nbsp;<p>Thanks &amp; Regards,<br/>'. get_bloginfo('name') .'</p>';
					}
					$mail_content  	  .= $mail_footer;
					$is_sent 		   = wp_mail( $to, $subject, $mail_content, $user_headers );
					/********* User Reset Email Template End *********/
					if( $is_sent ){
						$response['redirecturl'] = $currentPageUrl.'?checkemail=confirm';
						$response['errorStatus'] = false;
						$response['success'] = __('Check your email for the password reset link.', 'ajax-login-registration');
					} else {
						$response['errorStatus'] = false;
						$response['emailWarning'] = true;
	                	$response['warning'] = __('There was an error trying to send password reset email.', 'ajax-login-registration');
					}
				} else {
					$response['errorStatus'] = true;
		            $response['usersError'] = true;
		            $response['usersErrorMsg'] = __('There is no account with that username or email address.', 'ajax-login-registration');
				}
			}
			echo json_encode($response);
			exit;
		}
		// reset-password ajax callback action
		public function fn_user_reset_password()
		{
			$response = array();
			$user_password 	  	 = trim( $_POST['new_password']);
			$user_repassword 	 = trim( $_POST['renew_password']);
			$reset_password_nonce= trim( $_POST['reset_password_nonce']);
			$user_id 			 = trim( $_POST['user_id']);
		    $rp_activation_token = trim( $_POST['activation_token']);
		    $reset_password_page = trim( $_POST['reset_password_page']);
			
			$response['redirecturl']    = "";
			$response['errorStatus'] 	= false;
			$response['hiddenError'] 	= false;
		    $response['usersError'] 	= false;
		    $response['errorPassword'] 	= false;
		    $response['errorRepassword']= false;
		    
		    if ( ! wp_verify_nonce( $reset_password_nonce, 'reset-password' ) ){
		        $response['errorStatus'] = true;
		        $response['hiddenError'] = true;
		        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
		    }
		    if( empty ( $user_password )){
		        $response['errorStatus'] = true;
		        $response['errorPassword'] = true;
		        $response['errorPasswordMsg'] = __('Password is required.', 'ajax-login-registration');
		    } else if( strlen($user_password) < 6 ){
				$response['errorStatus'] = true;
		        $response['errorPassword'] = true;
		        $response['errorPasswordMsg'] = __('Password should contain minimum 6 characters.', 'ajax-login-registration');
			} else if( strlen($user_password) > 26 ){
				$response['errorStatus'] = true;
		        $response['errorPassword'] = true;
		        $response['errorPasswordMsg'] = __('Password should contain maximum 26 characters.', 'ajax-login-registration');
			} 
			
		    if( empty ( $user_repassword )){
		        $response['errorStatus'] = true;
		        $response['errorRepassword'] = true;
		        $response['errorRepasswordMsg'] = __('Confirm new password is required.', 'ajax-login-registration');
		    } else if( $user_repassword != $user_password){
				$response['errorStatus'] = true;
		        $response['errorRepassword'] = true;
		        $response['errorRepasswordMsg'] = __('Password does not matched.', 'ajax-login-registration');
			}

			if($response['errorStatus'] == false){
				$currentPageUrl			= strtok( $reset_password_page, '?' );
				$db_activation_token	= get_user_meta( $user_id, 'rp_activation_token', 1 );
		        if($db_activation_token == $rp_activation_token){
		            $pass_reset	= get_user_meta( $user_id, 'rp_password_reset', 1 );
		            if( $pass_reset == 0 ) {
		                $userdata = array(
		                    'ID'        =>  $user_id,
		                    'user_pass' =>  $user_password
		                );  
		                $updated_user_id = wp_update_user($userdata);                
		                if($user_id == $updated_user_id) {
		                    update_user_meta( $user_id, 'rp_password_reset', 1 );
		                    delete_user_meta($user_id, 'rp_activation_token');
		                    update_user_meta( $user_id, 'is_activated', 1 );
		                    $response['errorStatus'] = false;
		                    $response['redirecturl'] = $currentPageUrl.'?success=rp';
		                    $response['success'] 	 = __('Your password has been reset.', 'ajax-login-registration');
		                }
		            } else {
		                $response['errorStatus'] = true;
		                $response['usersError'] = true;
		                $response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'ajax-login-registration');
		            }
		        } else {
		            $response['errorStatus'] 	= true;
		            $response['usersError'] 	= true;
		            $response['redirecturl'] 	= $currentPageUrl.'?action=lostpassword&error=invalidkey';
		            $response['usersErrorMsg']  = __('Activation token is invalid.', 'ajax-login-registration');
		        }
			}
			echo json_encode($response);
			exit;
		}

		public function test(){
			echo 'Test';
		}
	}
