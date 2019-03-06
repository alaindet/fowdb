<?php

// VARIABLES
// $clusters
// $nextAvailableId
// $previous

?>
<div class="page-header">
  <h1>Create a new format</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Formats' => url('formats/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Form -->
  <div class="fd-box --more-margin">
    <div class="fd-box__content">
      <?=include_view('pages/admin/formats/includes/form', [
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
      <ul class="fd-list --spaced">
        <li>
          Setting a format as <strong>default</strong> automatically removes any other format from being default
        </li>
        <li>
          Format <strong>names</strong> for single cluster formats must include " Cluster". Ex.: "Grimm Cluster"
        </li>
        <li>
          Format <strong>codes</strong> for single cluster formats must follow the pattern <kbd>c-xxx</kbd> where <kbd>xxx</kbd> is a 3-letter code representing the cluster. Ex.: "c-gri"
        </li>
      </ul>
    </div>
  </div>

</div>
