<?php

$thisUrl = "test/components/form/button-dropdown";
$thisInputName = "INPUT_NAME";
$thisInput = fd_input()->get($thisInputName);

?>

<div class="page-header">
  <h1>Component: <em>form/button-dropdown</em></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      "button-dropdown" => "#"
    ]);
  ?>
</div>

<form action="<?= fd_url($thisUrl) ?>" method="get">

  <!-- component -->
  <?= fd_component("form/button-dropdown", (object) [
    "default" => [
      "face" => "DEFAULT_FACE",
      "value" => "DEFAULT_VALUE"
    ],
    "name" => $thisInputName,
    "size" => "lg",
    "state" => $thisInput,
    "items" => [
      "item1" => "aaa",
      "item2" => "bbb",
      "item3" => "ccc",
      "item4" => "ddd",
    ]
  ]) ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>