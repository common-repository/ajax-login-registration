<?php
/*
 * Ajax Registration Public View
 */	
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
	$pageUrl = "https";
} else {
	$pageUrl = "http";
}
$pageUrl .= "://";
$pageUrl .= $_SERVER['HTTP_HOST'];
$pageUrl .= rtrim($_SERVER['REDIRECT_URL'], '/');

if(isset($_GET['account-activation']) && $_GET['account-activation'] == 'yes' ){
	if( isset ( $_GET['user_id']) && isset( $_GET['activation_token']) && !empty( $_GET['user_id'])  && !empty( $_GET['activation_token']) ){
		$user_id 			= $_GET['user_id'];
		$activation_token	= get_user_meta( $user_id, 'activation_token', 1 );
		$activation_time    = get_user_meta( $user_id, 'activation_token_time', 1 );
		$timeDifference	    = time() - $activation_time;
		if( ($activation_token != $_GET['activation_token']) || ( $timeDifference > 3600 ) ){
		?>
			<div>
				<div class="activation-msg"><span><?php echo __('Your activation link appears to be invalid.', 'ajax-login-registration'); ?></span></div>
			</div>
		<?php
		} else {
			$is_activated = get_user_meta( $user_id, 'is_activated', 1 );
			if( $is_activated ){
		?>
				<div>
					<div class="activation-msg"><span><?php echo __('This user already activated.', 'ajax-login-registration'); ?></span></div>
				</div>
		<?php
			} else {
				update_user_meta( $user_id, 'is_activated', 1 );
				update_user_meta( $user_id, 'user_active_status', 'registered' );
				//delete_user_meta( $user_id, 'user_active_status');
				$user_password = wp_generate_password(24, true);
				$userdata = array(
                    'ID'        =>  $user_id,
                    'user_pass' =>  $user_password
                );  
                $updated_user_id = wp_update_user($userdata);
                $user 			 = get_user_by( 'ID', $updated_user_id );
                $mail_header  	 = '<!DOCTYPE html>
									<html>
									<head>
										<title></title>
										<meta charset="utf-8">
									</head>
									<body style="margin: 0px; padding: 0px;">';
				$mail_footer    = '</body></html>';	

                /******* User Reset Email Template Start *********/
				$to				= $user->data->user_email;
                $subject 		= get_bloginfo('name').' - Account Activated';				
				$user_headers[] = 'From: '. get_bloginfo('name') . '<'. get_option('admin_email') .'>';
                $user_headers[] = 'Content-Type: text/html; charset=UTF-8';
				$mail_content   = $mail_header;
				$firstname 		= get_user_meta($user->ID, 'first_name', true);
				$lastname 		= get_user_meta($user->ID, 'last_name', true);
				if(get_option('activation_email')){
					$tokens = array(
						'FIRSTNAME' 	=> ucfirst( $firstname ),
						'LASTNAME'  	=> ucfirst( $lastname ),
						'NEW_PASSWORD' 	=> $user_password,
					);
					$mail_content .= $this->make_email_template( get_option('activation_email'), $tokens );
				} else {
					$mail_content .= '<p>Hello '.ucfirst($firstname).' '.ucfirst($lastname).',</p><p>Your account is activated successfully.</p><p>Your password is : '. $user_password .'</p>&nbsp;&nbsp;&nbsp;&nbsp;<p>Thanks &amp; Regards,<br/>'. get_bloginfo('name') .'</p>';
				}
				$mail_content  	  .= $mail_footer;
				$is_sent 		   = wp_mail( $to, $subject, $mail_content, $user_headers );
				/********* User Reset Email Template End *********/
				if( $is_sent ){
		?>
				<div>
					<div class="activation-msg"><span><?php echo __('User activated successfully.', 'ajax-login-registration'); ?></span></div>
				</div>
		<?php
				} else { 
		?>
				<div>
					<div class="activation-msg"><span><?php echo __('There was an error trying to send activation email.', 'ajax-login-registration'); ?></span></div>
				</div>
		<?php
			}
			}			
		}
	}
} else {
?>
	<div class="form-container">
		<form id="frm_user_signup" method="post">
			<div class="form-group">
				<input type="text" name="signup_username" id="signup_username" class="form-control" placeholder="<?php echo __('Username', 'ajax-login-registration'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="signup_firstname" id="signup_firstname" class="form-control" placeholder="<?php echo __('First Name', 'ajax-login-registration'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="signup_lastname" id="signup_lastname" class="form-control" placeholder="<?php echo __('Last Name', 'ajax-login-registration'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="signup_email" id="signup_email" class="form-control" placeholder="<?php echo __('Email Address', 'ajax-login-registration'); ?>">
			</div>
			<div class="form-group submit-button">
				<input type="hidden" name="signup_page" id="signup_page" value="<?php echo $pageUrl; ?>" />
				<input type="hidden" name="signup_nonce" id="signup_nonce" value="<?php echo wp_create_nonce('signup-nonce'); ?>" />
				<button type="submit" class="btn"><?php echo __('Submit', 'ajax-login-registration'); ?></button>
				<span class="ajax-loader"></span>
			</div>
			<div id="responce-messages">
				<div class="error-msg"></div>
				<div class="success-msg"></div>
				<div class="warning-msg"></div>
			</div>		
		</form>
	</div>
<?php } ?>
