<?php

// VARIABLES
// $items
// $pagination

// Process filters
$hasItems = !empty($items);
$hasFilters = !empty($filters);
if ($hasFilters && $hasItems) {
  \App\Views\Ruling::buildFiltersLabels($filters, $items[0]);
}

?>
<div class="page-header">
  <h1>Rulings</h1>
  <?php
    $links = [
      'Admin' => url('profile'),
      'Rulings' => '#'
    ];
    if ($hasFilters) {
      $links['Rulings'] = url('rulings/manage');
      $links['&larr; Clear filter'] = '#';
    }
    echo component('breadcrumb', $links);
  ?>

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

  <!-- Pagination (top) -->
  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks = component('pagination', $pagination)?>
    </div>
  <?php endif; ?>

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

  <!-- Pagination (bottom) -->
  <?php if ($pagination['has-pagination']): ?>
    <div class="col-xs-12">
      <?=$pagelinks?>
    </div>
  <?php endif; ?>
  
</div>
