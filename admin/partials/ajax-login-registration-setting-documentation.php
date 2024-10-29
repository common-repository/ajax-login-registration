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
	<h1>Plugin Documentation</h1>
	<h3>Registration :</h3>
	<p>In this plugin registration process is as follow :</p>
	<ol>
		<li>When user registrate with registration form, user get email with account acivation link. Without active account user can't login.</li>
		<li>After successfull account activation, user get another mail with confirmation of account acivation and user password. With this password and username or email user can login in website.</li>
		<li>If user forgot password user can request to reset password. After requesting of reset password user get an email with password reset link. User can open link in browser and can reset password.</li>
	</ol>
	<h3>Shortcode :</h3>
	<p>Add below shortcode to add login and registration form :</p>
	<ol>
		<li>Login : <strong><code>[ajax-login]</code></strong></li>
		<li>Registration : <strong><code>[ajax-registration]</code></strong></li>
	</ol>
</div>
