<?php

$items = [
  'item1' => 'aaa',
  'item2' => 'bbb',
  'item3' => 'ccc',
  'item4' => 'ddd',
];

?>

<?=component('breadcrumb', [
  'Test' => url('test'),
  'button-dropdown' => '#'
])?>

<form action="<?=url('test/button-dropdown')?>" method="get">
  
  <!-- component -->
  <?=component('form/button-dropdown', [
    'default' => [
      'face' => 'DEFAULT_FACE',
      'value' => 'DEFAULT_VALUE'
    ],
    'name' => 'INPUT_NAME',
    'size' => 'lg',
    'state' => fd_input()->get('INPUT_NAME'),
    'items' => $items,
  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
