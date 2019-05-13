<?php

// VARIABLES
// $previous

?>
<div class="page-header">
  <h1>Create a new card</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Cards' => fd_url('cards/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin">
    <div class="fd-box__content">
      <?=fd_include_view('pages/admin/cards/includes/form', [
        'card' => null,
        'prev' => $previous ?? null
      ])?>
    </div>
  </div>

  <!-- Conventions -->
  <?=fd_include_view('pages/admin/cards/includes/conventions')?>

</div>
