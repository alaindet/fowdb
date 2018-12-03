<?php

// VARIABLES
// $card (optional): id, name, code, image_path
// $previous

?>
<div class="page-header">
  <h1>Create a new restriction</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Restrictions' => url('restrictions/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-9">
    <?=include_view('pages/admin/restrictions/includes/form', [
      'item' => null,
      'card' => $card ?? null,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
