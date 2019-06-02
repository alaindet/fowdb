<?php

$thisUrl = "test/components/button-checkboxes"; 

?>

<?php
  echo fd_component("navigation/breadcrumb", (object) [
    "Test" => "test",
    "button-checkboxes" => "#"
  ]);
?>

<form action="<?=fd_url($thisUrl)?>" method="get">

  <!-- component: multiple values -->
  <?php
    echo fd_component("form/button-checkboxes", (object) [
      "name" => "INPUT_NAME",
      "items" => [
        "item1" => "aaa",
        "item2" => "bbb",
        "item3" => "ccc",
        "item4" => "ddd",
      ],
      "state" => fd_input()->get("INPUT_NAME", []),
      "css" => [
        "button" => ["mv-10", "fd-btn-default"]
      ]
    ]);
  ?>

  <!-- component: multiple inputs -->
  <?php
    echo fd_component("form/button-checkboxes", (object) [
      "items" => [
        "input1" => "This is input 1",
        "input2" => "This is input 2",
        "input3" => "This is input 3",
      ],
      "state" => [
        fd_input()->has("input1") ? "input1" : "",
        fd_input()->has("input2") ? "input2" : "",
        fd_input()->has("input3") ? "input3" : "",
      ],
      "css" => [
        "button" => ["mv-10", "fd-btn-default"]
      ]
    ]);
  ?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
