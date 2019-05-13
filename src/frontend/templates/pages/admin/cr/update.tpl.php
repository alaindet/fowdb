<?php

// VARIABLES
// $previous
// $item

?>
<div class="page-header">
  <h1>Update comprehensive rules</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Comprehensive Rules' => fd_url('cr/manage'),
    'Update' => '#',
    'Show' => fd_url('cr/'.$item['version'])
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <?=fd_include_view('pages/admin/cr/includes/form', [
      'item' => $item,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
