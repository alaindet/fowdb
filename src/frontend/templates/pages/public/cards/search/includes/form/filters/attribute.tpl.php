<?php

// VARIABLES
// $state

// INPUTS
// attribute[]
// attribute-single
// attribute-multi
// attribute-only
// attribute-all

// Map attribute codes to HTML icons
$items = [];
$blank = fd_asset("images/icons/blank.gif");
$attributes = array_keys(fd_lookup("attributes.display"));
foreach ($attributes as $attribute) {
  $items[$attribute] = (
    "<img src=\"{$blank}\" class=\"fd-icon-{$attribute} --bigger\">"
  );
}

?>
<h3 class="font-110">Attribute</h3>
<div class="row sm-ph-100">

  <!-- Buttons with icons -->
  <?=fd_component("form/button-checkboxes", [
    "name" => "attribute",
    "state" => $state["attribute"],
    "items" => $items,
    "css" => [
      "button" => ["mv-10", "fd-btn-default"]
    ]
  ])?>

  <!-- Vertical separator -->
  <hr class="fd-hr mv-25">

  <!-- Button flags -->
  <?=fd_component("form/button-checkboxes", [
    "name" => null,
    "state" => [
      $state["attribute-single"] ? "attribute-single" : null,
      $state["attribute-multi"] ? "attribute-multi" : null,
      $state["attribute-only"] ? "attribute-only" : null,
      $state["attribute-all"] ? "attribute-all" : null,
    ],
    "items" => [
      "attribute-single" => "No Multi-Attribute",
      "attribute-multi" => "Only Multi-Attribute",
      "attribute-only" => "Only selected",
      "attribute-all" => "All selected",
    ],
    "css" => [
      "button" => ["btn-xs", "fd-btn-default"]
    ]
  ])?>

</div>