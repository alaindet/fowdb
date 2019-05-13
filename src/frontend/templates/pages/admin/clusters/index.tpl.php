<?php

// VARIABLES
// $items

?>
<div class="page-header">
  <h1>Clusters</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Clusters' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new ruling -->
  <div class="col-xs-12">
    <button
      type="button"
      class="btn btn-lg fd-btn-default fd-btn-success-hover js-open-modal"
      data-fd-action="create"
    >
      <i class="fa fa-plus"></i>
      Create a new cluster
    </button>
    <hr>
  </div>

  <!-- Items -->
  <div class="col-xs-12">
    <?=fd_include_template(
      'pages/admin/clusters/includes/index-list',
      [ 'items' => $items ]
    )?>
  </div>

  <!-- Modal -->
  <?=fd_include_template('pages/admin/clusters/includes/modal')?>
  
</div>
