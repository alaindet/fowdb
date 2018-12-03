<?php
  // VARIABLES
  // $card_id
  // $card_name
  // $card_code
  // $card_image
  // $card_label
  // $ruling_id
  // $ruling_date
  // $ruling_is_errata
  // $ruling_text

  // INPUT
  // card-id
  // card-name
  // ruling-errata
  // ruling-date
  // ruling-text
?>
<div class="page-header">
  <h1>Update ruling</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Rulings' => url('rulings/manage'),
    '(&larr; Card page)' => url_old(
      'card',
      ['code' => urlencode($card_code)]
    ),
    'Update' => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-9">
    <form
      action="<?=url("rulings/update/{$ruling_id}")?>"
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

          <!-- Image -->
          <a
            href="<?=$card_image?>"
            data-lightbox="cards"
            data-title="<?=$card_label?>"
          >
            <span class="fd-zoomable-lg">
              <img
                src="<?=$card_image?>"
                width="250px"
              >
            </span>
          </a>

          <!-- Link -->
          <br>
          <a
            href="<?=url_old('card', ['code' => urlencode($card_code)])?>"
            class="btn btn-link"
          >
            <i class="fa fa-external-link"></i>
            Go to card page
          </a>

        </div>
      </div>

      <!-- Ruling is errata -->
      <?php
        $checked = $ruling_is_errata ? 'checked="true"' : '';
      ?>
      <div class="form-group">
        <label class="col-sm-2 control-label">Errata</label>
        <div class="col-sm-10">
          <div class="checkbox">
            <label for="ruling-errata">
              <input
                type="checkbox"
                name="ruling-errata"
                id="ruling-errata"
                value="1"
                <?=$checked?>
              >
              <span class="text-danger font-110 text-bold">
                It's an errata
              </span>
            </label>
          </div>
        </div>

      </div>

      <!-- Ruling date -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Date</label>
        <div class="col-sm-10">
          <input
            type="text"
            class="form-control text-monospace font-110"
            name="ruling-date"
            placeholder="YYYY-MM-DD (Empty leaves it as it is).."
            value="<?=$ruling_date?>"
          >
        </div>
      </div>

      <!-- Ruling text -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Ruling</label>
        <div class="col-sm-10">
          <textarea
            name="ruling-text"
            class="form-control text-monospace font-110"
            rows="6"
            placeholder="Ruling text..."
          ><?=$ruling_text?></textarea>
        </div>
      </div>

      <!-- Submit -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button
            type="submit"
            class="btn btn-lg fd-btn-primary"
          >
            <i class="fa fa-pencil"></i>
            Update
          </button>
        </div>
      </div>

    </form>
  </div>
</div>
