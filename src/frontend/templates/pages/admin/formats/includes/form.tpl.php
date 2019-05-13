<?php

// VARIABLES
// $clusters
// $nextAvailableId?
// $item?
// $prev?

// INPUTS
// id
// name
// code
// desc
// is-default
// clusters

$isItem = isset($item);
$isPrev = isset($prev);
$url = $isItem ? "formats/update/{$item->id}" : "formats/create";

?>
<form
  action="<?=fd_url($url)?>"
  method="post"
  class="form-horizontal"
>
  <?=fd_csrf_token()?>

  <!-- ID ================================================================= -->
  <div class="form-group">
    <label class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
      <input
        type="number"
        name="id"
        class="form-control font-110"
        placeholder="Format ID (read below)..."
        value="<?php
          if ($isPrev) echo intval($prev['id']);
          elseif ($isItem) echo intval($item->id);
          else echo $nextAvailableId ?? null;
        ?>"
        required
      >
      <?php if (isset($nextAvailableId)): ?>
        <div class="well">
          Recommended ID (next sequential available):
          <strong><?=$nextAvailableId?></strong>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Name =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="name"
        class="form-control font-110"
        placeholder="Format name Ex.: Grimm Cluster..."
        value="<?php
          if ($isPrev) echo $prev['name'];
          elseif ($isItem) echo $item->name;
          else echo null;
        ?>"
        required
      >
    </div>
  </div>

  <!-- Code =============================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Code</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="code"
        class="form-control font-110"
        placeholder="Format code Ex.: c-gri (lowercase)..."
        value="<?php
          if ($isPrev) echo $prev['code'];
          elseif ($isItem) echo $item->code;
          else echo null;
        ?>"
        required
      >
      <div class="well">
        Must be exactly <strong>5</strong> characters long.
      </div>
    </div>
  </div>

  <!-- Description ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">
      Description
      <br>
      <small>(Optional)</small>
    </label>
    <div class="col-sm-10">
      <textarea
        name="desc"
        class="form-control font-110"
        placeholder="Format description...""
      ><?php
        if ($isPrev) echo $prev['code'];
        elseif ($isItem) echo $item->desc;
        else echo null;
      ?></textarea>
    </div>
  </div>

  <!-- Default? =========================================================== -->
  <?php
    (
      ($isPrev && isset($prev['is-spoiler'])) ||
      ($isItem && $item->is_default)
    )
      ? $checked = 'checked="true"'
      : $checked = ''
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Default</label>
    <div class="col-sm-10 font-110">
      <div class="checkbox">
        <label for="is-default">
          <input
            type="checkbox"
            name="is-default"
            id="is-default"
            value="1"
            <?=$checked?>
          >
          <span>
            It's the <strong class="text-danger">DEFAULT</strong> format
          </span>
        </label>
      </div>
    </div>
  </div>

  <!-- Clusters =========================================================== -->
  <?php
    // TODO
    $state = [];
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Clusters</label>
    <div class="col-sm-10">
      <?=fd_component('form/button-checkboxes', [
        'name' => 'clusters',
        'items' => $clusters,
        'state' => $state,
        'css' => [
          'button' => ['font-120', 'mv-50', 'fd-btn-default', 'display-block']
        ],
      ])?>
    </div>
  </div>

  <hr class="fd-hr">

  <!-- Submit ============================================================= -->
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg fd-btn-primary">
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
