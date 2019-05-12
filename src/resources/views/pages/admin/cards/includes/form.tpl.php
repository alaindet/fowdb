<?php

// VARIABLES
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
$isPrev = isset($prev);
$url = $isCard ? 'cards/update/'.$card['id'] : 'cards/create';

// Lookup data
$lookup = (App\Services\Lookup\Lookup::getInstance())->getAll();
$narps     = &$lookup['narps']['id2name'];
$clusters  = &$lookup['clusters']['list'];
$setMap    = &$lookup['sets']['id2code'];
$rarities  = &$lookup['rarities']['code2name'];
$backsides = &$lookup['backsides']['id2name'];
$types     = &$lookup['types']['name2bit'];

// Further process
$backsides = array_merge(['0' => '(Basic)'], $backsides);
$types = \App\Utils\Arrays::map($types, function ($bitpos) {
  return 1 << $bitpos;
});
$bitmask = new \App\Utils\Bitmask();

// Attributes custom lookup data ----------------------------------------------
$attrDisplay = &$lookup['attributes']['display'];
$map = &$lookup['attributes']['name2bit'];
$attributes = array_reduce(
  $attrDisplay,
  function ($result, $attr) use (&$map, &$bitmask) {
    $result[$attr] = $bitmask->getBitValue($map[$attr]);
    return $result;
  },
  []
);

// Manipulate the card resource
if ($isCard) {
  $card['attribute_bit'] = intval($card['attribute_bit']);
  if ($card['attribute_bit'] === 0) $card['attribute_bit'] = 32; // HACK!
  if ($card['free_cost'] < 0) {
    $card['free_cost'] = str_repeat('x', -1 * $card['free_cost']);
  }  
}

?>
<form
  action="<?=url($url)?>"
  method="post"
  enctype="multipart/form-data"
  class="form-horizontal"
