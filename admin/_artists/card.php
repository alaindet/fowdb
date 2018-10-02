<?php

// ERROR: Unauthorized
if (admin_level() === 0) {
  \App\FoWDB::notify('You are noth authorized.', 'danger');
  \App\Redirect::to('/');
	return;
}

// Read this card's info
$card = database()->get(
  "SELECT * FROM cards WHERE id = :id",
  [':id' => $_GET['id']],
  $first = true
);

// Store all this set's cards into the session
if (!isset($_SESSION['artist-tool'])) {
  $_SESSION['artist-tool'] = array_reduce(
    // Data
    database()->get(
      "SELECT id, cardnum, backside
      FROM cards
      WHERE setcode = :setcode AND cardnum >= :num
      ORDER BY cardnum ASC",
      [
        ':setcode' => $card['setcode'],
        ':num' => $card['cardnum']
      ]
    ),
    // Reducer
    function ($result, $row) {
      $result['list'][$row['cardnum'].'.'.$row['backside']] = $row['id'];
      return $result;
    },
    // Carry
    ['set' => $card['setcode'], 'list' => []]
  );
}
?>

<div class="row">
  <div class="col-xs-12">
    <form
      action="admin/_artists/artist-store.php"
      method="post"
      class="form-horizontal"
    >

      <!-- Card ID -->
      <input type="hidden" name="id" value="<?=$card['id']?>">

      <!-- Artist name -->
      <div class="form-group form-section">
        <label class="col-sm-2">Artist name</label>
        <div class="col-sm-10">
          <div class="col-xs-6">
            <input
              type="text"
              name="artist"
              class="form-control input-lg"
              autofocus
              value="<?=$card['artist_name'] ?? ''?>"
            >
          </div>
          <div class="col-xs-6">

            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fa fa-save"></i>
              Save
            </button>

          </div>
        </div>
      </div>

      <!-- Image -->
      <div class="form-group form-section">
        <label class="col-sm-2">Image</label>
        <div class="col-sm-10">
          <img src="<?=$card['image_path']?>">
        </div>
      </div>

    </form>

    <!-- Admin menu link -->
    <hr>

    <!-- Back to this set -->
    <a href="/?p=temp/admin/artists/select-card&set=<?=$card['setcode']?>">
      <button type="button" class="btn btn-default">
          &larr; Cards from <?=strtoupper($card['setcode'])?>
      </button>
    </a>

    <!-- Back to selecting a new set -->
    <a href="/?p=temp/admin/artists/select-set">
      <button type="button" class="btn btn-default">
          &larr; Select a set
      </button>
    </a>

  </div>
</div>
