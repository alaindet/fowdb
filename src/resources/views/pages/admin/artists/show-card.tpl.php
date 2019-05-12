<?php

// VARIABLES
// $card
// $next_card

// INPUTS
// card-id
// artist-name

$card_image = fd_asset($card['image_path']);

?>
<div class="page-header">
  <h1>Artists</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Artists' => fd_url('artists'),
    'Set' => fd_url("artists/set/{$card['sets_id']}"),
    'Card' => '#',
  ])?>
</div>

<div class="row">

  <!-- Image -->
  <div class="col-xs-12 col-sm-5 text-center sm-text-left mb-100">
    <a
      data-lightbox="card"
      data-title="<?="{$card['name']} ({$card['code']})"?>"
      href="<?=$card_image?>"
    >
      <img src="<?=$card_image?>">
    </a>
  </div>

  <!-- Form -->
  <div class="col-xs-12 col-sm-7">

    <form
      action="<?=fd_url('artists/store')?>""
      method="post"
      class="form-horizontal"
    >
      <?=fd_csrf_token()?>

      <!-- Card ID -->
      <input
        type="hidden"
        name="card-id"
        value="<?=$card['id']?>"
      >

      <!-- Input -->
      <input
        type="text"
        name="artist-name"
        class="form-control input-lg"
        placeholder="Artist name..."
        autofocus
        value="<?=$card['artist_name'] ?? ''?>"
        id="the-autocomplete"
      >

      <p></p>

      <!-- Save artist name -->
      <button type="submit" class="btn btn-primary btn-block btn-lg">
        <i class="fa fa-save"></i>
        Save artist
      </button>

      <!-- Show original image -->
      <a
        href="<?=$card_image?>"
        class="btn fd-btn-default btn-block btn-lg"
        target="_blank"
      >
        <i class="fa fa-external-link"></i>
        Show original image
      </a>

      <!-- Go to card page -->
      <a
        href="<?=fd_url('card/'.$card['code'])?>"
        class="btn fd-btn-default btn-block btn-lg"
        target="_blank"
      >
        <i class="fa fa-external-link"></i>
        Go to card page
      </a>

      <!-- Go to next card -->
      <?php if (!empty($next_card)): ?>
        <a
          href="<?=fd_url('artists/card/'.$next_card['id'])?>"
          class="btn fd-btn-default btn-block btn-lg"
        >
          <i class="fa fa-arrow-right"></i>
          Go to next card
        </a>
      <?php else: ?>

        <p class="text-center font-110 p-50">
          No next card!
        </p>

      <?php endif; ?>

    </form>

  </div>

</div>
