<?php
	
	/**
	 * Provide a admin area view for the plugin
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       https://profiles.wordpress.org/ravigadhiyawp/
	 * @package    Ajax_Login_Registration
	 * @subpackage Ajax_Login_Registration/admin/partials
	 */

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h1>Plugin Setting</h1>
	<?php
	if( isset( $_REQUEST ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'update' ){
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax-registration-email' ) ) {
			echo '<div class="error notice is-dismissible">';
				echo '<p><strong>Security check failed.</strong></p>';
				echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		} else {
			$registration_email 	= $_REQUEST['registration_email'];
			$activation_email 		= $_REQUEST['activation_email'];
			$reset_password_email 	= $_REQUEST['reset_password_email'];
			update_option( 'registration_email', $registration_email );
			update_option( 'activation_email', $activation_email );
			update_option( 'reset_password_email', $reset_password_email );
			echo '<div class="updated notice is-dismissible">';
				echo '<p><strong>Settings saved.</strong></p>';
				echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			echo '</div>';
		}
	}
	?>
	<form method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'ajax-registration-email' ); ?>">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="registration_email">Registration Email</label></th>
					<td><?php wp_editor( get_option('registration_email'), 'registration_email', array( 'textarea_rows' => 8 ) ); ?>
					<p class="description">User get this email after registration. In the email template, you can use these mail-tags:<br /><code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[ACTIVATION_LINK]</code> <code>[USERNAME]</code></p></td>
				</tr>
				<tr>
					<th scope="row"><label for="activation_email">Activation Email</label></th>
					<td><?php wp_editor( get_option('activation_email'), 'activation_email', array( 'textarea_rows' => 8 ) ); ?>
					<p class="description">User get this email when active user account through activation link. In the email template, you can use these mail-tags:<br /><code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[NEW_PASSWORD]</code></p></td>
				</tr>
				<tr>
					<th scope="row"><label for="reset_password_email">Reset Password Email</label></th>
					<td><?php wp_editor( get_option('reset_password_email'), 'reset_password_email', array( 'textarea_rows' => 8 ) ); ?>
					<p class="description">User get this email when user request for reset password.  In the email template, you can use these mail-tags:<br /><code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[RESET_PASSWORD_LINK]</code></p></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>
