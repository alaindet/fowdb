<?php
// VARIABLES
// $links
?>
<ol class="breadcrumb">
  <?php foreach ($links as $label => $link): ?>
    <?php if ($link === '#'): ?>

      <li><?=$label?></li>
    
    <?php else: ?>

      <li><a href="<?=$link?>"><?=$label?></a></li>

    <?php endif; ?>
  <?php endforeach; ?>
</ol>
