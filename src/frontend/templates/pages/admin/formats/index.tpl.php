<?php

// VARIABLES
// $items

?>
<div class="page-header">
  <h1>Formats</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Formats' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new format -->
  <div class="col-xs-12">
    <a
      href="<?=fd_url('formats/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create a new format
    </a>
    <hr>
  </div>

  <!-- Formats list -->
  <div class="col-xs-12">
    <?=fd_include_view('pages/admin/formats/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=fd_component('top-anchor')?>
  </div>
  
</div>
