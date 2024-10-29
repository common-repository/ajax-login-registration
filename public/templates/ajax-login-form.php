<?php
/*
 * Ajax Login Public View
 */
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
	$pageUrl = "https";
} else {
	$pageUrl = "http";
}
$pageUrl .= "://";
$pageUrl .= $_SERVER['HTTP_HOST'];
$pageUrl .= rtrim($_SERVER['REDIRECT_URL'], '/');
?>
<?php
if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'lostpassword' ){ ?>
<div class="form-container">
	<form id="frm_forgot_password" method="post">
		<?php
			$lost_password_instruction_text = apply_filters('alr_lost_password_instruction_text', __('Please enter your username or email address. You will receive a link to create a new password via email.', 'ajax-login-registration'));
			if( ! empty( $lost_password_instruction_text ) ) {
				echo '<div class="form-group form-info">';
					echo '<p>' . $lost_password_instruction_text . '</p>';
				echo '</div>';
			}
		?>
		<div class="form-group">				
			<input type="text" name="username" id="username" class="form-control" placeholder="<?php echo __('Username or Email Address', 'ajax-login-registration'); ?>">
		</div>
		<div class="form-group submit-button">
			<input type="hidden" name="forgot_password_page" id="forgot_password_page" value="<?php echo $pageUrl; ?>" />
			<input type="hidden" name="forgot_password_nonce" id="forgot_password_nonce" value="<?php echo wp_create_nonce('forgot-password'); ?>" />
			<button type="submit" class="btn"><?php echo __('Submit', 'ajax-login-registration'); ?></button>
			<span class="ajax-loader"></span>
		</div>
		<div id="responce-messages">
			<div class="error-msg"></div>
			<div class="success-msg"></div>
			<div class="warning-msg"></div>
			<?php
			if( isset($_REQUEST['error']) && $_REQUEST['error'] == 'invalidkey' ){
				echo '<div class="error-msg" style="display:block;">'. __('Your password reset link appears to be invalid. Please request a new link above.', 'ajax-login-registration') .'</div>';
			}
			?>
		</div>
	</form>
</div>
<?php } else if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'rp' ){ ?>
	<?php if( isset ( $_REQUEST['user_id']) && isset( $_REQUEST['rp_activation_token']) && !empty( $_REQUEST['user_id']) && !empty( $_REQUEST['rp_activation_token']) ){ ?>
		<?php
			$user_id = $_REQUEST['user_id'];
			$activation_token = get_user_meta( $user_id, 'rp_activation_token', 1 );
			$activation_time  = get_user_meta( $user_id, 'rp_activation_time', 1 );
			$timeDifference	  = time() - $activation_time;
			if( ( $activation_token != $_REQUEST['rp_activation_token'] ) || ( $timeDifference > 3600 ) ){ ?>
			<script type="text/javascript">window.location = "<?php echo $pageUrl; ?>?action=lostpassword&error=invalidkey";</script>
			<?php
			} else { ?>
				<div class="form-container">
					<form id="frm_reset_password" method="post">
						<div class="form-group">							
							<input type="password" name="new_password" id="new_password" class="form-control" placeholder="<?php echo __('New password', 'ajax-login-registration'); ?>">
						</div>
						<div class="form-group">
							<input type="password" name="renew_password" id="renew_password" class="form-control" placeholder="<?php echo __('Confirm new password', 'ajax-login-registration'); ?>">
						</div>
						<div class="form-group submit-button">
							<input type="hidden" name="rp_activation_token" id="rp_activation_token" value="<?php echo $_REQUEST['rp_activation_token']; ?>" />
							<input type="hidden" name="user_id" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" />
							<input type="hidden" name="reset_password_page" id="reset_password_page" value="<?php echo $pageUrl; ?>" />
							<input type="hidden" name="reset_password_nonce" id="reset_password_nonce" value="<?php echo wp_create_nonce('reset-password'); ?>" />
							<button type="submit" class="btn"><?php echo __('Submit', 'ajax-login-registration'); ?></button>
							<span class="ajax-loader"></span>
						</div>
						<div id="responce-messages">
							<div class="error-msg"></div>
							<div class="success-msg"></div>
						</div>
					</form>
				</div>
			<?php }
		?>
	<?php } ?>
<?php } else { ?>
<div class="form-container">
	<form id="frm_user_signin" method="post">
		<div class="form-group">
			<input type="text" name="username" id="username" class="form-control" placeholder="<?php echo __('Username or Email Address', 'ajax-login-registration'); ?>">
		</div>
		<div class="form-group">
			<input type="password" name="password" id="password" class="form-control" placeholder="<?php echo __('Password', 'ajax-login-registration'); ?>">
		</div>
		<?php
		$password_text 	= apply_filters('alr_change_reset_password_link_text', __('Forgotten Password ?', 'ajax-login-registration'));
		if( !empty($password_text) ){
		?>
		<div class="form-group forgotten-pass">
			<a href="?action=lostpassword"><?php echo $password_text; ?></a>
		</div>
		<?php } ?>
		<div class="form-group submit-button">
			<input type="hidden" name="signin_page" id="signin_page" value="<?php echo $pageUrl; ?>" />
			<input type="hidden" name="signin_nonce" id="signin_nonce" value="<?php echo wp_create_nonce('signin-nonce'); ?>" />
			<button type="submit" class="btn"><?php echo __('Submit', 'ajax-login-registration'); ?></button>
			<span class="ajax-loader"></span>
		</div>
		<div id="responce-messages">
			<div class="error-msg"></div>
			<div class="success-msg"></div>
			<?php
			if( isset($_REQUEST['checkemail']) && $_REQUEST['checkemail'] == 'confirm' ){
				echo '<div class="success-msg" style="display:block;">'. __('Check your email for the confirmation link.', 'ajax-login-registration') .'</div>';
			}
			if( isset($_REQUEST['success']) && $_REQUEST['success'] == 'rp' ){
				echo '<div class="success-msg" style="display:block;">'. __('Your password has been reset.', 'ajax-login-registration') .'</div>';
			}
			?>
		</div>
	</form>
</div>
<?php } ?>
