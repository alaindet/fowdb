<?php

// VARIABLES
// name (optional)
// items
// css (optional)
//   container
//   button

// NOTE
// If input name is not given, each item has a different input name so that
// $items = [ input_name => label, ... ]
// instead of default behavior
// $items = [ value => label, ... ]

$containerCss = '';
$buttonCss = '';

if (isset($css)) {
  if (isset($css['container'])) {
    $containerCss = ' ' . implode(' ', $css['container']);
  }
  if (isset($css['button'])) {
    $buttonCss = ' ' . implode(' ', $css['button']);
  }
}

?>
<div class="btg-group<?=$containerCss?>" data-toggle="buttons">
  <?php if (isset($name)): ?>

    <?php
      // ITEMS: value => label
      foreach ($items as $value => $label):
    ?>
      <label class="btn<?=$buttonCss?>">
        <input
          type="checkbox"
          name="<?=$name?>[]"
          value="<?=$value?>"
        >
        <span class="pointer"><?=$label?></span>
      </label>
    <?php endforeach; ?>

  <?php else: ?>

    <?php
      // ITEMS: input name => label
      foreach ($items as $inputName => $label):
    ?>
      <label class="btn<?=$buttonCss?>">
        <input
          type="checkbox"
          name="<?=$inputName?>"
          value="1"
        >
        <span class="pointer"><?=$label?></span>
      </label>
    <?php endforeach; ?>

  <?php endif; ?>
</div>
