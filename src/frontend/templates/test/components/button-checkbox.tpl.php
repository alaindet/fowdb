<?php
$thisUrl = "test/components/form/button-checkbox";
$thisInput = fd_input()->has("INPUT_NAME", "get");
?>

<div class="page-header">
  <h1>Component: <em>form/button-checkbox</em></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      "form/button-checkbox" => "#"
    ]);
  ?>
</div>

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
