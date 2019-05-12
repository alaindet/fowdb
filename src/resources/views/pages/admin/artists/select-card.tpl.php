<?php

// VARIABLES
// $cards
// $set

?>
<div class="page-header">
  <h1>Artists</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Artists' => fd_url('artists'),
    'Set' => '#'
  ])?>
</div>

<h2><?=$set['name']?></h2>

<hr>

<div class="row">
  <div class="col-xs-12 fd-grid-items">
    <?php foreach ($cards as $card): ?>
      <div class="fd-card-item fd-grid fd-grid-10">
        <a
          href="<?=fd_url("artists/card/{$card['id']}")?>"
          target="_self"
        >
          <img
            src="<?=fd_asset($card['image_path'])?>"
            <?php if (!isset($card['artist_name'])): ?>
              style="border:2px solid red!important;"
            <?php endif; ?>
          >
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
