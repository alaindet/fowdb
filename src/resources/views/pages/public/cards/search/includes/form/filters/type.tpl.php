<?php

// VARIABLES
// $state

// INPUTS
// type[]
// type-all

// type_name => type_name
$types = array_keys(fd_lookup("types.display"));
$items = array_combine($types, $types);

?>
<h3 class="font-110">Type</h3>
<div class="row sm-ph-100">

  <!-- Buttons -->
  <?=component("form/button-checkboxes", [
    "name" => "type",
    "items" => $items,
    "state" => $state["type"],
    "css" => [
      "button" => ["btn-xs", "font-105", "mv-10", "fd-btn-default"]
    ],
  ])?>

  <!-- Vertical separator -->
  <hr class="fd-hr mv-25">

  <!-- Must have all selected -->
  <?=component("form/button-checkboxes", [
    "name" => null,
    "state" => [
      $state["type-all"] ? "type-all" : null,
    ],
    "items" => [
      "type-all" => "Must have all selected",
    ],
    "css" => [
      "button" => ["btn-xs", "fd-btn-default"]
    ]
  ])?>
  
</div>
