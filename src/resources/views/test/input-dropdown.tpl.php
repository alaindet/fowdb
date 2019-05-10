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
  'input-dropdown' => '#'
])?>

<form action="<?=url('test/input-dropdown')?>" method="get">
  
  <!-- component -->
  <?=component('form/input-dropdown', [

    'dropdown' => [
      'name' => 'THE_DROPDOWN',
      'state' => fd_input()->get('THE_DROPDOWN'),
      'items' => $items,
      'css' => ['btn-lg'],
      'default' => [
        'face' => 'DEFAULT_FACE',
        'value' => 'DEFAULT_VALUE',
      ]
    ],

    'input' => [
      'name' => 'THE_INPUT',
      'state' => fd_input()->get('THE_INPUT'),
      'css' => ['input-lg'],
    ]

  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
