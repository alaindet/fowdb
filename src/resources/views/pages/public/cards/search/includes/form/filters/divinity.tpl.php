<?php

// VARIABLES
// $state

// INPUTS
// divinity[]

?>
<h3 class="font-110">Divinity</h3>
<div class="row sm-ph-100">
  <?=component('form/button-checkboxes', [
    'name' => 'divinity',
    'items' => lookup('divinities'),
    'state' => $state['divinity'],
    'css' => [
      'button' => ['font-110', 'fd-btn-default']
    ]
  ])?>
</div>
