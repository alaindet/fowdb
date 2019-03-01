<?php

// VARIABLES
// $state

// INPUTS
// format[]

// Legacy
if (is_array($state['format'])) {
  $state['format'] = $state['format'][0];
}

?>
<h3 class="font-110">Format</h3>
<div class="row sm-ph-100">
  <?=component('form/button-radio', [
    'name' => 'format',
    'items' => lookup('formats.display'),
    'state' => $state['format'],
    'css' => [
      'button' => ['btn-xs', 'font-105', 'mv-10', 'fd-btn-default']
    ]
  ])?>
</div>
