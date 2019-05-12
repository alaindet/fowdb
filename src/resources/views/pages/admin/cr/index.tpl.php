<?php

// VARIABLES
// $items
// $pagination

$hasItems = !empty($items);

?>
<div class="page-header">
  <h1>Comprehensive Rules</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Comprehensive Rules' => '#'
  ])?>
</div>

<div class="row">

  <!-- Create a new resource -->
  <div class="col-xs-12">
    <a
      href="<?=url('cr/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create new comprehensive rules
    </a>
    <hr>
  </div>

  <!-- ERROR: No items to show -->
  <?php if (!$hasItems): ?>

    <div class="col-xs-12">
      <span class="font-120">No items to show</span>
    </div>

    </div><!-- Close .row before quitting -->
    <?php return; // Quit ?>

  <?php endif; ?>

  <!-- Pagination (top) -->
  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks = fd_component('pagination', [
        'pagination' => $pagination,
      ])?>
    </div>
  <?php endif; ?>

  <!-- Items list -->
  <div class="col-xs-12">
    <?=fd_include_view('pages/admin/cr/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=fd_component('top-anchor')?>
  </div>

  <!-- Pagination (bottom) -->
  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks?>
    </div>
  <?php endif; ?>
  
</div>
