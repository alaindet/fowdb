<?php
  $ban = new \App\Models\Ban;
  $items = $ban->groupByFormat()->fetch()->getItems();
  $items = \App\Views\Ban\Ban::display($items);
  $totalCount = $ban->getTotalCount();
?>

<div class="page-header">
  <h1>Banned and limited cards (<?=$totalCount?>)</h1>
</div>

<div class="row">
  <?php foreach ($items as $format => &$cards): ?>
    <div class="col-xs-12">

      <div class="page-header">
        <h2>
          <a
            name="<?=$cards[0]['format_code']?>"
            href="#<?=$cards[0]['format_code']?>"
            class="no-style link-internal"
          >
            <?=$format?>
          </a>
          (<?=count($cards)?>)
        </h2>
      </div>

      <div class="fd-grid-items">

        <?php foreach ($cards as &$card): ?>
          <div class="fd-grid-2 fd-grid-sm-3 fd-grid-md-4 p-25">

            <a href="<?=$card['link']?>">
              <img
                src="<?=$card['image']?>"
                alt="<?=$card['name']?>"
              />
            </a>

            <ul class="p-50">
              <li><a href="<?=$card['link']?>"><?=$card['name']?></a></li>
              <li><em><?=$card['code']?></em></li>

              <?php if (isset($card['deck'])): ?>
                <li>
                  <span class="label label-danger"><?=$card['deck']?></span>
                </li>
              <?php endif; ?>

              <?php if ($card['copies'] > 0): ?>
                <li>
                  Limited in
                  <span class="label label-danger"><?=$card['copies']?></span>
                  cop<?=$card['copies']>1?'ies':'y'?>
                </li>
              <?php endif; ?>

            </ul>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  <?php endforeach; ?>
</div>
