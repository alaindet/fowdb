<?php

// ERROR: Unauthorized
if (admin_level() === 0) {
	notify('You are noth authorized.', 'danger');
	header('Location: /');
	return;
}

// Read set's info
$set = database()->get(
  "SELECT name, code FROM sets WHERE code = :code",
  [':code' => $_GET['set']],
  $first = true
);

// Read set's cards
$cards = database()->get(
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
          href="/?p=temp/admin/artists/card&id=<?=$card['id']?>"
          target="_self"
        >
          <img src="<?=$card['image_path']?>">
        </a>
      </div>
    <?php endforeach; ?>
  </div>

</div>
