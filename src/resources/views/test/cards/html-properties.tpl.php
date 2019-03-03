<?php

// VARIABLES
// $props

?>

<div class="row">
  <div class="col-xs-12">
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
