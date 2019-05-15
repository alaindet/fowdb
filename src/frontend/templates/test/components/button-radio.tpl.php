<?php

$thisUrl = "test/components/button-radio";
$thisInputName = "INPUT_NAME";
$thisInput = fd_input()->get($thisInputName);

$items = [
  "aaa" => "Item1",
  "bbb" => "Item2",
  "ccc" => "Item3",
  "ddd" => "Item4",
];

?>

<?= fd_component("breadcrumb", [
  "Test" => fd_url("test"),
  "button-radio" => "#"
]) ?>

<form
  action="<?=fd_url($thisUrl)?>"
  method="get"
>

  <!-- component -->
  <?= fd_component("form/button-radio", [
    "name" => $thisInputName,
    "items" => $items,
    "state" => $thisInput,
    "css" => [
      "button" => ["mv-10", "fd-btn-default"]
    ]
  ]) ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
