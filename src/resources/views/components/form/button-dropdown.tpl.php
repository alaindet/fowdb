<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/button-dropdown.js

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

// State
[$face, $value] = [$default['face'], $default['value']];
if (isset($state) && isset($items[$state])) {
  [$face, $value] = [$items[$state], $state];
}

?>
<div class="js-input-dropdown">

  <!-- Dropdown input -->
  <input
    type="hidden"
    name="<?=$name?>"
    class="js-input-dropdown-hidden"
    value="<?=$value?>"
    data-default="<?=$default['value']?>"
  >

  <div class="btn-group">

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
        <?=$face?>
      </span>
      <span class="caret"></span>
    </button>

    <!-- Dropdown items -->
    <ul class="dropdown-menu">
      <?php foreach ($items as $value => $face):
        [$active, $selected] = ['', ''];
        if ($value === $state) {
          [$active, $selected] = [' active', ' selected'];
        }
      ?>
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
