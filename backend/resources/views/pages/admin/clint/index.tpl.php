<?php

// VARIABLES
// $commands

?>
<div class="page-header">
  <h1>Clint commands</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Clint' => '#'
  ])?>
</div>

<ul class="fd-list --spaced font-110">
  <?php foreach ($commands as $key => $command): ?>
    <li>
      <a href="<?=url('clint/'.$key)?>">
        <?=$command['label']?>
      </a>
      <br>
      <?=$command['command']?>
    </li>
  <?php endforeach; ?>
</ul>
