<?php

use \App\Exceptions\CsrfTokenException;
use \App\Http\Request\Input;
use \App\Legacy\Authorization;
use \App\Services\CsrfToken;

// Check authorization and bounce back intruders
auth()->allow([Authorization::ROLE_ADMIN]);

// Get input instance
$input = Input::getInstance();

$breadcrumbs = [
  'Admin' => url('admin'),
  'Hash' => '#'
];

// Data passed
if ($input->has('toHash')) {

	// ERROR: Missing or wrong anti-CSRF token
	if (!CsrfToken::check($input->post(CsrfToken::NAME))) {
		throw new CsrfTokenException;
	}

	// Hash the input
  $hash = password_hash($input->post('toHAsh'), PASSWORD_BCRYPT);
  
  $breadcrumbs = [
    'Admin' => url('admin'),
    'Hash' => url_old('admin/hash'),
    'Hashed' => '#'
  ];

}
?>

<div class="page-header">
  <h1>Hashing tool</h1>
  <?=component('breadcrumb', $breadcrumbs)?>
</div>

<?php if (isset($hash)): // Results ?>

  <p>
    <h2>Original string</h2>
    <?=log_html($input->post('toHash'))?>
  </p>

  <p>
    <h2>Hashed string</h2>
    <?=log_html($hash)?>
  </p>

  <hr>

<?php endif; ?>

<form
  action=""
  method="post"
  class="form form-inline"
>

  <?=csrf_token()?>

  <input
    type="text"
    name="toHash"
    <?=isset($hash) ? '' : 'autofocus="true"'?>
    class="form-control input-lg"
    value="<?=$input->post('toHash')?>"
  />

  <button
    type="submit"
    class="btn btn-lg btn-primary"
  >
    Hash this string
  </button>

</form>
