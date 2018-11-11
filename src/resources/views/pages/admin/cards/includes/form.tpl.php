<?php
// VARIABLES
// $action
// $id (required for $action = 'update')
// $card (required for $action = 'update')

// INPUT
// image
// narp
// set
// number
// rarity
// attribute
// back-side
// type
// attribute-cost
// free-cost
// divinity-cost
// atk
// def
// code
// name
// race
// text
// flavor-text
// artist-name

$isCard = isset($card);
$url = "cards/{$action}". (isset($id) ? "/{$id}" : "");

// Lookup data
$lookup = (App\Services\Lookup\Lookup::getInstance())->getAll();
$narps = &$lookup['narps']['id2name'];
$clusters = &$lookup['clusters'];
$setMap = &$lookup['sets']['id2code'];
$rarities = &$lookup['rarities']['code2name'];
$attributes = &$lookup['attributes']['display'];
$backsides = &$lookup['backsides']['id2name'];
$types = &$lookup['types']['display'];

// Further process
$backsides = array_merge(['0' => '(Basic)'], $backsides);
?>
<form
  action="<?=url($url)?>"
  method="post"
  enctype="multipart/form-data"
  class="form-horizontal"
>
  <?=csrf_token()?>

  <!-- Image ============================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Image</label>
    <div class="col-sm-10">
      <?php if ($isCard): ?>
        <img src="<?=asset($card['thumb_path'])?>">
      <?php endif; ?>
      <input type="file" name="image">
    </div>
  </div>

  <!-- NARP =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2">NARP</label>
    <div class="col-sm-10">
      <select name="narp" class="form-control">
        <?php foreach($narps as $key => $label): ?>
          <?php // STICKY
            ($isCard && $card['narp'] === $key)
              ? $checked = ' selected'
              : $checked = '';
          ?>
          <option value="<?=$key?>"<?=$checked?>><?=$label?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Set ================================================================ -->
  <div class="form-group">
    <label class="col-sm-2">Set</label>
    <div class="col-sm-10">
      <select name="set" class="form-control">
        <option value="0">Choose a set..</option>
        <?php foreach($clusters as $clusterCode => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
          <?php foreach ($cluster['sets'] as $setCode => $setName): ?>
            <?php // STICKY
              ($isCard && $setMap[$card['sets_id']] === $setCode)
                ? $checked = ' selected'
                : $checked = '';
            ?>
            <option value="<?=$setCode?>"<?=$checked?>>
              <?=strtoupper($setCode).' - '.$setName?>
            </option>
          <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Number ============================================================= -->
  <div class="form-group">
    <label class="col-sm-2">Number</label>
    <div class="col-sm-10">
      <input
        type="number"
        name="number"
        value="<?=$card['num'] ?? null?>"
        placeholder="Card number..."
        class="form-control">
    </div>
  </div>

  <!-- Rarity ============================================================= -->
  <div class="form-group">
    <label class="col-sm-2">Rarity</label>
    <div class="col-sm-10">
      <select name="rarity" class="form-control">
        
        <!-- Default -->
        <option value="0">(None)</option>

        <?php foreach ($rarities as $code => $name): ?>
          <?php // STICKY
            ($isCard && $card['rarity'] === $code)
              ? $checked = ' selected'
              : $checked = '';
          ?>
          <option value="<?=$code?>"<?=$checked?>>
            <?=strtoupper($code)?> - <?=$name?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Attribute ========================================================== -->
  <?php $cardAttributes = $isCard ? explode('/', $card['attr']) : []; ?>
  <div class="form-group">
    <label for="attribute" class="col-sm-2">Attribute</label>
    <div class="col-sm-10">
      <div
        class="btn-group fd-btn-group --separate"
        data-toggle="buttons"
      >
        <?php foreach ($attributes as $code => $name):
          // Sticky values
          ($isCard && in_array($code, $cardAttributes))
            ? [$active, $checked] = [' active', 'checked']
            : [$active, $checked] = ['', ''];
        ?>
          <label class="btn fd-btn-default<?=$active?>">
            <input
              type="checkbox"
              name="attribute[]"
              value="<?=$code?>"
              <?=$checked?>
            >
            <img
              src="<?=asset('images/icons/blank.gif')?>"
              class="fd-icon-<?=$code?> --bigger"
              alt="<?=$name?>"
            >
          </label>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Back side ========================================================== -->
  <?php $cardBackside = $card['back_side'] ?? '0'; ?>
  <div class="form-group">
    <label class="col-sm-2">Back side</label>
    <div class="col-sm-10">
      <div class="btn-group" data-toggle="buttons">
        <?php foreach ($backsides as $backsideId => $backsideName):
          ($cardBackside == $backsideId)
              ? [$checked, $active] = ['checked', ' active']
              : [$checked, $active] = ['', ''];
        ?>
          <label class="btn btn-default<?=$active?>">
            <input
              type="radio"
              name="back-side"
              value="<?=$backsideId?>"
              <?=$checked?>
            >
            <span class="pointer"><?=$backsideName?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Type =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Type</label>
    <div class="col-sm-10">
      <select name="type" class="form-control">
        <option value="0">(None)</option>
        <?php foreach($types as $type):
          ($isCard && $card['type'] === $type)
            ? $checked = 'selected'
            : $checked = '';
        ?>
          <option
            value="<?=$type?>"
            <?=$checked?>
          >
            <?=$type?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Cost =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Cost</label>
    <div class="col-xs-12 col-sm-10">
      <div class="row">

        <!-- Attribute cost -->
        <div class="col-sm-6">
          <span class="form-label">Attribute cost</span>
          <input 
            type="text"
            name="attribute-cost"
            value="<?=$card['attribute_cost'] ?? ''?>"
            placeholder="Attribute cost (w,r,u,g,b)..."
            class="form-control"
          >
        </div>

        <!-- Free cost -->
        <div class="col-sm-6">
          <span class="form-label">
            Free cost
            <em class="font-90">(Also "x", "xx" etc.)</em>
          </span>
          <input
            type="text"
            name="free-cost"
            value="<?=$card['free_cost'] ?? null?>"
            placeholder="Free cost..."
            class="form-control"
          >
        </div>

      </div>
    </div>
  </div>

  <!-- Divinity =========================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Divinity cost</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="divinity-cost"
        value="<?=$card['divinity'] ?? null?>"
        placeholder="Divinity (-1 to delete existing value)..."
        class="form-control"
      >
    </div>
  </div>

  <!-- ATK/DEF ============================================================ -->
  <div class="form-group">
    <label class="col-sm-2">ATK/DEF</label>
    <div class="col-xs-12 col-sm-10">
      <div class="row">

        <!-- ATK -->
        <div class="col-sm-6">
          <span class="form-label">ATK</span>
          <input
            type="number"
            name="atk"
            value="<?=$card['atk'] ?? null?>"
            placeholder="ATK..."
            class="form-control"
          >
        </div>

        <!-- DEF -->
        <div class="col-sm-6">
          <span class="form-label">DEF</span>
          <input
            type="number"
            name="def"
            value="<?=$card['def'] ?? null?>"
            placeholder="DEF..."
            class="form-control"
          >
        </div>

      </div>
    </div>
  </div>

  <!-- Code =============================================================== -->
  <div class="form-group">
    <label for="code" class="col-sm-2">Code</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="code"
        value="<?=$card['code'] ?? null?>"
        placeholder="Code (Read below)..."
        class="form-control"
      >
      <div class="well well-sm">
        <ul class="fd-list">
          <li>
            Leave empty for automatic generation (recommended) or enter a custom code
          </li>
          <li>
            Automatic code pattern: <code>SETCODE{dash}NUMBER{space}RARITY</code>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Name =============================================================== -->
  <div class="form-group">
    <label for="name" class="col-sm-2">Name</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="name"
        value="<?=$card['name'] ?? null?>"
        placeholder="Name..."
        class="form-control"
      >
    </div>
  </div>

  <!-- Race/Trait ========================================================= -->
  <div class="form-group">
    <label for="race" class="col-sm-2">Race/Trait</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="race"
        value="<?=$card['race'] ?? null?>"
        class="form-control"
        placeholder="Race/Trait.."
      >
    </div>
  </div>

  <!-- Text =============================================================== -->
  <div class="form-group">
    <label for="text" class="col-sm-2">Text</label>
    <div class="col-sm-10">
      <textarea
        name="text"
        class="form-control text-monospace font-120"
        rows="6"
        placeholder="Text..."
      ><?=$isCard ? escape($card['text']) : null?></textarea>
    </div>
  </div>

  <!-- Flavor Text ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Flavor text</label>
    <div class="col-sm-10">
      <textarea
        name="flavor-text"
        class="form-control"
        rows="3"
        placeholder="Flavor text..."
      ><?=$card['flavor_text'] ?? null?></textarea>
    </div>
  </div>

  <!-- Artist name ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2">Artist name</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="artist-name"
        value="<?=$card['artist_name'] ?? null?>"
        placeholder="Artist name..."
        class="form-control"
      >
    </div>
  </div>

  <!-- Submit ============================================================= -->
  <div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
      <button type="submit" class="btn btn-primary btn-lg">
        <?php if ($isCard): ?>

          <i class="fa fa-pencil"></i>
          Update card

        <?php else: ?>

          <i class="fa fa-plus"></i>
          Create card

        <?php endif; ?>
      </button>
    </div>
  </div>
  
</form>
