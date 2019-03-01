<?php

$items = [
  'aaa' => 'Item1',
  'bbb' => 'Item2',
  'ccc' => 'Item3',
  'ddd' => 'Item4',
];

?>

<?=component('breadcrumb', [
  'Test' => url('test'),
  'button-radio' => '#'
])?>

<form action="<?=url('test/button-radio')?>" method="get">
  
  <!-- component -->
  <?=component('form/button-radio', [
    'name' => 'INPUT_NAME',
    'items' => $items,
    'state' => input()->get('INPUT_NAME') ?? null,
    'css' => [
      'button' => ['mv-10', 'fd-btn-default']
    ]
  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>