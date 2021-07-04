<?php

// VARIABLES
// $card
// $prev
// $item

// INPUT
// card-id
// format-id
// deck
// copies

$isPrev = isset($prev);
$isItem = isset($item);
$isCard = isset($card); // This is just auxiliary data!
$url = 'restrictions/' . ($isItem ? 'update/'.$item['id'] : 'create');

$formats = lookup('formats.id2name');
$decks = \App\Models\PlayRestriction::$decksLabels;

?>
<form
  action="<?=url($url)?>"
  method="post"
  class="form-horizontal"
  id="validate-this-form"
>
  <?=csrf_token()?>

  <!-- Card ID ============================================================ -->
  <input
    type="hidden"
    name="card-id"
    id="card-id"
    <?=$isCard ? "value=\"{$card['id']}\"" : ""?>
  >

  <!-- Card Name ========================================================== -->
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

  <!-- Card image ========================================================= -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-10" id="card-image">
      <?php if ($isCard): ?>

        <!-- Image -->
        <a
          href="<?=asset($card['image'])?>"
          data-lightbox="cards"
          data-title="<?="{$card['name']} ({$card['code']})"?>"
        >
          <span class="fd-zoomable-lg">
          <img
            src="<?=asset($card['image'])?>"
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

  <!-- Format ============================================================= -->
  <?php
    if ($isPrev) $sticky = intval($prev['format-id']);
    elseif ($isItem) $sticky = intval($item['format_id']);
    else $sticky = null;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Format</label>
    <div class="col-sm-10">
      <select name="format-id" class="form-control" required>
        <?php foreach ($formats as $id => $name):
          ($sticky === $id)
            ? $checked = 'selected'
            : $checked = '';
        ?>
          <option
            value="<?=$id?>"
            <?=$checked?>
          >
            <?=$name?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Deck =============================================================== -->
  <?php
    if ($isPrev) $sticky = intval($prev['deck']);
    elseif ($isItem) $sticky = intval($item['deck']);
    else $sticky = null;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Deck</label>
    <div class="col-sm-10">
      <select name="deck" class="form-control" required>
        <?php foreach ($decks as $id => $name):
          ($sticky === $id)
          ? $checked = 'selected'
          : $checked = '';
        ?>
          <option
            value="<?=$id?>"
            <?=$checked?>
          >
            <?=$name?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Copies ============================================================= -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Copies</label>
    <div class="col-sm-10">
      <input
        type="number"
        name="copies"
        required
        class="form-control"
        placeholder="Copies allowed (0 = banned, 1 = limited, etc.)..."
        value="<?php
          if ($isPrev) echo intval($prev['copies']);
          elseif ($isItem) echo $item['copies'];
          else echo null;
        ?>"
      >
    </div>
  </div>

  <!-- Submit ============================================================= -->
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button
        type="submit"
        class="btn btn-lg fd-btn-primary"
      >
        <?php if ($isItem): ?>

          <i class="fa fa-pencil"></i>
          Update

        <?php else: ?>

          <i class="fa fa-plus"></i>
          Create

        <?php endif; ?>
      </button>
    </div>
  </div>

</form>
