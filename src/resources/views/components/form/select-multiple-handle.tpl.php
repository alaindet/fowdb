<?php

// REQUIRES
// /src/resources/assets/js/dependencies/form/select-multiple.js
// component: select-multiple-items

// VARIABLES
// $css (optional)
// $target
// $state (optional)

$css = isset($css) ? ' '.implode(' ', $css) : '';
$active = (isset($state) && $state) ? ' active' : '';

?>
<button
  type="button"
  class="js-select-multiple btn<?=$css?><?=$active?>"
  data-target="<?=$target?>"
>
  Multiple
</button>
