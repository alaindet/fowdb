<?php

// VARIABLES
// $items

?>
<div class="row">

  <!-- Side options -->
  <?=include_view(
    'pages/public/cards/search/includes/options',
    [
      'isSpoiler' => true,
      'spoilers' => $items // TO DO
    ]
  )?>

  <!-- Results -->
  <div class="col-xs-12" id="search-results">
    <div class="panel panel-default">

      <!-- Header -->
      <div class="panel-heading">
        <h3>
          <i class="fa fa-th-large"></i>
          Spoiler
          <button
            type="button"
            class="btn btn-link js-hider js-panel-toggle"
            data-target="#hide-options"
            data-open-icon="fa-times"
            data-closed-icon="fa-plus"
            id="js-panel-toggle-options"
          >
            <i class="fa fa-plus"></i>
            Options
          </button>
        </h3>
      </div>

      <!-- Content -->
      <div class="panel-body cards-container">
        <?php foreach ($items as $set) : ?>

          <!-- Spoiler set container -->
          <div
            id="<?=$set['code']?>"
            class="spoiler"
            data-set-code="<?=$set['code']?>"
            data-set-count="<?=$set['count']?>"
          >
            <!-- Spoiler set header -->
            <div class="spoiler-header text-center">
              
              <h3
                class="js-hider pointer inline"
                data-target="#hide-spoiler-<?=$set['code']?>"
              >
                <i class="fa fa-chevron-down"></i>
                <?="{$set['name']} ({$set['spoiled']} / {$set['count']})"?>
              </h3>
              
              <!-- Top anchor -->
              <a href="#top" class="btn btn-link">Top</a>

              <!-- Share button -->
              <a
                class="btn btn-link"
                href="#<?=$set['code']?>"
                name="<?=$set['code']?>"
              >
                Share
              </a>
              
            </div>

            <p></p>

            <!-- Spoiler set body -->
            <div class="spoiler-body" id="hide-spoiler-<?=$set['code']?>"><!--
              <?php if (!empty($set['cards'])): ?>
                <?php foreach ($set['cards'] as $card): ?>
                    --><div
                      class="fdb-card fdb-card-3"
                      data-number="<?=$card['num']?>"
                      data-code="<?=$card['code']?>"
                      data-id="<?=$card['id']?>"
                    >
                      <a
                        href="<?=url_old('card', [
                          'code' => urlencode($card['code'])
                        ])?>"
                        target="_self"
                      >
                        <img
                          src="<?=$card['thumb_path']?>"
                          alt="<?=$card['name']?>"
                        >
                      </a>
                    </div><!--
                <?php endforeach; ?>
              <?php endif; ?>
            --></div>

          </div>
        <?php endforeach; ?>
        <?=component('top-anchor')?>
      </div>

    </div>
  </div>

</div>
