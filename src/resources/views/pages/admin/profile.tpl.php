<?php
// VARIABLES
// $links
?>
<div class="page-header">
  <h1>Admin profile</h1>
</div>

<ul>
  <?php foreach($links as $link => $label): ?>
    <li class="separate">
      <a href="<?=$link?>" class="btn btn-lg btn-link">
        <?=$label?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?=component('logout')?>
