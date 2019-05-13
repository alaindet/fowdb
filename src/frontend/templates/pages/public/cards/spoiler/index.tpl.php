<?php

// VARIABLES
// $items

?>
<div class="row">

  <!-- Options -->
  <?=fd_include_template('pages/public/cards/search/includes/results/options', [
    'spoilers' => $items
  ])?>

  <!-- Results -->
  <div class="col-xs-12" id="the-results">
    <div class="fd-box --xs-less-padding">

      <!-- Header -->
      <div class="fd-box__title">
        <h2>
          <i class="fa fa-th-large"></i>
          Spoiler

          <!-- Show options -->
          <button
            type="button"
            class="btn btn-xs btn-link link-as-text js-hider js-options-toggle"
            data-target="#the-options"
            data-open-icon="fa-times"
            data-closed-icon="fa-plus"
          >
            <i class="fa fa-plus"></i>
            Options
          </button>

        </h2>
      </div>

      <!-- Content -->
      <div class="fd-box__content">
        <?php foreach ($items as $set):
          $targetId = "spoiler-{$set['code']}";
        ?>
          <!-- Spoiler set container -->
          <div
            id="<?=$set['code']?>"
            class="fd-box js-spoiler text-center"
            data-set-code="<?=$set['code']?>"
            data-set-count="<?=$set['count']?>"
          >
            <!-- Spoiler set header -->
            <div class="fd-box__title js-spoiler-header">
              
              <!-- Spoiler set name -->
              <h3
                class="js-hider pointer inline"
                data-target="#<?=$targetId?>"
              >
                <i class="fa fa-chevron-down"></i>
                <?="{$set['name']} ({$set['spoiled']} / {$set['count']})"?>
              </h3>
              
              <!-- Top anchor -->
              <a
                href="#top"
                class="btn btn-link"
              >
                Top
              </a>

              <!-- Share button -->
              <a
                class="btn btn-link"
                href="#<?=$set['code']?>"
                name="<?=$set['code']?>"
              >
                Share
              </a>
              
            </div>

            <!-- Spoiler set body -->
            <div
              class="fd-box__content fd-grid-items js-spoiler-body"
              id="<?=$targetId?>"
            ><!--
              <?php if (!empty($set['cards'])): ?>
                <?php foreach ($set['cards'] as $item):
                  $link = fd_url('card/'.urlencode($item['code']));
                ?>
                  --><div
                    class="fd-card-item fd-grid fd-grid-3"
                    data-id="<?=$item['id']?>"
                    data-number="<?=$item['num']?>"
                  >
                    <a href="<?=$link?>" target="_self">
                      <img
                        src="<?=fd_asset($item['thumb_path'])?>"
                        alt="<?=$item['name']?>"
                      >
                    </a>
                  </div><!--
                <?php endforeach; ?>
              <?php endif; ?>
            --></div>

          </div>
        <?php endforeach; ?>
        <?=fd_component('top-anchor')?>
      </div><!-- /.fd-box__content -->
    </div><!-- /.fd-box -->
  </div><!-- /.col-xs-12 -->
</div><!-- /.row -->
