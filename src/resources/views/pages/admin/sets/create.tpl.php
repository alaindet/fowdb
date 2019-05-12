<?php

// VARIABLES
// $previous
// $clusters

?>
<div class="page-header">
  <h1>Create a new set</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Sets' => url('sets/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin">
    <div class="fd-box__content">
      <?=include_view('pages/admin/sets/includes/form', [
        'item' => null,
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
      <ul class="fd-list --spaced">
        <li>
          Set ID must be unique and should be progressive to maintain order of release. Check the last set's ID before creating a new set.
        </li>        
      </ul>
    </div>
  </div>

</div>
