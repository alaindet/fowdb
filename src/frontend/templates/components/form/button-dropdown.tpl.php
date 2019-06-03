<?php
/*
 * REQUIRES
 * frontend/js/dependencies/form/button-dropdown.js
 *
 * INPUT
 * name string
 * items array
 * ?default [face=>, value=>] array
 * ?size (enum: xs, sm, md, lg) string
 * ?state [face=>, value=>] array
 * 
 * VARIABLES = INPUT
 */

// Specific to Bootstrap 3
$sizes = [
  'xs' => 'btn-xs',
  'sm' => 'btn-sm',
  'md' => '', // Default
  'lg' => 'btn-lg',
];

// Button size
(isset($this->size) && isset($sizes[$this->size]))
  ? $this->size = " {$sizes[$this->size]}"
  : $this->size = "";

  
// Default state
if (!isset($this->default)) {
  $nullable = true;
  $this->default = [
    "face" => "Select...",
    "value" => ""
  ];
} else {
  $nullable = false;
}

// State
(isset($this->state) && isset($this->items[$this->state]))
  ? [$face, $value] = [$this->items[$this->state], $this->state]
  : [$face, $value] = [$this->default["face"], $this->default["value"]];
?>
<div class="js-button-dropdown<?=$nullable ? " --nullable" : ""?>">

  <!-- Dropdown input -->
  <input
    type="hidden"
    name="<?=$this->name?>"
    class="js-button-dropdown-hidden"
    value="<?=$value?>"
    data-default="<?=$this->default["value"]?>"
  >

  <div class="btn-group">

    <!-- Dropdown face -->
    <button
      type="button"
      class="dropdown-toggle btn<?=$this->size?> fd-btn-default"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <span
        class="js-button-dropdown-face"
        data-default="<?=$this->default["face"]?>"
      >
        <?=$face?>
      </span>
      <span class="caret"></span>
    </button>

    <!-- Dropdown items -->
    <ul class="dropdown-menu">
      <?php foreach ($this->items as $value => &$face):
        // STICKY
        ($value === $this->state)
          ? [$active, $selected] = [" active", " selected"]
          : [$active, $selected] = ["", ""];
      ?>
        <li>
          <a
            class="js-button-dropdown-item pointer"
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
