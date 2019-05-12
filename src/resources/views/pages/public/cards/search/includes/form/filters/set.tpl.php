<?php

// VARIABLES
// $state

// INPUTS
// set[] || set

$items = [];
foreach (fd_lookup("clusters.list") as $item) {
  $items[$item["name"]] = $item["sets"];
}

?>
<h3 class="font-110">
  Set
  <?=fd_component("form/select-multiple-handle", [
    "target" => "#filter-set",
    "state" => isset($state["set"]) && is_array($state["set"]),
    "css" => ["btn", "btn-xs", "fd-btn-default"]
  ])?>
</h3>
<div class="row sm-ph-100">
  <?=fd_component("form/select-multiple-items", [
    "id" => "filter-set",
    "name" => "set",
    "css" => ["input-lg"],
    "items" => $items,
    "state" => $state["set"] ?? null
  ])?>
</div>
