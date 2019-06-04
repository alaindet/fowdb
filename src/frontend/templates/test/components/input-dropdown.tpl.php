<?php

$thisUrl = "test/components/form/input-dropdown";

$items = [
  "item1" => "aaa",
  "item2" => "bbb",
  "item3" => "ccc",
  "item4" => "ddd",
];

?>

<?php
  echo fd_component("navigation/breadcrumb", (object) [
    "Test" => "test",
    "form/input-dropdown" => "#"
  ]);
?>

<form action="<?=fd_url($thisUrl)?>" method="get">

  <?php // Small size
    echo fd_component("form/input-dropdown", (object) [
      "size" => "sm",
      "css" => ["mv-100"],
      "dropdown" => [
        "name" => "DROPDOWN_SMALL",
        "state" => fd_input()->get("DROPDOWN_SMALL"),
        "items" => $items,
        "default" => [
          "label" => "DROPDOWN_SMALL_DEFAULT_LABEL",
          "value" => "DROPDOWN_SMALL_DEFAULT_VALUE",
        ]
      ],
      "input" => [
        "name" => "INPUT_SMALL",
        "state" => fd_input()->get("INPUT_SMALL"),
        "placeholder" => "This is a small input",
        "autofocus" => true,
      ]
    ]);
  ?>

  <?php // Regular size
    echo fd_component("form/input-dropdown", (object) [
      "css" => ["mv-100"],
      "dropdown" => [
        "name" => "DROPDOWN_REGULAR",
        "state" => fd_input()->get("DROPDOWN_REGULAR"),
        "items" => $items,
        "default" => [
          "label" => "DROPDOWN_REGULAR_DEFAULT_LABEL",
          "value" => "DROPDOWN_REGULAR_DEFAULT_VALUE",
        ]
      ],
      "input" => [
        "name" => "INPUT_REGULAR",
        "state" => fd_input()->get("INPUT_REGULAR"),
        "placeholder" => "This is a regular input",
      ]
    ]);
  ?>

  <?php // Large size
    echo fd_component("form/input-dropdown", (object) [
      "size" => "lg",
      "css" => ["mv-100"],
      "dropdown" => [
        "name" => "DROPDOWN_LARGE",
        "state" => fd_input()->get("DROPDOWN_LARGE"),
        "items" => $items,
        "default" => [
          "label" => "DROPDOWN_LARGE_DEFAULT_LABEL",
          "value" => "DROPDOWN_LARGE_DEFAULT_VALUE",
        ]
      ],
      "input" => [
        "name" => "INPUT_LARGE",
        "state" => fd_input()->get("INPUT_LARGE"),
        "placeholder" => "This is a large input",
      ]
    ]);
  ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
