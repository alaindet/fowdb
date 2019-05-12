<?php

// VARIABLES
// $state

// INPUTS
// rarity[]

?>
<h3 class="font-110">Rarity</h3>
<div class="row sm-ph-100">
  <?=fd_component("form/button-checkboxes", [
    "name" => "rarity",
    "items" => fd_lookup("rarities.code2name"),
    "state" => $state["rarity"],
    "css" => [
      "button" => ["btn-xs", "fd-btn-default", "font-110", "mv-10"]
    ]
  ])?>
</div>
