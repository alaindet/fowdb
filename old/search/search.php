<?php
// VARIABLES
// $cards
// $filters
// $search
// $thereWereResults
?>
<div class="row">
  
  <?php
    require __DIR__ . '/search.html.php';

    if ($thereWereResults) {
      require __DIR__ . '/options.html.php';
      require __DIR__ . '/results.html.php';
    }
  ?>

</div>
