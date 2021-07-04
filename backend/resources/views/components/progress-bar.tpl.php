<?php
// VARIABLES
// $from
// $to
// $total
$percent = number_format(100 * $to / $total, 0, '.', '');
?>
<div class="progress fd-progress">

  <!-- Label -->
  <div class="fd-progress-label">
    <?=$from?> - <?=$to?> of <strong><?=$total?></strong>
  </div>

  <!-- Bar -->
  <div
    class="progress-bar fd-progress-bar"
    role="progressbar"
    aria-valuenow="<?=$to?>"
    aria-valuemin="1"
    aria-valuemax="<?=$total?>"
    style="width: <?=$percent?>%;"
  >
  </div>

</div>
