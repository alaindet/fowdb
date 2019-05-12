<?php

$items = [
  'aaa' => 'Item1',
  'bbb' => 'Item2',
  'ccc' => 'Item3',
  'ddd' => 'Item4',
];

?>

<?=fd_component('breadcrumb', [
  'Test' => fd_url('test'),
  'button-radio' => '#'
])?>

<form action="<?=fd_url('test/button-radio')?>" method="get">
  
  <!-- component -->
  <?=fd_component('form/button-radio', [
    'name' => 'INPUT_NAME',
    'items' => $items,
    'state' => fd_input()->get('INPUT_NAME') ?? null,
    'css' => [
      'button' => ['mv-10', 'fd-btn-default']
    ]
  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
