<?php

// VARIABLES
// $items
// $pagination

?>
<div class="page-header">
  <h1>Rulings</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Rulings' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new ruling -->
  <div class="col-xs-12">
    <a
      href="<?=url('rulings/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create a new ruling
    </a>
    <hr>
  </div>

  <!-- Pagination (save and re-use it) -->
  <div class="col-xs-12">
    <?=$pagelinks = component('pagination', $pagination)?>
  </div>

  <!-- Progress bar -->
  <div class="col-xs-12">
    <?=component('progress-bar', [
      'from' => $pagination['lower-bound'],
      'to' => $pagination['upper-bound'],
      'total' => $pagination['total']
    ])?>
  </div>

  <!-- Rulings -->
  <div class="col-xs-12">
    <?=include_view('pages/admin/rulings/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=component('top-anchor')?>
  </div>

  <!-- Pagination -->
  <div class="col-xs-12">
    <?=$pagelinks?>
  </div>
  
</div>
