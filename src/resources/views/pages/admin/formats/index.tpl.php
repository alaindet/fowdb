<?php

// VARIABLES
// $items

?>
<div class="page-header">
  <h1>Formats</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Formats' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new format -->
  <div class="col-xs-12">
    <a
      href="<?=url('formats/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create a new format
    </a>
    <hr>
  </div>

  <!-- Formats list -->
  <div class="col-xs-12">
    <?=include_view('pages/admin/formats/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=component('top-anchor')?>
  </div>
  
</div>
