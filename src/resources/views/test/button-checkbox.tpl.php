<?=component('breadcrumb', [
  'Test' => url('test'),
  'button-checkbox' => '#'
])?>

<form action="<?=url('test/button-checkbox')?>" method="get">
  
  <!-- component -->
  <?=component('form/button-checkbox', [
    'name' => 'INPUT_NAME',
    'value' => 'INPUT_VALUE',
    'label' => 'INPUT_LABEL',
    'state' => fd_input()->has('INPUT_NAME', 'GET'),
    'css' => [
      'button' => ['mv-10', 'fd-btn-default']
    ]
  ])?>

  <!-- submit -->
  <hr class="fd-hr">
  <button type="submit" class="btn btn-lg btn-primary">SUBMIT</button>

</form>
