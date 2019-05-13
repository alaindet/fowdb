<?php

// VARIABLES
// $clusters
// $nextAvailableId
// $previous

?>
<div class="page-header">
  <h1>Create a new format</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Formats' => fd_url('formats/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin">
    <div class="fd-box__content">
      <?=fd_include_template('pages/admin/formats/includes/form', [
        'item' => null,
        'nextAvailableId' => $nextAvailableId,
        'prev' => $previous ?? null,
        'clusters' => $clusters
      ])?>
    </div>
  </div>

  <!-- Rules -->
  <div class="fd-box --darker-headings --more-margin">
    <div class="fd-box__title">
      <h2>Rules</h2>
      <a name="rules"></a>
    </div>
    <div class="fd-box__content">
      <?=fd_include_template('pages/admin/formats/includes/form-rules')?>
    </div>
  </div>

</div>
