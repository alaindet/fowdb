<?php

// VARIABLES
// $previous

?>
<div class="page-header">
  <h1>Create new comprehensive rules</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Comprehensive Rules' => url('cr/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <?=fd_include_view('pages/admin/cr/includes/form', [
      'item' => null,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
