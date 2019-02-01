<?php

// VARIABLES
// $state

// INPUTS
// type[]
// type-selected

// type_name => type_name
$types = array_keys(lookup('types.display'));
$items = array_combine($types, $types);

?>
<h3 class="font-110">Type</h3>
<div class="row sm-ph-100">

  <!-- Buttons -->
  <?=component('form/button-checkboxes', [
    'name' => 'type',
    'items' => $items,
    'state' => $state['type'],
    'css' => [
      'button' => ['btn-xs', 'font-105', 'mv-10', 'fd-btn-default']
    ],
  ])?>

  <!-- Vertical separator -->
  <hr class="fd-hr mv-25">

  <!-- Must have all selected -->
  <?=component('form/button-checkboxes', [
    'name' => null,
    'state' => [
      $state['type-selected'] ? 'type-selected' : null,
    ],
    'items' => [
      'type-selected' => 'Must have all selected',
    ],
    'css' => [
      'button' => ['btn-xs', 'fd-btn-default']
    ]
  ])?>
  
</div>
