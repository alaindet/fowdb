<?php

// VARIABLES
// ...

// INPUTS
// cluster[] || cluster

$items = [];
foreach (fd_lookup("clusters.list") as $code => $item) {
  $name = $item["name"];
  $items[$code] = $name;
}

?>
<h3 class="font-110">
  Cluster
  <?=component("form/select-multiple-handle", [
    "target" => "#filter-cluster",
    "state" => isset($state["cluster"]) && is_array($state["cluster"]),
    "css" => ["btn", "btn-xs", "fd-btn-default"]
  ])?>
</h3>
<div class="row sm-ph-100">
  <?=component("form/select-multiple-items", [
    "id" => "filter-cluster",
    "name" => "cluster",
    "css" => ["input-lg"],
    "items" => $items,
    "state" => $state["cluster"] ?? null
  ])?>
</div>
