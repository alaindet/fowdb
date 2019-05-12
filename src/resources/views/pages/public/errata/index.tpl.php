<?php

// VARIABLES
// $items
// $pagination

?>
<div class="page-header">
	<h1>Errata</h1>
</div>

<div class="row">
	<div class="col-xs-12">

    <?php if (empty($items)): // No items to show ?>

      <div class="col-xs-12">
        <span class="font-120">No items to show</span>
      </div>

      <!-- Close everything before quitting -->
      </div>
      </div>
      <?php return; ?>

    <?php endif; ?>

    <!-- Pagination (top) -->
    <div class="col-xs-12 col-sm-6">
      <?=$pagelinks = fd_component('pagination', [
        'pagination' => $pagination,
        'no-label' => true,
      ])?>
    </div>

		<!-- Items -->
    <div class="col-xs-12">
      <div class="list-group fd-list-group">
        <?php foreach ($items as $item):
          $link = fd_url('card/'.urlencode($item['card_code']));
        ?>
          <div class="list-group-item">
            <h4 class="list-group-item-heading">
              <em><?=$item['ruling_date']?></em>
              <a href="<?=$link?>">
                <?="{$item['card_name']} ({$item['card_code']})"?> 
              </a>
            </h4>
            <p class="list-group-item-text">
              <?=fd_render($item['ruling_text'])?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Pagination (bottom) -->
    <?php if ($pagination['has-pagination']): ?>
      <div class="col-xs-12 col-sm-6">
        <?=$pagelinks?>
      </div>
    <?php endif; ?>

    <!-- Top anchor -->
    <div class="col-xs-12">
      <?=fd_component('top-anchor')?>
    </div>

	</div>
</div>
