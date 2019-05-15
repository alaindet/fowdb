<?php

$thisUrl = "test/components/button-checkboxes";
$thisInput = fd_input()->get("INPUT_NAME", []);

$items = [
  "item1" => "aaa",
  "item2" => "bbb",
  "item3" => "ccc",
  "item4" => "ddd",
];

?>

<?= fd_component("breadcrumb", [
  "Test" => fd_url("test"),
  "button-checkboxes" => "#"
]) ?>

<form
  action="<?=fd_url($thisUrl)?>"
  method="get"
>

  <!-- component -->
  <?= fd_component("form/button-checkboxes", [
    "name" => "INPUT_NAME",
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
