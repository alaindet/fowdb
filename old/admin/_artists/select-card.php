<?php

// Check authorization and bounce back intruders
auth()->allow([\App\Legacy\Authorization::ROLE_ADMIN]);

$setId = lookup('sets.code2id.' . $_GET['set']);

$set = database()
  ->select(
    statement('select')
      ->select('name')
      ->from('game_sets')
      ->where('id = :id')
  )
  ->bind([':id' => $setId])
  ->first();

$cards = database()
  ->select(
    statement('select')
      ->select(['id','image_path',])
      ->from('cards')
      ->where('sets_id = :setid')
      ->orderBy('num')
  )
  ->bind([':setid' => $setId])
  ->get();

\App\Services\Session::delete('artist-tool');
?>

<div class="page-header">
  <h1>Artists</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Artists' => url_old('admin/_artists/select-set'),
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
          href="<?=url_old('admin/_artists/card', ['id' => $card['id']])?>"
          target="_self"
        >
          <img src="<?=$card['image_path']?>">
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
