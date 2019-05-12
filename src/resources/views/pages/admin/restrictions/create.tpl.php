<?php

// VARIABLES
// $card (optional): id, name, code, image_path
// $previous

?>
<div class="page-header">
  <h1>Create a new restriction</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Restrictions' => url('restrictions/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <?=fd_include_view('pages/admin/restrictions/includes/form', [
      'item' => null,
      'card' => $card ?? null,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
