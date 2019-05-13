<?php

// VARIABLES
// $links

?>
<div class="page-header">
  <h1>Admin profile</h1>
</div>


<ul class="fd-list --spaced font-110">
  <?php foreach($links as $link => $label): ?>
    <li>
      <a href="<?=$link?>">
        <?=$label?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?=fd_component('logout')?>
