<?php

// VARIABLES
// $item

$cardLabel = "{$item['card_name']} ({$item['card_code']})";
$cardImage = fd_asset($item['card_image']);

?>
<div class="page-header">
  <h1>
    Delete restriction
    <small><?=$cardLabel?></small>
  </h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Restrictions' => url('restrictions/manage'),
    '(&larr; Card page)' => url('card/'.urlencode($item['card_code'])),
    'Update' => '#'
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <form
      action="<?=url("restrictions/delete/{$item['id']}")?>"
      method="post"
      class="form-horizontal"
    >
      <?=fd_csrf_token()?>

      <!-- Card Name ====================================================== -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Card</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$cardLabel?>
          </p>
        </div>
      </div>

      <!-- Card image ===================================================== -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Image</label>
        <div class="col-sm-10" id="card-image">
          <a
            href="<?=$cardImage?>"
            data-lightbox="cards"
            data-title="<?=$cardLabel?>"
          >
            <span class="fd-zoomable-lg">
              <img
                src="<?=$cardImage?>"
                width="200px"
              >
            </span>
          </a>
        </div>
      </div>

      <!-- Format ========================================================= -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Format</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$item['format_name']?>
          </p>
        </div>
      </div>

      <!-- Deck =========================================================== -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Deck</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=\App\Models\PlayRestriction::$decksLabels[$item['deck']]?>
          </p>
        </div>
      </div>

      <!-- Copies ========================================================= -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Copies</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$item['copies']?>
          </p>
        </div>
      </div>

      <!-- Submit ========================================================= -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button
            type="submit"
            class="btn btn-lg fd-btn-primary"
          >
            <i class="fa fa-trash"></i>
            Delete
          </button>
        </div>
      </div>

    </form>
  </div>
</div>
