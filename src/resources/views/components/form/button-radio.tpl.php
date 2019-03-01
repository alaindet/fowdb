<?php

// VARIABLES
// $name
// $state
// $items
// $css (optional)
//   container
//   button

// CSS ------------------------------------------------------------------------
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
  <?php
    foreach ($items as $value => $label):

      // Preserve 0 character for the label
      if ($label === 0) $label = '&#48;';

      // State
      ($value === $state)
        ? [$active, $checked] = [' active', ' checked']
        : [$active, $checked] = ['', ''];
  ?>
    <label class="btn<?=$buttonCss?><?=$active?>">
      <input
        type="radio"
        name="<?=$name?>"
        value="<?=$value?>"
        <?=$checked?>
      >
      <span class="pointer"><?=$label?></span>
    </label>
  <?php endforeach; ?>
</div>
