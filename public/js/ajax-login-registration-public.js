/** Main js Ajax Login Registration plugin **/

jQuery(document).ready(function(){

	//** User Signup **//
	if( jQuery('form#frm_user_signup').length > 0 ){
		jQuery('#frm_user_signup').validate({
			errorElement: 'span',
			errorClass: 'error',
			focusInvalid: false,
			rules: {
				signup_username: {
					required: true,
					minlength: 6,
					maxlength: 15,
				},
				signup_firstname: {
					required: true
				},
				signup_lastname: {
					required: true
				},
				signup_email: {
					required: true,
					email:true
				},
			},
			messages: {
				signup_username: {
					required: 'Username is required.',
					minlength: 'Username should contain minimum 6 characters.',
					maxlength: 'Username should contain maximum 15 characters.'
				},
				signup_firstname: {
					required: 'Firstname is required.',
				},
				signup_lastname: {
					required: 'Lastname is required.',
				},
				signup_email: {
					required: 'Email is required.',
					email:'Please enter valid email address.'
				},
			},
			submitHandler: function(form){
				jQuery('#frm_user_signup button').attr("disabled", true);
				jQuery('#frm_user_signup span.ajax-loader').css('display', 'inline-block');
				jQuery('#frm_user_signup span.error').remove();
				jQuery('#frm_user_signup #responce-messages .error-msg').hide();
				jQuery('#frm_user_signup #responce-messages .success-msg').hide();
				
				var username 		= jQuery.trim(jQuery('#signup_username').val());
				var firstname 		= jQuery.trim(jQuery('#signup_firstname').val());
				var lastname		= jQuery.trim(jQuery('#signup_lastname').val());
				var email 			= jQuery.trim(jQuery('#signup_email').val());
				var signup_page		= jQuery.trim(jQuery('#signup_page').val());
				var signup_nonce 	= jQuery('#signup_nonce').val();

				jQuery.ajax({
					url: ajaxVar.ajax_url,
					type: 'POST',
					dataType :"json",
					data: {
						action			: 'user_registration',
						username 		: username,
						firstname 		: firstname,
						lastname 		: lastname,
						email 			: email,
						signup_page 	: signup_page,
						signup_nonce 	: signup_nonce,
					},
					cache: false,
					success: function(response){
						jQuery('#frm_user_signup button').attr("disabled", false);
						jQuery('#frm_user_signup span.ajax-loader').css('visibility', 'hidden');
						if(response.errorStatus){
							if(response.hiddenError){
								jQuery('#frm_user_signup #responce-messages .error-msg').text(response.hiddenErrorMsg);
								jQuery('#frm_user_signup #responce-messages').show();
								jQuery('#frm_user_signup #responce-messages .error-msg').css('display', 'inline-block');
							}                        
							if(response.usersError){
								jQuery('#frm_user_signup #responce-messages .error-msg').text(response.usersErrorMsg);
								jQuery('#frm_user_signup #responce-messages').show();
								jQuery('#frm_user_signup #responce-messages .error-msg').css('display', 'inline-block');
							}
							
							if(response.errorusername){
								jQuery( "#signup_username" ).after( '<span id="signup_firstname-error" class="error">'+ response.errorusernameMsg +'</span>' );
							}
							if(response.errorfirstname){
								jQuery( "#signup_firstname" ).after( '<span id="signup_firstname-error" class="error">'+ response.errorfirstnameMsg +'</span>' );
							}
							if(response.errorlastname){
								jQuery( "#signup_lastname" ).after( '<span id="signup_lastname-error" class="error">'+ response.errorlastnameMsg +'</span>' );
							}			
							if(response.erroremail){
								jQuery( "#signup_email" ).after( '<span id="signup_email-error" class="error">'+ response.erroremailMsg +'</span>' );
							}
							if(response.errorpassword){
								jQuery( "#signup_password" ).after( '<span id="signup_password-error" class="error">'+ response.errorpasswordMsg +'</span>' );
							}
							if(response.errorconfirmpassword){
								jQuery( "#signup_confirmpassword" ).after( '<span id="signup_confirmpassword-error" class="error">'+ response.errorconfirmpasswordMsg +'</span>' );
							}
						} else {
							jQuery("#frm_user_signup").trigger('reset');
							if(response.emailWarning){
								jQuery('#frm_user_signup #responce-messages .warning-msg').text(response.warning);
								jQuery('#frm_user_signup #responce-messages').show();
								jQuery('#frm_user_signup #responce-messages .warning-msg').css('display', 'inline-block');
							} else {
								jQuery('#frm_user_signup #responce-messages .success-msg').text(response.success);
								jQuery('#frm_user_signup #responce-messages').show();
								jQuery('#frm_user_signup #responce-messages .success-msg').css('display', 'inline-block');
							}
							//location.reload(true);
							//window.location = response.redirecturl;
						}
					},
				});
			}
			
		});
	}

	//** User Login **//
	if( jQuery('form#frm_user_signin').length > 0 ){
		jQuery('#frm_user_signin').validate({
			errorElement: 'span',
			errorClass: 'error',
			focusInvalid: false,
			rules: {
				username: {
					required: true,
				},
				password: {
					required: true,
				}
			},
			messages: {
				username: {
					required: 'Username or Email Address is required.',
				},
				password: {
					required: 'Password is required.',
				}			
			},
			submitHandler: function(form){
				jQuery('#frm_user_signin button').attr("disabled", true);
				jQuery('#frm_user_signin span.ajax-loader').css('display', 'inline-block');
				jQuery('#frm_user_signin span.error').remove();
				jQuery('#frm_user_signin #responce-messages .error-msg').hide();
				jQuery('#frm_user_signin #responce-messages .success-msg').hide();
				var username 	= jQuery.trim(jQuery('#username').val());
				var password	= jQuery.trim(jQuery('#password').val());
				var signin_nonce= jQuery('#signin_nonce').val();
				
				jQuery.ajax({
					url: ajaxVar.ajax_url,
					type: 'POST',
					dataType :"json",
					data: {
						action			: 'user_login',
						username 		: username,
						password 		: password,
						signin_nonce 	: signin_nonce,
					},
					cache: false,
					success: function(response){
						jQuery('#frm_user_signin button').attr("disabled", false);
						jQuery('#frm_user_signin span.ajax-loader').css('visibility', 'hidden');
						if(response.errorStatus){
							if(response.hiddenError){
								jQuery('#frm_user_signin #responce-messages .error-msg').text(response.hiddenErrorMsg);
								jQuery('#frm_user_signin #responce-messages').show();
								jQuery('#frm_user_signin #responce-messages .error-msg').css('display', 'inline-block');
							}                        
							if(response.usersError){
								jQuery('#frm_user_signin #responce-messages .error-msg').text(response.usersErrorMsg);
								jQuery('#frm_user_signin #responce-messages').show();
								jQuery('#frm_user_signin #responce-messages .error-msg').css('display', 'inline-block');
							}						
							if(response.errorUsername){
								jQuery( "#username" ).after( '<span id="username-error" class="error">'+ response.errorUsernameMsg +'</span>' );
							}
							if(response.errorPassword){
								jQuery( "#password" ).after( '<span id="password-error" class="error">'+ response.errorPasswordMsg +'</span>' );
							}
						} else {
							jQuery("#frm_user_signin").trigger('reset');
							jQuery('#frm_user_signin #responce-messages .success-msg').text(response.success);
							jQuery('#frm_user_signin #responce-messages').show();
							jQuery('#frm_user_signin #responce-messages .success-msg').css('display', 'inline-block');
							//location.reload(true);
							//window.location = response.redirecturl;				
						}
					},
						
				});
			}
			
		});
	}

	//** Forgot Password **//
	if( jQuery('form#frm_forgot_password').length > 0 ){
		jQuery('#frm_forgot_password').validate({
			errorElement: 'span',
			errorClass: 'error',
			focusInvalid: false,
			
			rules: {
				username: {
					required: true,
				}
			},
			messages: {
				username: {
					required: 'Username or Email Address is required.',
				}			
			},
			submitHandler: function(form){
				jQuery('#frm_forgot_password button').attr("disabled", true);
				jQuery('#frm_forgot_password span.ajax-loader').css('display', 'inline-block');
				jQuery('#frm_forgot_password span.error').remove();
				jQuery('#frm_forgot_password #responce-messages .error-msg').hide();
				jQuery('#frm_forgot_password #responce-messages .success-msg').hide();
				var username 				= jQuery.trim(jQuery('#username').val());
				var forgot_password_page 	= jQuery.trim(jQuery('#forgot_password_page').val());
				var forgot_password_nonce 	= jQuery('#forgot_password_nonce').val();
				
				jQuery.ajax({
					url: ajaxVar.ajax_url,
					type: 'POST',
					dataType :"json",
					data: {
						action					: 'user_forgot_password',
						username 				: username,
						forgot_password_page 	: forgot_password_page,
						forgot_password_nonce 	: forgot_password_nonce,
					},
					success: function(response){
						jQuery('#frm_forgot_password button').attr("disabled", false);
						jQuery('#frm_forgot_password span.ajax-loader').css('visibility', 'hidden');
						if(response.errorStatus){
							if(response.hiddenError){
								jQuery('#frm_forgot_password #responce-messages .error-msg').text(response.hiddenErrorMsg);
								jQuery('#frm_forgot_password #responce-messages').show();
								jQuery('#frm_forgot_password #responce-messages .error-msg').css('display', 'inline-block');
							}                        
							if(response.usersError){
								jQuery('#frm_forgot_password #responce-messages .error-msg').text(response.usersErrorMsg);
								jQuery('#frm_forgot_password #responce-messages').show();
								jQuery('#frm_forgot_password #responce-messages .error-msg').css('display', 'inline-block');
							}						
							if(response.errorUsername){
								jQuery( "#email" ).after( '<span id="username-error" class="error">'+ response.errorUsernameMsg +'</span>' );
							}
						} else {
							jQuery("#frm_forgot_password").trigger('reset');
							if(response.emailWarning){
								jQuery('#frm_forgot_password #responce-messages .warning-msg').text(response.warning);
								jQuery('#frm_forgot_password #responce-messages').show();
								jQuery('#frm_forgot_password #responce-messages .warning-msg').css('display', 'inline-block');
							} else {
								jQuery('#frm_forgot_password #responce-messages .success-msg').text(response.success);
								jQuery('#frm_forgot_password #responce-messages').show();
								jQuery('#frm_forgot_password #responce-messages .success-msg').css('display', 'inline-block');
								window.location = response.redirecturl;
							}		
						}
					},
				});
			}
		});
	}

	//** Reset Password **//
	if( jQuery('form#frm_reset_password').length > 0 ){
		jQuery('#frm_reset_password').validate({
			errorElement: 'span',
			errorClass: 'error',
			focusInvalid: false,
			
			rules: {
				new_password: {
					required: true,
					minlength: 6,
					maxlength: 26,
				},
				renew_password: {
					required: true,
					equalTo: '#new_password'
				}
			},
			messages: {
				new_password: {
					required: 'Password is required.',
					minlength: 'Password should contain minimum 6 characters.',
					maxlength: 'Password should contain maximum 26 characters.',
				},
				renew_password: {
					required: 'Confirm new password is required.',
					equalTo: 'Password does not matched.'
				}			
			},
			submitHandler: function(form){
				jQuery('#frm_reset_password button').attr("disabled", true);
				jQuery('#frm_reset_password span.ajax-loader').css('display', 'inline-block');
				jQuery('#frm_reset_password span.error').remove();
				jQuery('#frm_reset_password #responce-messages .error-msg').hide();
				jQuery('#frm_reset_password #responce-messages .success-msg').hide();
				var new_password 		= jQuery.trim(jQuery('#new_password').val());
				var renew_password		= jQuery.trim(jQuery('#renew_password').val());
		        var user_id 			= jQuery('#user_id').val();
		        var rp_activation_token = jQuery('#rp_activation_token').val();
				var reset_password_nonce= jQuery('#reset_password_nonce').val();
				var reset_password_page = jQuery('#reset_password_page').val();
				
				jQuery.ajax({
					url: ajaxVar.ajax_url,
					type: 'POST',
					dataType :"json",
					data: {
						action				: 'user_reset_password',
						new_password 		: new_password,
						renew_password 		: renew_password,
		                user_id				: user_id,
		                activation_token	: rp_activation_token,
						reset_password_nonce: reset_password_nonce,
						reset_password_page : reset_password_page,
					},
					cache: false,
					success: function(response){
						jQuery('#frm_reset_password button').attr("disabled", false);
						jQuery('#frm_reset_password span.ajax-loader').css('visibility', 'hidden');
						if(response.errorStatus){    
							if(response.hiddenError){
								jQuery('#frm_reset_password #responce-messages .error-msg').text(response.hiddenErrorMsg);
								jQuery('#frm_reset_password #responce-messages').show();
								jQuery('#frm_reset_password #responce-messages .error-msg').css('display', 'inline-block');
							}                        
							if(response.usersError){
								jQuery('#frm_reset_password #responce-messages .error-msg').text(response.usersErrorMsg);
								jQuery('#frm_reset_password #responce-messages').show();
								jQuery('#frm_reset_password #responce-messages .error-msg').css('display', 'inline-block');
							}						
							if(response.errorPassword){
								jQuery( "#new_password" ).after( '<span id="new_password-error" class="error">'+ response.errorPasswordMsg +'</span>' );
							}
							if(response.errorRepassword){
								jQuery( "#renew_password" ).after( '<span id="renew_password-error" class="error">'+ response.errorRepasswordMsg +'</span>' );
							}
							if( response.redirecturl ){
								window.location = response.redirecturl;
							}
						} else {
							jQuery("#frm_reset_password").trigger('reset');
							jQuery('#frm_reset_password #responce-messages .success-msg').text(response.success);
							jQuery('#frm_reset_password #responce-messages').show();
							jQuery('#frm_reset_password #responce-messages .success-msg').css('display', 'inline-block');
							//location.reload(true);
							window.location = response.redirecturl;
						}
					},
				});
			}
		});
	}
});
