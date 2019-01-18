<?php

// VARIABLES
// $items
// $pagination

?>
<div class="page-header">
  <h1>Sets</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Sets' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new set -->
  <div class="col-xs-12">
    <a
      href="<?=url('sets/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create a new set
    </a>
    <hr>
  </div>

  <!-- Pagination (top) -->
  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks = component('pagination', [
        'pagination' => $pagination,
      ])?>
    </div>
  <?php endif; ?>

  <!-- Sets list -->
  <div class="col-xs-12">
    <?=include_view('pages/admin/sets/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=component('top-anchor')?>
  </div>

  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks?>
    </div>
  <?php endif; ?>
  
</div>
