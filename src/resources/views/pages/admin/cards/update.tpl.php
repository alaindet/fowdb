<?php

// VARIABLES
// $previous
// $card

?>
<div class="page-header">
  <h1>
    Update card
    <small><?=$card['name']?> (<?=$card['code']?>)</small>
  </h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Cards' => url('cards/manage'),
    '(&larr; Card page)' => url_old(
      'card',
      ['code' => urlencode($card['code'])]
    ),
    'Update' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin --darker-headings">
    <div class="fd-box__content">
      <?=include_view('pages/admin/cards/includes/form', [
        'action' => 'update',
        'card' => $card,
        'prev' => $previous ?? null
      ])?>
    </div>
  </div>

  <!-- Conventions -->
  <?=include_view('pages/admin/cards/includes/conventions')?>

</div>
