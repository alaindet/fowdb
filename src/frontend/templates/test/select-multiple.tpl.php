<?php

$name = 'PERSON';
$items = [

  // // Grouped
  // 'Letter A' => [
  //   'alain' => 'Alain',
  //   'albert' => 'Albert',
  //   'anthony' => 'Anthony',
  // ],
  // 'Letter B' => [
  //   'baldwin' => 'Baldwin',
  //   'barney' => 'Barney',
  //   'bilbo' => 'Bilbo',
  // ],

  // Non-grouped
  'alain' => 'Alain',
  'albert' => 'Albert',
  'anthony' => 'Anthony',
  'baldwin' => 'Baldwin',
  'barney' => 'Barney',
  'bilbo' => 'Bilbo',

];

?>

<!-- Breadcrumb -->
<?=fd_component('breadcrumb', [
  'Test' => fd_url('test'),
  'select-multiple' => '#'
])?>

<?php // LOG
  if (fd_input()->has($name)) {
    echo fd_log_html(fd_input()->get($name), $name);
  }
?>

<form action="<?=fd_url('test/select-multiple')?>" method="get">

  <!-- The handle -->
  <?=fd_component('form/select-multiple-handle', [
    'target' => '#the-select',
    'css' => ['btn-lg', 'fd-btn-default'],
    'state' => isset($_GET[$name]) && is_array($_GET[$name]),
  ])?>

  <hr class="fd-hr">

  <!-- The select -->
  <?=fd_component('form/select-multiple-items', [
    'id' => 'the-select',
    'name' => $name,
    'items' => $items,
    'state' => $_GET[$name] ?? null,
    'css' => ['input-lg', 'text-monospace'],
  ])?>

  <!-- The submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
