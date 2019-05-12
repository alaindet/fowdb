<?php

$items = [
  'item1' => 'aaa',
  'item2' => 'bbb',
  'item3' => 'ccc',
  'item4' => 'ddd',
];

?>

<?=fd_component('breadcrumb', [
  'Test' => fd_url('test'),
  'input-dropdown' => '#'
])?>

<form action="<?=fd_url('test/input-dropdown')?>" method="get">
  
  <!-- component -->
  <?=fd_component('form/input-dropdown', [

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
