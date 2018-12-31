<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/input-dropdown.js

// VARIABLES
// $default [face, value]
// $items
// $name
// $size (optional)
// $state (optional) [face, value]

// Specific to Bootstrap 3
$sizes = [
  'xs' => 'btn-xs',
  'sm' => 'btn-sm',
  'md' => '', // Default
  'lg' => 'btn-lg',
];

$size = isset($size) && isset($sizes[$size]) ? ' '.$sizes[$size] : '';

?>
<div class="js-input-dropdown">
  <div class="btn-group">

    <!-- Dropdown input -->
    <input
      type="hidden"
      name="<?=$name?>"
      class="js-input-dropdown-hidden"
      value="<?=isset($state) ? $state['value'] : $default['value']?>"
      data-default="<?=$default['value']?>"
    >

    <!-- Dropdown face -->
    <button
      type="button"
      class="dropdown-toggle btn<?=$size?> fd-btn-default"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <span
        class="js-input-dropdown-face"
        data-default="<?=$default['face']?>"
      >
        <?=isset($state) ? $state['face'] : $default['face'] ?>
      </span>
      <span class="caret"></span>
    </button>

    <!-- Dropdown items -->
    <ul class="dropdown-menu">
      <?php foreach ($items as $value => $face): ?>
        <li>
          <a
            class="js-input-dropdown-item pointer"
            data-face="<?=$face?>"
            data-value="<?=$value?>"
          >
            <?=$face?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

  </div>
</div>
