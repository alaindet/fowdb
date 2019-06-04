<?php
$thisUrl = "test/components/form/button-checkbox";
$thisInput = fd_input()->has("INPUT_NAME", "get");
?>

<?php
  echo fd_component("navigation/breadcrumb", (object) [
    "Test" => "test",
    "form/button-checkbox" => "#"
  ]);
?>

<h1>Hello there!</h1>

<form action="<?=fd_url($thisUrl)?>" method="get">

  <!-- component -->
  <?php
    echo fd_component("form/button-checkbox", (object) [
      "name" => "INPUT_NAME",
      "value" => "INPUT_VALUE",
      "label" => "INPUT_LABEL",
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
