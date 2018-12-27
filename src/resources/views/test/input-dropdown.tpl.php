<?php

$items = [
  'aaa' => 1,
  'bbb' => 2,
  'ccc' => 3,
  'ddd' => 4,
];

?>
<form action="<?=url('test/input-dropdown')?>" method="get">
  <div class="input-group js-input-dropdown">

    <!-- Hidden input -->
    <input
      type="hidden"
      name="THE_NAME"
      class="js-input-dropdown-hidden"
      value="THE_VALUE"
    >

    <div class="input-group-btn">
    
      <!-- Dropdown face -->
      <button
        type="button"
        class="btn btn-default dropdown-toggle"
        data-toggle="dropdown"
      >
        <span class="js-input-dropdown-face">
          THE_FACE
        </span>
      </button>

      <!-- Dropdown items -->
      <ul class="dropdown-menu">
        <?php foreach ($items as $face => $value): ?>
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

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>
</form>
