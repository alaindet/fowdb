<?php

// VARIABLES
// results
// filters
// pagination

// INPUTS
// atk
// atk-operator
// attribute[]
// attribute-multi
// attribute-single
// attribute-selected
// back-side[]
// def
// def-operator
// divinity[]
// partial-match
// exclude[]
// format
// illust
// in-fields[]
// q
// quickcast
// race
// rarity[]
// set[]?
// sort
// sort-dir
// cost[]
// cost-x
// type[]
// type-selected

$areResults = !empty($results);
$spoilerIds = fd_lookup("spoilers.ids");
$format = $filters["format"] ?? null;
$bannedList = fd_lookup("banned.{$format}") ?? [];

?>
<div class="row">

  <!-- Form -->
  <div class="col-xs-12">
    <?=fd_include_view("pages/public/cards/search/includes/form/form", [
      "areResults" => true,
      "state" => $filters,
    ])?>
  </div>

  <!-- Options -->
  <?=fd_include_view("pages/public/cards/search/includes/results/options")?>
  
  <div class="col-xs-12" id="the-results">
    <div class="fd-box --xs-less-padding">

      <!-- Title -->
      <div class="fd-box__title">
        <h2>
          Results
          <button
            type="button"
            class="
              btn btn-xs btn-link link-as-text
              js-hider js-options-toggle
            "
            data-target="#the-options"
            data-open-icon="fa-times"
            data-closed-icon="fa-plus"
          >
            <i class="fa fa-plus"></i>
            Options
          </button>
        </h2>
      </div>

      <div class="fd-box__content">

        <!-- Pagination (top) -->
        <?php if ($pagination["has-pagination"]): ?>
          <?=$pagelinks = fd_component("pagination", [
            "pagination" => $pagination,
            // "no-label" => true
          ])?>
        <?php endif; ?>

        <!-- Results -->
        <div class="fd-grid-items js-fd-card-items">
          <?php foreach ($results as $item):

            $link = fd_url("card/".urlencode($item["code"]));

            $custom = "";

            if (in_array($item["sets_id"], $spoilerIds)) {
              $custom = " fd-card-item--spoiler";
            }

            if (in_array(intval($item["id"]), $bannedList)) {
              $custom = " fd-card-item--banned";
            }

          ?>
            <div class="fd-card-item fd-grid fd-grid-3<?=$custom?>">
              <a href="<?=$link?>">
                <img
                  src="<?=fd_asset($item["thumb_path"])?>"
                  alt="<?=$item["name"]?>"
                >
              </a>
            </div>
          <?php endforeach;?>
        </div>

        <!-- Pagination (bottom) -->
        <?php if ($pagination["has-pagination"]): ?>
          <div class="mv-50">
            <?=$pagelinks?>
          </div>
        <?php endif; ?>

        <!-- Top anchor -->
        <div class="text-center"><?=fd_component("top-anchor")?></div>

      </div><!-- /.fd-box__content -->
    </div><!-- /.fd-box -->
  </div><!-- /.col-xs-12 -->
</div><!-- /.row -->
