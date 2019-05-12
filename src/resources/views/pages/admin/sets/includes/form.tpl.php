<?php

// VARIABLES
// $clusters
// $item
// $prev

// INPUTS
// cluster-id
// id
// name
// code
// count
// release-date
// is-spoiler

$isItem = isset($item);
$isPrev = isset($prev);
$url = $isItem ? 'sets/update/'.$item['id'] : 'sets/create';
$nextId = \App\Models\GameSet::nextAvailableId();

?>
<form
  action="<?=url($url)?>"
  method="post"
  class="form-horizontal"
>
  <?=fd_csrf_token()?>

  <!-- Cluster ============================================================ -->
  <?php
    if ($isPrev) $itemCluster = $prev['cluster-id'];
    elseif ($isItem) $itemCluster = $item['clusters_id'];
    else $itemCluster = null;
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Cluster</label>
    <div class="col-sm-10">
      <select name="cluster-id" class="form-control" required>
        <option value="0">Choose a cluster...</option>
        <?php foreach ($clusters as $cluster):
          ($itemCluster === $cluster['id'])
            ? $selected = ' selected'
            : $selected = '';
        ?>
          <option value="<?=$cluster['id']?>"<?=$selected?>>
            <?=$cluster['name'].' ('.strtoupper($cluster['code']).')'?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- ID ================================================================= -->
  <div class="form-group">
    <label class="col-sm-2 control-label">
      ID<br>
      <small><em><a href="#rules">Rules</a></em></small>
    </label>
    <div class="col-sm-10">
      <input
        type="number"
        name="id"
        class="form-control font-110"
        placeholder="Set ID (read below)..."
        value="<?php
          if ($isPrev) echo intval($prev['id']);
          elseif ($isItem) echo intval($item['id']);
          else echo null;
        ?>"
        required
      >
      <div class="well">
        Recommended ID (next sequential available):
        <strong><?=$nextId?></strong>
      </div>
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
        placeholder="Set name Ex.: The Crimson Moon Fairy Tale..."
        value="<?php
          if ($isPrev) echo $prev['name'];
          elseif ($isItem) echo $item['name'];
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
        placeholder="Set code Ex.: cmf (lowercase)..."
        value="<?php
          if ($isPrev) echo $prev['code'];
          elseif ($isItem) echo $item['code'];
          else echo null;
        ?>"
        required
      >
    </div>
  </div>

  <!-- Count ============================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">
      Count<br>
      <small><em>(Max 255)</em></small>
    </label>
    <div class="col-sm-10">
      <input
        type="number"
        name="count"
        class="form-control font-110"
        placeholder="Set count for cards Ex.: 105..."
        value="<?php
          if ($isPrev) echo intval($prev['count']);
          elseif ($isItem) echo $item['count'];
          else echo null;
        ?>"
        required
      >
    </div>
  </div>

  <!-- Release date ======================================================= -->
  <div class="form-group">
    <label class="col-sm-2 control-label">
      Release date<br>
      <small><em>(Optional)</em></small>
    </label>
    <div class="col-sm-10">
      <input
        type="text"
        name="release-date"
        class="form-control font-110"
        placeholder="Set release date (YYYY-MM-DD) Ex.: 2018-11-26..."
        value="<?php
          if ($isPrev) echo $prev['release-date'];
          elseif ($isItem) echo $item['date_release'];
          else echo null;
        ?>"
      >
    </div>
  </div>

  <!-- Spoiler? =========================================================== -->
  <?php
    (
      ($isPrev && isset($prev['is-spoiler'])) ||
      ($isItem && $item['is_spoiler'])
    )
      ? $checked = 'checked="true"'
      : $checked = ''
  ?>
  <div class="form-group">
    <label class="col-sm-2 control-label">Spoiler?</label>
    <div class="col-sm-10 font-110">
      <div class="checkbox">
        <label for="is-spoiler">
          <input
            type="checkbox"
            name="is-spoiler"
            id="is-spoiler"
            value="1"
            <?=$checked?>
          >
          <span class="text-danger text-bold">
            It's a spoiler set
          </span>
        </label>
      </div>
    </div>
  </div>

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
