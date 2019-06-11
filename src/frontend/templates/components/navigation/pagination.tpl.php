<?php
/*
 * INPUT
 * 
 * VARIABLES
 * App\Base\Items\ItemsCollection links
 * int progressPercentage
 * string progressLabel
 * string css
 */
?>
<div class="fd-pagination mv-100<?=$this->css?>">

  <!-- Links -->
  <nav class="fd-pagination-links">
    
    <!-- Prev -->
    <?php if ($this->links->first()->isDisabled): ?>
      <span class="prev">
        &#10094;&nbsp;Prev
      </span>
    <?php else: ?>
      <a href="<?=$this->links->first()->url?>" class="prev">
        &#10094;&nbsp;Prev
      </a>
    <?php endif; ?>

    <?php foreach ($this->links as $link): ?>
      <?php if ($link->class !== "prev" && $link->class !== "next"): ?>

        <?php if ($link->class === 'missing'): ?>
          <span class="dots">...</span>
        <?php elseif ($link->class === 'current'): ?>
          <span class="current"><?=$link->page?></span>
        <?php else: ?>
          <a href="<?=$link->url?>" class="<?=$link->class?>">
            <?=$link->page?>
          </a>
        <?php endif; ?>

      <?php endif; ?>
    <?php endforeach; ?>

    <!-- Next -->
    <?php if ($this->links->last()->isDisabled): ?>
      <span class="next">
        Next&nbsp;&#10095;
      </span>
    <?php else: ?>
      <a href="<?=$this->links->last()->url?>" class="next">
        Next&nbsp;&#10095;
      </a>
    <?php endif; ?>

  </nav>

  <!-- Progress bar -->
  <div
    class="fd-pagination-progress"
    style="background-size: <?=$this->progressPercentage?>%;"
  >
    <?=$this->progressLabel?>
  </div>
 
</div>
