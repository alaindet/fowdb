<?php

// VARIABLES
// name
// value
// label
// css (optional)
//   container
//   button

$value = $value ?? 1;

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
  <label class="btn<?=$buttonCss?>">
    <input
      type="checkbox"
      name="<?=$name?>"
      value="<?=$value?>"
    >
    <span class="pointer"><?=$label?></span>
  </label>
</div>
