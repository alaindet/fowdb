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
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Restrictions' => url('restrictions/manage'),
    '(&larr; Card page)' => url('card/'.urlencode($item['card_code'])),
    'Update' => '#'
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <?=include_view('pages/admin/restrictions/includes/form', [
      'item' => $item,
      'card' => $card ?? null,
      'prev' => $previous ?? null
    ])?>
  </div>
</div>
