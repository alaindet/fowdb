<?php
  // VARIABLES
  // $card (optional): id, name, code, image_path

  // INPUT
  // card-id
  // card-name
  // ruling-errata
  // ruling-date
  // ruling-text
  $isCard = isset($card);
?>
<div class="page-header">
  <h1>Create a new ruling</h1>
  <?=component('breadcrumb', [
    'Admin' => url('profile'),
    'Rulings' => url('rulings/manage'),
    'Create' => '#'
  ])?>
</div>

<div class="fd-box --more-margin">
  <div class="fd-box__content">
    <form
      action="<?=url('rulings/create')?>"
      method="post"
      class="form-horizontal"
      id="validate-this-form"
    >
      <?=csrf_token()?>

      <!-- Card ID -->
      <input
        type="hidden"
        name="card-id"
        id="card-id"
        <?=$isCard ? "value=\"{$card['id']}\"" : ""?>
      >

      <!-- Card Name -->
      <div class="form-group">

        <label class="col-sm-2 control-label">Card</label>
        <div class="col-sm-10">
          <?php if ($isCard): // Card already defined ?>
          
            <p class="fd-text-form-horizontal font-110">
              <?="{$card['name']} ({$card['code']})"?>
            </p>
          
          <?php else: // Search autocomplete ?>
            
            <?=component('form/input-clear', [
              'classes' => 'input-lg text-monospace',
              'name' => 'card-name',
              'id' => 'card-name-autocomplete',
              'placeholder' => 'Card name here..',
              'autofocus' => !$isCard,
              'disabled' => $isCard,
              'value' => $isCard ? "{$card['name']} ({$card['code']})" : null
            ])?>

          <?php endif; ?>
        </div>

      </div>

      <!-- Card image -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Image</label>
        <div class="col-sm-10" id="card-image">
          <?php if ($isCard): ?>

            <!-- Image -->
            <a
              href="<?=fd_asset($card['image_path'])?>"
              data-lightbox="cards"
              data-title="<?="{$card['name']} ({$card['code']})"?>"
            >
              <span class="fd-zoomable-lg">
                <img
                  src="<?=fd_asset($card['image_path'])?>"
                  width="200px"
                  class="img-responsive"
                >
              </span>
            </a>

            <!-- Link -->
            <br>
            <a
              href="<?=url('card/'.urlencode($card['code']))?>"
              class="btn btn-link"
            >
              <i class="fa fa-external-link"></i>
              Go to card page
            </a>

          <?php else: ?>

            Image will be shown here

          <?php endif; ?>
        </div>
      </div>

      <!-- Ruling is errata -->
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

        <label class="col-sm-2 control-label">
          Date
        </label>

        <div class="col-sm-10">
          <input
            type="text"
            class="form-control text-monospace font-110"
            name="ruling-date"
            placeholder="YYYY-MM-DD (Empty if today).."
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
          ></textarea>
        </div>
      </div>

      <!-- Submit -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button
            type="submit"
            class="btn btn-lg fd-btn-primary"
          >
            <i class="fa fa-plus"></i>
            Create
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

<?=include_view('pages/admin/rulings/includes/conventions')?>
