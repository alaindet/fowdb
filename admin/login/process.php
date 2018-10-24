<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';

use \App\Services\CsrfToken;
use \App\Http\Request\Input;
use \App\Legacy\Authentication;

$input = Input::getInstance();

// ERROR: Missing token
if (!$input->exists(CsrfToken::NAME, 'POST')) {
	
	notify('Missing anti-CSRF token.', 'danger');
	redirect('admin');
}

// ERROR: Invalid token
if (!CsrfToken::check($input->post('_token'))) {

	notify('Invalid anti-CSRF token.', 'danger');
	redirect('admin');
}

// Log in
if ($input->post('action') === 'admin_login') {

	$username = $input->post('username', $escape = true);
	$password = $input->post('password');
	Authentication::login($username, $password);
	notify('You logged in as admin', 'success');
	redirect('admin');

}

// Log out
if ($input->post('action') === 'admin_logout') {

	Authentication::logout();
	notify('You logged out.');
	redirect('admin');
	
}
