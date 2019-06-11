<!-- Breadcrumb -->
<div class="page-header">
  <h1>Component: <em>form/select-submit</em></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      "form/select-submit" => "#"
    ]);
  ?>
</div>

<h1>With grouped data</h1>

<?php // Grouped data
  echo fd_component("form/select-submit", (object) [
    "url" => fd_url("test/components/form/select-submit"),
    "name" => "SELECT_SUBMIT_GROUPED",
    "defaultOption" => "Select an option...",
    "state" => fd_input()->get("SELECT_SUBMIT_GROUPED"),
    "items" => [
      "Letter A" => [
        "Alain" => "ala",
        "Albert" => "alb",
        "Alice" => "ali",
      ],
      "Letter B" => [
        "Barbara" => "bar",
        "Becky" => "bec",
        "Benjamin" => "ben",
      ],
    ],
  ]);
?>

<hr>

<h1>With non-grouped data</h1>

<?php // Non-grouped data
  echo fd_component("form/select-submit", (object) [
    "url" => fd_url("test/components/form/select-submit"),
    "name" => "SELECT_SUBMIT_NON_GROUPED",
    "defaultOption" => "Select an option...",
    "state" => fd_input()->get("SELECT_SUBMIT_NON_GROUPED"),
    "items" => [
      "Alain" => "ala",
      "Albert" => "alb",
      "Alice" => "ali",
      "Barbara" => "bar",
      "Becky" => "bec",
      "Benjamin" => "ben",
    ],
  ]);
?>
