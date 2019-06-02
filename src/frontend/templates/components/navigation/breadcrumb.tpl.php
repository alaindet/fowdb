<?php
/**
 * INPUT
 * [
 *   label => relative_url,
 *   ...,
 * ]
 * 
 * VARIABLES
 * links array
 */
?>
<ol class="breadcrumb">
  <?php foreach ($this->links as $label => $url): ?>
    <?php if ($url === '#'): ?>
      <li><?=$label?></li>
    <?php else: ?>
      <li><a href="<?=$url?>"><?=$label?></a></li>
    <?php endif; ?>
  <?php endforeach; ?>
</ol>
