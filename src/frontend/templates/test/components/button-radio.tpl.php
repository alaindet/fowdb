<?php

$thisUrl = "test/components/form/button-radio";
$thisInputName = "INPUT_NAME";
$thisInput = fd_input()->get($thisInputName);

?>

<div class="page-header">
  <h1>Component: <em>form/button-radio</em></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      "form/button-radio" => "#"
    ]);
  ?>
</div>

<form action="<?=fd_url($thisUrl)?>" method="get">

  <!-- component -->
  <?php
    echo fd_component("form/button-radio", (object) [
      "name" => $thisInputName,
      "items" => [
        "aaa" => "Item1",
        "bbb" => "Item2",
        "ccc" => "Item3",
        "ddd" => "Item4",
      ],
      "state" => $thisInput,
      "css" => [
        "button" => ["mv-10", "fd-btn-default"]
      ]
    ]);
  ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
