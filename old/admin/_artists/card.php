<?php

use \App\Legacy\Authorization;
use \App\Models\Card;
use \App\Services\Session;

// Check authorization and bounce back intruders
Authorization::allow([1, 3]);

$card = Card::getById($_GET['id'], [
  'id',
  'sets_id',
  'num',
  'back_side',
  'artist_name',
  'image_path'
]);

// Store all this set's *remaining* cards into the session
if (!Session::has('artist-tool')) {

  Session::set('artist-tool', array_reduce(

    // Data
    database()
      ->select(
        statement('select')
          ->select(['id', 'num', 'back_side'])
          ->from('cards')
          ->where('sets_id = :setid AND num >= :num')
          ->orderBy('num')
      )
      ->bind([
        ':setid' => $card['sets_id'],
        ':num' => $card['num']
      ])
      ->get(),

    // Reducer
    function ($result, $card) {
      $key = "{$card['num']}.{$card['back_side']}";
      $result['list'][$key] = $card['id'];
      return $result;
    },

    // State
    [
      'set' => $card['sets_id'],
      'list' => []
    ]

  ));
}
?>

<div class="page-header">
  <h1>Artists</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Artists' => url_old('admin/_artists/select-set'),
    'Set' => url_old(
      'admin/_artists/select-card',
      ['set' => lookup("sets.id2code.{$card['sets_id']}")]
    ),
    'Card' => '#'
  ])?>
</div>

<div class="row">

  <!-- Form -->
  <div class="col-xs-12 col-sm-7">

    <form
      action="/old/admin/_artists/artist-store.php"
      method="post"
      class="form-horizontal"
    >
      <?=csrf_token()?>

      <!-- Card ID -->
      <input
        type="hidden"
        name="id"
        value="<?=$card['id']?>"
      >

      <!-- Input -->
      <input
        type="text"
        name="artist"
        id="the-autocomplete"
        class="form-control input-lg"
        placeholder="Artist name..."
        autofocus
        value="<?=$card['artist_name'] ?? ''?>"
      >

      <p></p>

      <!-- Save artist name -->
      <button type="submit" class="btn btn-primary btn-block btn-lg">
        <i class="fa fa-save"></i>
        Save
      </button>

    </form>

  </div>

  <!-- Image -->
  <div class="col-xs-12 col-sm-5">
    <a
      data-lightbox="card"
      href="<?=asset($card['image_path'])?>"
    >
      <img src="<?=asset($card['image_path'])?>">
    </a>
  </div>

</div>
