<?php

// VARIABLES
// $areResults
// $state

// STATE ----------------------------------------------------------------------
$state = [
  "partial-match" => isset($state["partial-match"]),
  "in-fields" => $state["in-fields"] ?? [],
  "exclude" => $state["exclude"] ?? [],
  "sort" => $state["sort"] ?? false,
  "sort-dir" => isset($state["sort-dir"]),
  "format" => $state["format"] ?? fd_lookup("formats.default"),
  "type" => $state["type"] ?? [],
  "type-all" => isset($state["type-all"]),
  "attribute" => $state["attribute"] ?? [],
  "attribute-single" => isset($state["attribute-single"]),
  "attribute-multi" => isset($state["attribute-multi"]),
  "attribute-only" => isset($state["attribute-only"]),
  "attribute-all" => isset($state["attribute-all"]),
  "cost" => $state["cost"] ?? [],
  "cost-x" => isset($state["cost-x"]),
  "set" => $state["set"] ?? null,
  "cluster" => $state["cluster"] ?? null,
  "rarity" => $state["rarity"] ?? [],
  "race" => $state["race"] ?? null,
  "illust" => $state["illust"] ?? null,
  "atk" => $state["atk"] ?? null,
  "def" => $state["def"] ?? null,
  "atk-operator" => $state["atk-operator"] ?? "equals",
  "def-operator" => $state["def-operator"] ?? "equals",
  "back-side" => $state["back-side"] ?? [],
  "divinity" => $state["divinity"] ?? [],
  "quickcast" => isset($state["quickcast"]),
];

?>
<div
  id="the-filters"
  class="<?=$areResults ? " hidden" : ""?>"
>
  <div class="fd-box --xs-less-padding">

    <!-- Title -->
    <div class="fd-box__title">
      <h2>

        Filters

        <!-- Close button -->
        <?php if ($areResults): ?>
          <button
            type="button"
            class="btn btn-xs btn-link link-as-text js-hider"
            data-target="#the-filters"
            data-open-icon="fa-times"
            data-closed-icon="fa-plus"
          >
            <i class="fa fa-times"></i>
            Close
          </button>
        <?php endif; ?>

        <!-- Reset button -->
        <button
          type="button"
          class="btn btn-link btn-xs link-as-text js-search-reset"
        >
          <i class="fa fa-undo"></i>
          Reset
        </button>

      </h2>
    </div>

    <!-- Content -->
    <div class="fd-box__content">
      <div class="row">

        <!-- Left filters -->
        <div class="col-sm-12 col-md-6 ph-0 sm-ph-100">
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/options",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/format",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/type",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/attribute",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/cost",
            ["state" => $state]
          )?>
        </div>

        <!-- Right filters -->
        <div class="col-sm-12 col-md-6 ph-0 sm-ph-100">
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/set",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/cluster",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/rarity",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/race",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/illustrator",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/atkdef",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/back-side",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/divinity",
            ["state" => $state]
          )?>
          <?=include_view(
            "pages/public/cards/search/includes/form/filters/flags",
            ["state" => $state]
          )?>
        </div>

      </div>
    </div>

    <!-- Footer -->
    <div class="fd-box__footer text-right">

      <button
        type="button"
        class="btn btn-lg btn-link link-as-text js-search-reset"
      >
        <i class="fa fa-undo"></i>
        Reset
      </button>

      <button type="submit" class="btn btn-lg fd-btn-primary">
        <i class="fa fa-search"></i>
        Search
      </button>

    </div>

  </div>
</div>
