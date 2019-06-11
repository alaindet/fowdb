<?php

$thisUrl = "test/components/form/select-multiple";

?>

<!-- Breadcrumb -->
<div class="page-header">
  <h1>Component: <em>form/select-multiple</em></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      "form/select-multiple" => "#"
    ]);
  ?>
</div>

<form action="<?= fd_url($thisUrl) ?>" method="get">

  <!-- Non-grouped select -->
  <div class="form-group">
    <h1>With non-grouped data</h1>
    <?php
      echo fd_component("form/select-multiple", (object) [
        "name" => "PERSON_NON_GROUPED",
        "state" => fd_input()->get("PERSON_NON_GROUPED"),
        "items" => [
          "alain" => "Alain",
          "albert" => "Albert",
          "anthony" => "Anthony",
          "baldwin" => "Baldwin",
          "barney" => "Barney",
          "bilbo" => "Bilbo",
        ],
        "css" => [
          "handle" => ["btn-xs", "fd-btn-default", "mb-100"],
          "select" => ["input-lg", "text-monospace"],
        ],
      ]);
    ?>
  </div>

  <hr>

  <!-- Grouped select -->
  <div class="form-group">
    <h1>With grouped data</h1>
    <?php
      echo fd_component("form/select-multiple", (object) [
        "name" => "PERSON_GROUPED",
        "state" => fd_input()->get("PERSON_GROUPED"),
        "items" => [
          "Letter A" => [
            "alain" => "Alain",
            "albert" => "Albert",
            "anthony" => "Anthony",
          ],
          "Letter B" => [
            "baldwin" => "Baldwin",
            "barney" => "Barney",
            "bilbo" => "Bilbo",
          ],
        ],
        "css" => [
          "handle" => ["btn-xs", "fd-btn-default", "mb-100"],
          "select" => ["input-lg", "text-monospace"],
        ],
      ]);
    ?>
  </div>

  <!-- The submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
