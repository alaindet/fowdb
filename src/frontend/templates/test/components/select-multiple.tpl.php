<?php

$thisUrl = "test/components/select-multiple";
$thisInput = "PERSON";

$items = [

  // // Grouped
  // "Letter A" => [
  //   "alain" => "Alain",
  //   "albert" => "Albert",
  //   "anthony" => "Anthony",
  // ],
  // "Letter B" => [
  //   "baldwin" => "Baldwin",
  //   "barney" => "Barney",
  //   "bilbo" => "Bilbo",
  // ],

  // Non-grouped
  "alain" => "Alain",
  "albert" => "Albert",
  "anthony" => "Anthony",
  "baldwin" => "Baldwin",
  "barney" => "Barney",
  "bilbo" => "Bilbo",

];

?>

<!-- Breadcrumb -->
<?= fd_component("breadcrumb", [
  "Test" => fd_url("test"),
  "select-multiple" => "#"
]) ?>

<?php // LOG
  if (fd_input()->has($thisInput)) {
    echo fd_log_html(fd_input()->get($thisInput), $thisInput);
  }
?>

<form action="<?= fd_url($thisUrl) ?>" method="get">

  <!-- The handle -->
  <?=fd_component("form/select-multiple-handle", [
    "target" => "#the-select",
    "css" => ["btn-lg", "fd-btn-default"],
    "state" => is_array(fd_input()->get($thisInput)),
  ]) ?>

  <hr class="fd-hr">

  <!-- The select -->
  <?=fd_component("form/select-multiple-items", [
    "id" => "the-select",
    "name" => $thisInput,
    "items" => $items,
    "state" => fd_input()->get($thisInput),
    "css" => ["input-lg", "text-monospace"],
  ]) ?>

  <!-- The submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
