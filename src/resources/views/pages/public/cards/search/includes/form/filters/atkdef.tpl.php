<?php

// VARIABLES
// $state

// INPUTS
// atk
// atk_operator
// def
// def_operator

$operators = [
  'lessthan' => '&lt;',
  'equals' => '=',
  'greaterthan' => '&gt;',
];
$defaultAtkOperator = 'equals';
$defaultDefOperator = 'equals';

?>
<div class="row sm-ph-100">

  <!-- Attack =========================================================== -->
  <div class="col-xs-12 col-sm-6 ph-0 pr-50">
    <h3 class="font-110">Attack</h3>
    <?=component('form/input-dropdown', [
      'dropdown' => [
        'name' => 'atk-operator',
        'state' => $state['atk-operator'],
        'items' => $operators,
        'default' => [
          'face' => $operators[$defaultAtkOperator],
          'value' => $defaultAtkOperator
        ]
      ],
      'input' => [
        'name' => 'atk',
        'state' => $state['atk'],
      ],
    ])?>
  </div>
  
  <!-- Defense ========================================================== -->
  <div class="col-xs-12 col-sm-6 ph-0 pl-50">
    <h3 class="font-110">Defense</h3>
    <?=component('form/input-dropdown', [
      'input' => [
        'name' => 'def',
        'state' => $state['def']
      ],
      'dropdown' => [
        'name' => 'def-operator',
        'state' => $state['def-operator'],
        'items' => $operators,
        'default' => [
          'face' => $operators[$defaultDefOperator],
          'value' => $defaultDefOperator
        ]
      ]
    ])?>
  </div>

</div>
