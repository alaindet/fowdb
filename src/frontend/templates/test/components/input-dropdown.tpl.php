<?php

$thisUrl = "test/components/input-dropdown";
$thisInputName = "THE_INPUT";
$thisInputValue = fd_input()->get($thisInputName);
$thisDropdownName = "THE_DROPDOWN";
$thisDropdownValue = fd_input()->get($thisDropdownName);

$items = [
  "item1" => "aaa",
  "item2" => "bbb",
  "item3" => "ccc",
  "item4" => "ddd",
];

?>

<?= fd_component("breadcrumb", [
  "Test" => fd_url("test"),
  "input-dropdown" => "#"
]) ?>

<form
  action="<?=fd_url($thisUrl)?>"
  method="get"
>

  <!-- component -->
  <?= fd_component("form/input-dropdown", [

    "dropdown" => [
      "name" => $thisDropdownName,
      "state" => $thisDropdownValue,
      "items" => $items,
      "css" => ["btn-lg"],
      "default" => [
        "face" => "DEFAULT_FACE",
        "value" => "DEFAULT_VALUE",
      ]
    ],

    "input" => [
      "name" => $thisInputName,
      "state" => $thisInputValue,
      "css" => ["input-lg"],
    ]

  ]) ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
