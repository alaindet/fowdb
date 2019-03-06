<?php

// VARIABLES
// $code
// $props

?>

<div class="row">
  <div class="col-xs-12">
    <h1><?=$code?></h1>
    <ul class="fd-list">
      <?php foreach($props as $label => $value): ?>
        <li>
          <strong><?=$label?></strong>
          <?=$value?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
