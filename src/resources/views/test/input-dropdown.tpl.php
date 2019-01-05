<?php

$items = [
  'item1' => 'aaa',
  'item2' => 'bbb',
  'item3' => 'ccc',
  'item4' => 'ddd',
];

$state = [
  'input' => 'Hello, World!',
  'dropdown' => 'item3',
];

?>
<form action="<?=url('test/input-dropdown')?>" method="get">
  
  <!-- component -->
  <?=component('form/input-dropdown', [

    'size' => 'lg',

    'input' => [
      'name' => 'THE_INPUT',
      'state' => $state['input'],
    ],

    'dropdown' => [
      'name' => 'THE_DROPDOWN',
      'state' => $state['dropdown'],
      'items' => $items,
      'default' => [
        'face' => 'DEFAULT_FACE',
        'value' => 'DEFAULT_VALUE',
      ]
    ],

  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
