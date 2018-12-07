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

    <!-- Pagination (save and re-use it) -->
    <div class="col-xs-12">
      <?=$pagelinks = component('pagination', $pagination)?>
    </div>

    <!-- Progress bar -->
    <div class="col-xs-12">
      <?=component('progress-bar', [
        'from' => $pagination['lower-bound'],
        'to' => $pagination['upper-bound'],
        'total' => $pagination['total']
      ])?>
    </div>

		<!-- Items -->
    <div class="col-xs-12">
      <div class="list-group fd-list-group">
        <?php foreach ($items as $item):
          $link = url_old('card', [ 'code' => urlencode($item['card_code']) ]);
        ?>
          <div class="list-group-item">
            <h4 class="list-group-item-heading">
              <em><?=$item['ruling_date']?></em>
              <a href="<?=$link?>">
                <?="{$item['card_name']} ({$item['card_code']})"?> 
              </a>
            </h4>
            <p class="list-group-item-text">
              <?=render($item['ruling_text'])?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Top anchor -->
    <div class="col-xs-12">
      <?=component('top-anchor')?>
    </div>

    <!-- Pagination -->
    <div class="col-xs-12">
      <?=$pagelinks?>
    </div>

	</div>
</div>
