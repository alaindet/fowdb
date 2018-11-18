<?php

// VARIABLES
// $previous

?>
<div class="page-header">
  <h1>Create a new card</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Cards' => url('cards/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin --darker-headings">
    <div class="fd-box__content">
      <?=include_view('pages/admin/cards/includes/form', [
        'action' => 'create',
        'card' => null,
        'prev' => $previous ?? null
      ])?>
    </div>
  </div>

  <!-- Conventions -->
  <?=include_view('pages/admin/cards/includes/conventions')?>

</div>
