<?php

// VARIABLES
// $state[]
//   partial-match
//   in-fields
//   sort
//   sort-dir

// INPUTS
// partial-match
// in-fields[]
// exclude[]
// sort
// sort-dir

?>
<h3 class="font-110">Options</h3>

<!-- PARTIAL MATCH ====================================================== -->
<div class="row sm-ph-100">
  <span>Allow partial matches of search terms...</span>
  <?=component("form/button-checkbox", [
    "name" => "partial-match",
    "label" => "Partial Match",
    "state" => $state["partial-match"],
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["btn-xs", "fd-btn-default", "mv-10"]
    ]
  ])?>
</div>

<!-- SEARCH ONLY IN... ================================================== -->
<div class="row sm-ph-100">
  <span>Search only in...</span>
  <?=component("form/button-checkboxes", [
    "name" => "in-fields", // There"s a single input with multiple values
    "items" => [
      "name"        => "Names",
      "code"        => "Codes",
      "text"        => "Texts",
      "race"        => "Races",
      "flavor_text" => "Flavor",
    ],
    "state" => $state["in-fields"],
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["btn-xs", "fd-btn-default", "mv-10"]
    ]
  ])?>
</div>

<!-- EXCLUDE ============================================================ -->
<div class="row sm-ph-100">
  <span class="filter-desc">Exclude...</span>
  <?=component("form/button-checkboxes", [
    "name" => "exclude",
    "state" => $state["exclude"],
    "items" => [
      "basics"     => "Basics",
      "spoilers"   => "Spoilers",
      "alternates" => "Alternates",
      "reprints"   => "Reprints",
      "promo"      => "Promo",
    ],
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["btn-xs", "fd-btn-default", "mv-10"]
    ]
  ])?>

</div>

<!-- SORT BY ============================================================ -->
<div class="row sm-ph-100">
  <span>Sort results by...</span>
  <div class="display-inline-block">
    <?=component("form/button-dropdown", [
      "name" => "sort",
      "state" => $state["sort"],
      "items" => fd_lookup("sortables.cards"),
      "size" => "xs"
    ])?>
  </div>
  <?=component("form/button-checkbox", [
    "name" => "sort-dir",
    "label" => "Descending",
    "value" => "desc",
    "state" => $state["sort-dir"],
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["btn-xs", "fd-btn-default"]
    ]
  ])?>
</div>
