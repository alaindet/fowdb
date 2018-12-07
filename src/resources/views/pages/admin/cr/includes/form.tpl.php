<?php

// VARIABLES
// $prev
// $item

// INPUT
// txt-file
// version
// date-validity
// is-default

$isPrev = isset($prev);
$isItem = isset($item);
$url = 'cr/' . ($isItem ? 'update/'.$item['id'] : 'create');

?>
<form
  action="<?=url($url)?>"
  method="post"
  enctype="multipart/form-data"
  class="form-horizontal"
>
  <?=csrf_token()?>

  <?php if ($isItem): ?>
    <!-- ID ============================================================= -->
    <div class="form-group text-muted">
      <label class="col-sm-2 control-label">ID</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['id']?>
        </p>
      </div>
    </div>

    <!-- Created on ===================================================== -->
    <div class="form-group text-muted">
      <label class="col-sm-2 control-label">Created on</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['date_created']?>
        </p>
      </div>
    </div>
  <?php endif; ?>

  <!-- File upload ======================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">File (.txt)</label>
    <div class="col-sm-10">

      <!-- Upload .txt file -->
      <input type="file" name="txt-file">

      <!-- View existing .txt file -->
      <?php if ($isItem): ?>
        <a
          href="<?=url('cr/file/'.$item['id'])?>"
          class="btn btn-lg fd-btn-default mv-100"
        >
          Download source file &rarr; <?=$item['version']?>.txt
        </a>
      <?php endif; ?>

      <!-- Description -->
      <br>
      <div class="well">
        Input file must be a valid .txt with a specific format. Check existing comprehensive rules source files for reference.
      </div>

    </div>
  </div>

  <!-- Version ============================================================ -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Version</label>
    <div class="col-sm-10">
      
      <!-- Input -->
      <input
        type="text"
        name="version"
        required
        class="form-control text-monospace font-110"
        placeholder="CR version... Ex.: 6.3a, 8.01"
        value="<?php
          if ($isPrev) echo $prev['version'];
          elseif ($isItem) echo $item['version'];
          else echo null;
        ?>"
      >

      <!-- Description -->
      <br>
      <div class="well">
        The version must match <code>{NUMBERS}{DOT}{NUMBERS}{?LETTER}</code>, where <code>{NUMBERS}</code> can be one or more numbers and <code>{?LETTER}</code> is an optional letter. Examples: <code>6.3a</code>, <code>8.01</code>
      </div>

    </div>
  </div>

  <!-- Date validity ====================================================== -->
  <div class="form-group">
    <label class="col-sm-2 control-label">Valid since</label>
    <div class="col-sm-10">
      <input
        type="text"
        name="date-validity"
        required
        class="form-control text-monospace font-110"
        placeholder="YYYY-MM-DD..."
        value="<?php
          if ($isPrev) echo $prev['date-validity'];
          elseif ($isItem) echo $item['date_validity'];
          else echo null;
        ?>"
      >
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
