<?php

// VARIABLES
// $name
// $state
// $value
// $label
// $css[] (optional)
//   container
//   button

$value = $value ?? 1;

// STATE ----------------------------------------------------------------------
($state)
  ? [$active, $checked] = [' active', ' checked']
  : [$active, $checked] = ['', ''];

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
  <label class="btn<?=$buttonCss?><?=$active?>">
    <input
      type="checkbox"
      name="<?=$name?>"
      value="<?=$value?>"
      <?=$checked?>
    >
    <span class="pointer"><?=$label?></span>
  </label>
</div>
