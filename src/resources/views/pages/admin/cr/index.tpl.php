<?php

// VARIABLES
// $items
// $pagination

$hasItems = !empty($items);

?>
<div class="page-header">
  <h1>
    Comprehensive Rules
    <small>(<?=$pagination['total']?>)</small>
  </h1>
  <?=component('breadcrumb', [
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

  <!-- Pagination (save and re-use it) -->
  <div class="col-xs-12">
    <?=$pageLinks = component('pagination', $pagination)?>
  </div>

  <!-- Progress bar -->
  <div class="col-xs-12">
    <?=component('progress-bar', [
      'from' => $pagination['lower-bound'],
      'to' => $pagination['upper-bound'],
      'total' => $pagination['total']
    ])?>
  </div>

  <!-- Items list -->
  <div class="col-xs-12">
    <?=include_view('pages/admin/cr/includes/index-list', [
      'items' => $items
    ])?>
  </div>

  <!-- Top anchor -->
  <div class="col-xs-12">
    <?=component('top-anchor')?>
  </div>

  <!-- Pagination -->
  <div class="col-xs-12">
    <?=$pageLinks?>
  </div>
  
</div>
