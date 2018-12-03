<?php

// VARIABLES
// $card (optional): id, name, code, image_path
// $previous

?>
<div class="page-header">
  <h1>
    Update restriction
    <small><?=$item['card_name']?> (<?=$item['card_code']?>)</small>
  </h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Restrictions' => url('restrictions/manage'),
    '(&larr; Card page)' => url_old(
      'card',
      ['code' => urlencode($item['card_code'])]
    ),
    'Update' => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-9">
    <?=include_view('pages/admin/restrictions/includes/form', [
      'item' => $item,
      'card' => $card ?? null,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