>
  <?=fd_csrf_token()?>

  <!-- Image ============================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-10">
      <?php if ($isCard): ?>
        
        <a
          href="<?=fd_asset($card['image_path'])?>"
          data-lightbox="cards"
          data-title="<?="{$card['name']} ({$card['code']})"?>"
        >
          <span class="fd-zoomable-lg">
            <img
              src="<?=fd_asset($card['thumb_path'])?>"
              width="200px"
            >
          </span>
        </a>

      <?php endif; ?>
      <input type="file" name="image">
    </div>
  </div>

  <!-- NARP =============================================================== -->
  <?php
    if ($isPrev) $cardNarp = intval($prev['narp']);
    elseif ($isCard) $cardNarp = $card['narp'];
    else $cardNarp = null;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">NARP</label>
    <div class="col-sm-10">
      <select name="narp" class="form-control" required>
        <?php foreach($narps as $key => $label):
          ($cardNarp === $key)
            ? $checked = 'selected'
            : $checked = '';
        ?>
          <option
            value="<?=$key?>"
            <?=$checked?>
          >
            <?=$label?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Set ================================================================ -->
  <?php
    $cardSet = null;
    if ($isPrev) $cardSet = $prev['set'];
    elseif ($isCard) $cardSet = $setMap[$card['sets_id']];
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Set</label>
    <div class="col-sm-10">
      <select name="set" class="form-control" required>
        <option value="0">Choose a set...</option>
        <?php foreach($clusters as $clusterCode => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
          <?php foreach ($cluster['sets'] as $setCode => $setName):
            ($cardSet == $setCode)
              ? $checked = 'selected'
              : $checked = '';
          ?>
            <option
              value="<?=$setCode?>"
              <?=$checked?>
            >
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
    <label class="col-sm-2 control-label">Number</label>
    <div class="col-sm-10">
      <input
        type="number"
        name="number"
        value="<?php
          if ($isPrev) echo intval($prev['number']);
          elseif ($isCard) echo $card['num'];
          else echo null;
        ?>"
        placeholder="Card number..."
        class="form-control"
        required
      >
    </div>
  </div>

  <!-- Rarity ============================================================= -->
  <?php
    $cardRarity = $isPrev ? $prev['rarity'] : $isCard ? $card['rarity'] : null;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Rarity</label>
    <div class="col-sm-10">
      <select name="rarity" class="form-control">
        <option value="0">(None)</option>
        <?php foreach ($rarities as $code => $name):
          ($cardRarity === $code)
            ? $checked = 'selected'
            : $checked = '';
        ?>
          <option
            value="<?=$code?>"
            <?=$checked?>
          >
            <?=strtoupper($code)?> - <?=$name?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Attribute ========================================================== -->
  <?php
    $cardAttributes = 0;
    if ($isPrev) {
      $cardAttributes = $bitmask
        ->setMask(0)
        ->addBitValues($prev['attribute'])
        ->getMask();
    } elseif ($isCard) {
      $cardAttributes = intval($card['attribute_bit']);
    }
  ?>
  <div class="form-group">
    <label for="attribute" class="col-sm-2 control-label">Attribute</label>
    <div class="col-sm-10">
      <div
        class="btn-group fd-btn-group --separate fd-grid-items"
        data-toggle="buttons"
      >
        <?php foreach ($attributes as $name => $bitval):
          (($cardAttributes & $bitval) === $bitval)
            ? [$active, $checked] = [' active', 'checked']
            : [$active, $checked] = ['', ''];
        ?>
          <label class="btn fd-btn-default<?=$active?>">
            <input
              type="checkbox"
              name="attribute[]"
              value="<?=$bitval?>"
              <?=$checked?>
            >
            <img
              src="<?=fd_asset('images/icons/blank.gif')?>"
              class="fd-icon-<?=$lookup['attributes']['name2code'][$name]?> --bigger"
              alt="<?=$name?>"
            >
          </label>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Back side ========================================================== -->
  <?php
    if ($isPrev) $cardBackSide = intval($prev['back-side']);
    elseif ($isCard) $cardBackSide = $card['back_side'];
    else $cardBackSide = 0;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Back side</label>
    <div class="col-sm-10">
      <div class="btn-group" data-toggle="buttons">
        <?php foreach ($backsides as $backsideId => $backsideName):
          ($cardBackSide === $backsideId)
            ? [$checked, $active] = ['checked', ' active']
            : [$checked, $active] = ['', ''];
        ?>
          <label class="btn btn-default<?=$active?>">
            <input
              type="radio"
              name="back-side"
              value="<?=$backsideId?>"
              required
              <?=$checked?>
            >
            <span class="pointer"><?=$backsideName?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Type =============================================================== -->
  <?php
    $cardType = 0;
    if ($isPrev) {
      $cardType = $bitmask->setMask(0)->addBitValues($prev['type'])->getMask();
    } elseif ($isCard) {
      $cardType = intval($card['type_bit']);
    }
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Type</label>
    <div class="col-sm-10">

      <div class="btg-group" data-toggle="buttons">
        <?php foreach ($types as $name => $bitval):
          (($cardType & $bitval) === $bitval)
            ? [$active, $checked] = [' active', 'checked=true']
            : [$active, $checked] = ['', ''];
        ?>
          <label
            class="btn font-105 m-25 fd-btn-default<?=$active?>"
          >
            <input
              type="checkbox"
              name="type[]"
              value="<?=$bitval?>"
              <?=$checked?>
            >
            <span class="pointer"><?=$name?></span>
          </label>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

  <!-- Cost =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Cost</label>
    <div class="col-xs-12 col-sm-10">
      <div class="row">

        <!-- Attribute cost =============================================== -->
        <div class="col-sm-6">
          <span class="form-label">Attribute cost</span>
          <input 
            type="text"
            name="attribute-cost"
            value="<?php
              if ($isPrev) echo $prev['attribute-cost'];
              elseif ($isCard) echo $card['attribute_cost'] ?? '-1';
              else echo null;
            ?>"
            placeholder="Attribute cost (w,r,u,g,b)..."
            class="form-control"
          >
        </div>

        <!-- Free cost ==================================================== -->
        <div class="col-sm-6">
          <span class="form-label">
            Free cost
            <em class="font-90">(Also "x", "xx" etc.)</em>
          </span>
          <input
            type="text"
            name="free-cost"
            value="<?php
              if ($isPrev) echo intval($prev['free-cost']);
              elseif ($isCard) echo $card['free_cost'] ?? '-1';
              else echo null;
            ?>"
            placeholder="Free cost..."
            class="form-control"
          >
        </div>

      </div>
    </div>
  </div>

  <!-- Divinity =========================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Divinity cost</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="divinity-cost"
        value="<?php
          if ($isPrev) echo intval($prev['divinity-cost']);
          elseif ($isCard) echo $card['divinity'] ?? '-1';
          else echo null;
        ?>"
        placeholder="Divinity (-1 to delete existing value)..."
        class="form-control"
      >
    </div>
  </div>

  <!-- ATK/DEF ============================================================ -->
  <div class="form-group">
    <label class="col-sm-2 control-label">ATK/DEF</label>
    <div class="col-xs-12 col-sm-10">
      <div class="row">

        <!-- ATK -->
        <div class="col-sm-6">
          <span class="form-label">ATK</span>
          <input
            type="number"
            name="atk"
            value="<?php
              if ($isPrev) echo intval($prev['atk']);
              elseif ($isCard) echo $card['atk'] ?? '-1';
              else echo null;
            ?>"
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
            value="<?php
              if ($isPrev) echo intval($prev['def']);
              elseif ($isCard) echo $card['def'] ?? '-1';
              else echo null;
            ?>"
            placeholder="DEF..."
            class="form-control"
          >
        </div>

      </div>
    </div>
  </div>

  <!-- Code =============================================================== -->
  <div class="form-group">
    <label for="code" class="col-sm-2 control-label">Code</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="code"
        value="<?php
          if ($isPrev) echo $prev['code'];
          elseif ($isCard) echo $card['code'] ?? '-1';
          else echo null;
        ?>"
        placeholder="Code (Read below)..."
        class="form-control"
      >
      <div class="well well-sm">
        <ul class="fd-list">
          <li>
            Leave <strong>empty</strong> for automatic generation (recommended)
            or enter a custom code
          </li>
          <li>
            Automatic code pattern:
            <code>{SETCODE}{dash}{NUMBER}{RARITY}</code>,
            ex.: NDR-001U
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Name =============================================================== -->
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="name"
        value="<?php
          if ($isPrev) echo $prev['name'];
          elseif ($isCard) echo $card['name'];
          else echo null;
        ?>"
        placeholder="Name..."
        class="form-control"
        required
      >
    </div>
  </div>

  <!-- Race/Trait ========================================================= -->
  <div class="form-group">
    <label for="race" class="col-sm-2 control-label">Race/Trait</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="race"
        value="<?php
          if ($isPrev) echo $prev['race'] ?? '-1';
          elseif ($isCard) echo $card['race'] ?? '-1';
          else echo null;
        ?>"
        class="form-control"
        placeholder="Race/Trait.."
      >
    </div>
  </div>

  <!-- Text =============================================================== -->
  <div class="form-group">
    <label for="text" class="col-sm-2 control-label">Text</label>
    <div class="col-sm-10">
      <textarea
        name="text"
        class="form-control text-monospace font-120"
        rows="6"
        placeholder="Text..."
      ><?php
        if ($isPrev) echo trim(fd_escape($prev['text'] ?? '-1'));
        elseif ($isCard) echo trim(fd_escape($card['text'] ?? '-1'));
        else echo null;
      ?></textarea>
    </div>
  </div>

  <!-- Flavor Text ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Flavor text</label>
    <div class="col-sm-10">
      <textarea
        name="flavor-text"
        class="form-control"
        rows="3"
        placeholder="Flavor text..."
      ><?php
        if ($isPrev) echo trim(fd_escape($prev['flavor-text'] ?? '-1'));
        elseif ($isCard) echo trim(fd_escape($card['flavor_text'] ?? '-1'));
        else echo null;
      ?></textarea>
    </div>
  </div>

  <!-- Artist name ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Artist name</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="artist-name"
        value="<?php
          if ($isPrev) echo $prev['artist-name'] ?? '-1';
          elseif ($isCard) echo $card['artist_name'] ?? '-1';
          else echo null;
        ?>"
        placeholder="Artist name..."
        class="form-control"
        id="artist-autocomplete"
      >
    </div>
  </div>

  <!-- Submit ============================================================= -->
  <div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
      <button type="submit" class="btn fd-btn-primary btn-lg">
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
