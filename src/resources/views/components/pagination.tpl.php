<?php

// VARIABLES
// $css
// $links
// $progress_label
// $progress_percentage

$cssClasses = isset($css) ? ' '.implode(' ', $css) : '';
$count = count($links);

?>
<div class="fd-pagination mv-100<?=$cssClasses?>">

  <!-- Links -->
  <nav class="fd-pagination-links">
    
    <!-- Prev -->
    <?php if ($links[0]['disable']): ?>
      <span class="prev">
        &#10094;&nbsp;Prev
      </span>
    <?php else: ?>
      <a href="<?=$links[0]['link']?>" class="prev">
        &#10094;&nbsp;Prev
      </a>
    <?php endif; ?>

    <?php for ($i = 1, $len = $count - 1; $i < $len; $i++):
      $link = &$links[$i];
    ?>
      <?php if ($link['class'] === 'missing'): ?>
        <span class="dots">...</span>
      <?php elseif ($link['class'] === 'current'): ?>
        <span class="current"><?=$link['page']?></span>
      <?php else: ?>
        <a href="<?=$link['link']?>" class="<?=$link['class']?>">
          <?=$link['page']?>
        </a>
      <?php endif; ?>
    <?php endfor; ?>

    <!-- Next -->
    <?php if ($links[$count - 1]['disable']): ?>
      <span class="next">
        Next&nbsp;&#10095;
      </span>
    <?php else: ?>
      <a href="<?=$links[$count - 1]['link']?>" class="next">
        Next&nbsp;&#10095;
      </a>
    <?php endif; ?>

  </nav>

  <!-- Progress bar -->
  <div
    class="fd-pagination-progress"
    style="background-size: <?=$progress_percentage?>%;"
  >
    <?=$progress_label?>
  </div>
 
</div>
