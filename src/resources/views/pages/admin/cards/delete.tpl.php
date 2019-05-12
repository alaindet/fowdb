<?php

// VARIABLES
// $card

$blank = fd_asset("images/icons/blank.gif");

// Cost -----------------------------------------------------------------------
$displayCost = "";
if (isset($card["attribute_cost"])) {
  foreach (str_split($card["attribute_cost"]) as $attribute) {
    $displayCost .= "<img src=\"{$blank}\" class=\"fd-icon-{$attribute}\">";
  }
}
if (isset($card["free_cost"])) {
  $displayCost .= $card["free_cost"];
}
if ($displayCost === "") {
  $displayCost = "(No cost)";
}

// Attribute ------------------------------------------------------------------
if (isset($card["attribute"])) {
  $displayAttribute = [];
  $map = fd_lookup("attributes.code2name");
  foreach (explode("/", $card["attribute"]) as $attribute) {
    $label = $map[$attribute];
    $displayAttribute[] = (
      "<img ".
        "src=\"{$blank}\" ".
        "class=\"fd-icon-{$attribute}\"> {$label}"
    );
  }
  $displayAttribute = implode(", ", $displayAttribute);
} else {
  $displayAttribute = "(No attribute)";
}

// Type -----------------------------------------------------------------------
$typeLabels = \App\Views\Card\Card::buildTypeLabels($card["type_bit"]);
$cardType = implode(" / ", $typeLabels);

// ATK / DEF ------------------------------------------------------------------
if (isset($card["atk"]) && isset($card["def"])) {
  $displayBattleValues = "{$card["atk"]} / {$card["def"]}";
} else {
  $displayBattleValues = "(No battle values)";
}

// CSN ------------------------------------------------------------------------
$displayCsn = (
  "<li>".fd_lookup("clusters.id2name.id".$card["clusters_id"])."</li>".
  "<li>".fd_lookup("sets.id2name.id".$card["sets_id"])."</li>".
  "<li>".str_pad($card["num"], 3, "0", STR_PAD_LEFT)."</li>"
);

?>
<div class="page-header">
  <h1>Delete card</h1>
  <?=fd_component("breadcrumb", [
    "Admin" => url("profile"),
    "Cards" => url("cards/manage"),
    "(&larr; Card page)" => url("card/".urlencode($card["code"])),
    "Delete" => "#"
  ])?>
</div>

<!-- Form -->
<div class="fd-box --more-margin">
  <form
    action="<?=url("cards/delete/{$card["id"]}")?>"
    method="post"
    class="form-horizontal"
  >
    <?=fd_csrf_token()?>

    <!-- Name -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?="{$card["name"]} ({$card["code"]})"?>
        </p>
      </div>
    </div>

    <!-- Image -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Image</label>
      <div class="col-sm-10" id="card-image">
        <a
          href="<?=fd_asset($card["image_path"])?>"
          data-lightbox="cards"
        >
          <span class="fd-zoomable-lg">
            <img
              src="<?=fd_asset($card["image_path"])?>"
              width="200px"
            >
          </span>
        </a>
      </div>
    </div>

    <!-- Attribute -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Attribute</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$displayAttribute?>
        </p>
      </div>
    </div>

    <!-- Type -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Type</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$cardType?>
        </p>
      </div>
    </div>

    <!-- Cost -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Cost</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$displayCost?>
        </p>
      </div>
    </div>

    <!-- ATK / DEF -->
    <div class="form-group">
      <label class="col-sm-2 control-label">ATK / DEF</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$displayBattleValues?>
        </p>
      </div>
    </div>

    <!-- Race -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Race</label>
      <div class="col-sm-10 font-110">
        <p class="fd-text-form-horizontal font-110">
          <?=$card["race"] ?? "(No race)"?>
        </p>
      </div>
    </div>

    <!-- CSN -->
    <div class="form-group">
      <label class="col-sm-2 control-label">CSN</label>
      <div class="col-sm-10">
        <ul class="fd-list font-110">
          <?=$displayCsn?>
        </ul>
      </div>
    </div>

    <!-- Text -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Text</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$card["text"]?>
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
