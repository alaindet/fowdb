<?php

// VARIABLES
// $previous
// $item

?>
<div class="page-header">
  <h1>
    Update card
    <small><?=$card['name']?> (<?=$card['code']?>)</small>
  </h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Cards' => fd_url('cards/manage'),
    '(&larr; Card page)' => fd_url('card/'.urlencode($card['code'])),
    'Update' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin --darker-headings">
    <div class="fd-box__content">
      <?=fd_include_template('pages/admin/cards/includes/form', [
        'card' => $card,
        'prev' => $previous ?? null
      ])?>
    </div>
  </div>

  <!-- Conventions -->
  <?=fd_include_template('pages/admin/cards/includes/conventions')?>

</div>
