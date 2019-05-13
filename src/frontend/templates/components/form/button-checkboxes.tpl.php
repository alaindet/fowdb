<?php

// VARIABLES
// $name (optional)
// $state[]
// $items[]
// $css (optional)
//   container
//   button

// NOTE -----------------------------------------------------------------------
// If input name is not given, each item has a different input name so that
// $items = [ input_name => label, ... ]
// instead of default behavior
// $items = [ value => label, ... ]
$multipleNames = !isset($name);
if (!$multipleNames) $name .= "[]";

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
    // ITEMS: value => label (default)
    foreach ($items as $key => $label):

      // Preserve 0 character for the label
      if ($label === 0) $label = '&#48;';

      ($multipleNames)
        ? [$inputName, $inputValue] = [$key, 1]
        : [$inputName, $inputValue] = [$name, $key];

      // State
      (in_array($key, $state))
        ? [$active, $checked] = [' active', ' checked']
        : [$active, $checked] = ['', ''];
  ?>
    <label class="btn<?=$buttonCss?><?=$active?>">
      <input
        type="checkbox"
        name="<?=$inputName?>"
        value="<?=$inputValue?>"
        <?=$checked?>
      >
      <span class="pointer"><?=$label?></span>
    </label>
  <?php endforeach; ?>
</div>
