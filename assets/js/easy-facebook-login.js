window.fbAsyncInit = function () {
	FB.init({
		appId: fb_login_object.appID,
		cookie: true,  // enable cookies to allow the server to access the session
		xfbml: true,  // parse social plugins on this page
		version: 'v4.0' // The Graph API version to use for the call
	});

	let loginState = false;
	getLoginStatus();

	// Get login status, sign in if all good
	function getLoginStatus() {
		FB.getLoginStatus(function (response) {
			console.log('LOGIN STATUS', response);
			if (response.status === 'connected') {
				// User is logged into facebook and app is authorized
				console.log('LOGIN STATUS SUCCESS', response);
				if (!fb_login_object.isUserLoggedIn) {
					console.log('USER IS LOGGED IN, AND APP IS AUTHORIZED', fb_login_object.isUserLoggedIn);
					loggedIn();
				}

				loginState = true;
			} else if(response.status === 'not_authorized') {
				// User is logged into facebook but app is NOT authorized
				console.log('USER IS LOGGED IN, BUT APP IS NOT AUTHORIZED', response);
				loginState = false;
			} else {
				// User is not logged into facebook
				console.log('LOGIN STATUS ERROR', response);
				loginState = false;
			}
		});
	}

	// If login is clicked
	jQuery(fb_login_object.loginBtnElem).on('click', loginPrompt);

	// If logout is clicked
	jQuery(fb_login_object.logoutBtnElem).on('click', function (e) {
		e.preventDefault();
		console.log('LOG OUT - CHECK LOGGED IN STATE', getLoginStatus());
		if (loginState && loginState !== 'undefined') {
			if (confirm('Du vil muligvis blive logget ud af Facebook. Fortsæt?')) {
				console.log('CALLING LOG OUT');
				logOut();
			}
		}
		window.location.replace(window.location.origin + '?logout=1');
	});

	// If already logged in to app with facebook
	function loggedIn() {
		console.log('LOGGING IN');
		FB.api('/me?fields=name,first_name,last_name,email', function (response) {
			login(response);
		});
	}

	// Login with fb api
	function loginPrompt() {
		FB.login(function (response) {
			console.log('LOGIN PROMPT', response);
			// If all is good
			if (response.authResponse) {
				console.log('LOGIN PROMPT SUCCESS', response);
				// Get data from FB and log in
				FB.api('/me?fields=name,first_name,last_name,email', function (response) {
					login(response);
				});
			} else {
				console.log('LOGIN PROMPT ERROR', response);
			}
		}, {
			scope: 'email',
		});
	}

	// Login
	function login(fbData) {
		console.log('LOGIN', fbData);
		// Display connecting class, for visual representation of stuff
		jQuery(fb_login_object.loginBtnElem).addClass('connecting');

		jQuery.ajax({
			method: 'POST',
			url: fb_login_object.ajax_url,
			data: {
				user_data: fbData,
				action: 'login_with_fb',
			}
		}).done(function (response) {
			// Remove class
			jQuery(fb_login_object.loginBtnElem).removeClass('connecting');
			if (response.success) {
				console.log('LOGIN SUCCESS', response);
				// Display another visual representation of stuff
				jQuery(fb_login_object.loginBtnElem).addClass('signingin');
				if (response.data !== 'logged_in') {
					window.location.reload();
				}
			} else {
				console.log('LOGIN ERROR', response);
				if (fb_login_object.msgElem !== 'undefined') {
					jQuery(document).find(fb_login_object.msgElem).html(response.data);
				}
			}
		});
	}

	// Log out
	function logOut() {
		FB.logout(function (response) {
			console.log('LOG OUT', response);
		});
	}
};

// Load the SDK asynchronously
(function (d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
	js.src = "https://connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));