<?php

// VARIABLES
// $cards
// $set

?>
<div class="page-header">
  <h1>Artists</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Artists' => url('artists'),
    'Set' => '#'
  ])?>
</div>

<h2><?=$set['name']?></h2>

<hr>

<div class="row">
  <div class="col-xs-12">
    <?php foreach ($cards as $card): ?>
      <div class="fdb-card fdb-card-10">
        <a
          href="<?=url("artists/card/{$card['id']}")?>"
          target="_self"
        >
          <img
            src="<?=asset($card['image_path'])?>"
            <?php if (!isset($card['artist_name'])): ?>
              style="border:2px solid red!important;"
            <?php endif; ?>
          >
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
