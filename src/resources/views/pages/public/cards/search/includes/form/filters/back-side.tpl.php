<?php

// VARIABLES
// $state

// INPUTS
// back-side[]

?>
<h3 class="font-110">Back Side</h3>
<div class="row sm-ph-100">
  <?=component('form/button-checkboxes', [
    'name' => 'back-side',
    'items' => lookup('backsides.code2name'),
    'state' => $state['back-side'],
    'css' => [
      'button' => ['btn-xs', 'fd-btn-default', 'font-110', 'mv-10']
    ]
  ])?>
</div>
