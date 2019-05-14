<?php

$thisUrl = "test/components/button-dropdown";
$thisInputName = "INPUT_NAME";
$thisInput = fd_input()->get($thisInputName);

$items = [
  "item1" => "aaa",
  "item2" => "bbb",
  "item3" => "ccc",
  "item4" => "ddd",
];

?>

<?= fd_component("breadcrumb", [
  "Test" => fd_url("test"),
  "button-dropdown" => "#"
]) ?>

<form action="<?= fd_url($thisUrl) ?>" method="get">

  <!-- component -->
  <?= fd_component("form/button-dropdown", [
    "default" => [
      "face" => "DEFAULT_FACE",
      "value" => "DEFAULT_VALUE"
    ],
    "name" => $thisInputName,
    "size" => "lg",
    "state" => $thisInput,
    "items" => $items,
  ]) ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
