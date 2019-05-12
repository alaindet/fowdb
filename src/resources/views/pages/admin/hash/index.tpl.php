<?php

// VARIABLES
// $breadcrumbs
// $input (optional)
// $output (optional)

?>
<div class="page-header">
  <h1>Hash a string <small>(BCRYPT)</small></h1>
  <?=fd_component('breadcrumb', $breadcrumbs)?>
</div>

<form
  action="<?=url('hash')?>"
  method="post"
  class="form form-inline"
>
  <?=fd_csrf_token()?>

  <input
    type="text"
    name="to-hash"
    autofocus="<?=isset($output) ? 'false' : 'true'?>"
    class="form-control input-lg"
  />

  <button
    type="submit"
    class="btn btn-lg fd-btn-primary"
  >
    Hash string
  </button>

</form>

<?php if (isset($input) && isset($output)): ?>

  <h2>Input string</h2>
  <div class="fd-box p-100 --darker font-110">
    <?=$input?>
  </div>

  <h2>Output (hashed) string</h2>
  <div class="fd-box p-100 --darker font-110">
    <?=$output?>
  </div>

  <hr>

<?php endif; ?>
