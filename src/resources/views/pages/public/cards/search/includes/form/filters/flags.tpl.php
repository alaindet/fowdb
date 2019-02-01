<?php

// VARIABLES
// $state

// INPUTS
// quickcast

?>
<h3 class="font-110">Divinity</h3>
<div class="row sm-ph-100">
  <?=component('form/button-checkboxes', [
    'name' => null, // There are multiple input names!
    'state' => [
      'quickcast' => $state['quickcast'],
    ],
    'items' => [
      'quickcast' => 'Has Quickcast',
    ],
    'css' => [
      'button' => ['font-105', 'fd-btn-default', 'mv-10']
    ]
  ])?>
</div>
