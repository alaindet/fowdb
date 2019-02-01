<?php

// VARIABLES
// areResults
// state

// INPUTS
// q

$value = $state['q'] ?? '';

?>
<div class="input-group input-group-lg">

  <!-- Syntax help button -->
  <span class="input-group-btn">
    <a
      href="<?=url('cards/search/help')?>"
      class="btn btn-lg fd-btn-default"
    >
      <i class="fa fa-info"></i>
      <span class="hidden-xs">Help</span>
    </a>
  </span>

  <!-- Filters toggle -->
  <?php if ($areResults): ?>
    <span
      class="input-group-btn"
      data-toggle="buttons"
      role="group"
    >
      <label
        class="btn btn-lg fd-btn-default js-hider"
        style="border-radius:0;border-right-width:0;"
        data-target="#the-filters"
        data-open-icon="fa-times"
        data-closed-icon="fa-plus"
      >
        <input type="checkbox">
        <i class="fa fa-filter"></i>
        <span class="hidden-xs">Filters</span>
      </label>
    </span>
  <?php endif; ?>
    
  <!-- Searchbar input -->
  <input
    type="text"
    class="form-control input-lg"
    style="border-left: 0;"
    placeholder="Search for cards..."
    name="q"
    value="<?=$value?>"
    <?=$areResults ? '' : 'autofocus'?>
  >
    
  <!-- Search button -->
  <span class="input-group-btn">
    <button type="submit" class="btn btn-lg fd-btn-primary">
      <i class="fa fa-search"></i>
      <span class="hidden-xs">Search</span>
    </button>
  </span>

</div>
