<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow();

// Read set's info
$set = database_old()->get(
  "SELECT name, code FROM sets WHERE code = :code",
  [':code' => $_GET['set']],
  $first = true
);

// Read set's cards
$cards = database_old()->get(
  "SELECT * FROM cards WHERE setcode = :setcode ORDER BY num ASC",
  [':setcode' => $set['code']]
);

// Empty the session!
unset($_SESSION['artist-tool']);
?>

<div class="row">

  <div class="col-xs-12">
    <div class="page-header"><h1><?=$set['name']?></h1></div>
    <div class="well well-lg">Click on a card to start from there!</div>
  </div>

  <div class="col-xs-12">
    <?php foreach ($cards as &$card): ?>
      <div class="fdb-card fdb-card-10">
        <a
          href="<?=url_old('temp/admin/artists/card', ['id' => $card['id']])?>"
          target="_self"
        >
          <img src="<?=$card['image_path']?>">
        </a>
      </div>
    <?php endforeach; ?>
  </div>

</div>
