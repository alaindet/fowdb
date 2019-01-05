<?php

$items = [
  'item1' => 'aaa',
  'item2' => 'bbb',
  'item3' => 'ccc',
  'item4' => 'ddd',
];

?>
<form action="<?=url('test/button-dropdown')?>" method="get">
  
  <!-- component -->
  <?=component('form/button-dropdown', [
    'default' => [
      'face' => 'DEFAULT_FACE',
      'value' => 'DEFAULT_VALUE'
    ],
    'name' => 'INPUT_NAME',
    'size' => 'lg',
    'state' => 2, // Should appear "bbb" selected
    'items' => $items
  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
