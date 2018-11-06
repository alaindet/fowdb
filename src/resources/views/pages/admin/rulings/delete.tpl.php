<?php
// VARIABLES
// $card_label
// $card_image
// $ruling_is_errata
// $ruling_date
// $ruling_text

// INPUT
// (none)
?>
<div class="page-header">
  <h1>Delete ruling</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Rulings' => url('rulings/manage'),
    'Delete' => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-9">
    <form
      action="<?=url("rulings/delete/{$ruling_id}")?>"
      method="post"
      class="form-horizontal"
    >
      <?=csrf_token()?>

      <!-- Card Name -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Card</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$card_label?>
          </p>
        </div>
      </div>

      <!-- Card image -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Image</label>
        <div class="col-sm-10" id="card-image">
          <a
            href="<?=$card_image?>"
            data-lightbox="cards"
            data-title="<?=$card_label?>"
          >
            <img
              src="<?=$card_image?>"
              width="200px"
            >
          </a>
        </div>
      </div>

      <!-- Ruling is errata -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Errata</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?="It's ".($ruling_is_errata ? '' : 'NOT ')."an errata"?>
          </p>
        </div>

      </div>

      <!-- Ruling date -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Date</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$ruling_date?>
          </p>
        </div>
      </div>

      <!-- Ruling text -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Ruling</label>
        <div class="col-sm-10">
          <p class="fd-text-form-horizontal font-110">
            <?=$ruling_text?>
          </p>
        </div>
      </div>

      <!-- Submit -->
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
