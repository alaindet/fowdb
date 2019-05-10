<?php

// VARIABLES
// $state

// INPUTS
// cost[]
// cost-x

?>
<h3 class="font-110">Total Cost</h3>
<div class="row sm-ph-100">

  <!-- Normal costs -->
  <?=component("form/button-checkboxes", [
    "name" => "cost",
    "state" => $state["cost"],
    "items" => fd_lookup("costs"),
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["font-110", "fd-btn-default", "mv-10"]
    ]
  ])?>

  <!-- Vertical separator -->
  <hr class="fd-hr mv-25">

  <!-- X cost -->
  <?=component("form/button-checkbox", [
    "name" => "cost-x",
    "state" => $state["cost-x"],
    "label" => "Must have X cost",
    "css" => [
      "container" => ["display-inline-block"],
      "button" => ["btn-xs", "fd-btn-default", "mv-10"]
    ]
  ])?>

</div>
