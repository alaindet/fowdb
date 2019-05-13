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
  <h1>Restricted cards: banned and limited</h1>
  <?php
    $links = [
      'Admin' => fd_url('profile'),
      'Restrictions' => '#'
    ];
    if ($hasFilters) {
      $links['Restrictions'] = fd_url('restrictions/manage');
      $links['&larr; Clear filter'] = '#';
    }
    echo fd_component('breadcrumb', $links);
  ?>
</div>

<div class="row">

  <!-- Create a new restriction -->
  <div class="col-xs-12">
    <a
      href="<?=fd_url('restrictions/create')?>"
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
          href="<?=fd_url('restrictions/manage')?>"
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
    <?=fd_include_view('pages/admin/restrictions/includes/index-list', [
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
