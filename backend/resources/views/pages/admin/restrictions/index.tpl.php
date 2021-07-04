<?php

// VARIABLES
// $items
// $pagination
// $filters

// Process filters (turn IDs to labels)
// Trick: $items[0] will surely respect all those filters
$hasItems = !empty($items);
$hasFilters = !empty($filters);
if ($hasFilters && $hasItems) {
  \App\Views\PlayRestriction::buildFiltersLabels($filters, $items[0]);
}

?>
<div class="page-header">
  <h1>
    Restricted cards: banned and limited
    <small>(<?=$pagination['total']?>)</small>
  </h1>
  <?php
    $links = [
      'Admin' => url('profile'),
      'Restrictions' => '#'
    ];
    if ($hasFilters) {
      $links['Restrictions'] = url('restrictions/manage');
      $links['&larr; Clear filter'] = '#';
    }
    echo component('breadcrumb', $links);
  ?>
</div>

<div class="row">

  <!-- Create a new restriction -->
  <div class="col-xs-12">
    <a
      href="<?=url('restrictions/create')?>"
      class="btn btn-lg fd-btn-default fd-btn-success-hover"
    >
      <i class="fa fa-plus"></i>
      Create a new restriction
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

  <!-- Show set filters -->
  <?php if ($hasFilters): ?>
    <div class="col-xs-12 fd-box --info-background">
      <h2>
        Filters
        <a
          href="<?=url('restrictions/manage')?>"
          class="font-100 text-italic"
        >
          (Reset)
        </a>
      </h2>
      <ul class="fd-list">
        <?php foreach($filters as $name => $value): ?>
          <li>
            <strong><?=ucfirst($name)?></strong>: <?=$value?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
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
    <?=include_view('pages/admin/restrictions/includes/index-list', [
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
