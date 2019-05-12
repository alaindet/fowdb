<?php

// VARIABLES
// $item

?>
<div class="page-header">
  <h1>Delete comprehensive rules</h1>
  <?=fd_component('breadcrumb', [
    'Admin' => fd_url('profile'),
    'Comprehensive Rules' => fd_url('cr/manage'),
    'Delete' => '#',
    'Show' => fd_url('cr/'.$item['version'])
  ])?>
</div>

<div class="fd-box --more-margin pv-100">
  <form
    action="<?=fd_url("cr/delete/{$item['id']}")?>"
    method="post"
    class="form-horizontal"
  >
    <?=fd_csrf_token()?>

    <!-- Source file ====================================================== -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Source file</label>
      <div class="col-sm-10">
        <a
          href="<?=fd_url('cr/file/'.$item['id'])?>"
          class="btn btn-lg fd-btn-default"
        >
          Download source file &rarr; <?=$item['version']?>.txt
        </a>
      </div>
    </div>

    <!-- ID =============================================================== -->
    <div class="form-group">
      <label class="col-sm-2 control-label">ID</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['id']?>
        </p>
      </div>
    </div>

    <!-- Created on ======================================================= -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Created on</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['date_created']?>
        </p>
      </div>
    </div>

    <!-- Version ========================================================== -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Version</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['version']?>
        </p>
      </div>
    </div>

    <!-- Valid from ======================================================= -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Valid from</label>
      <div class="col-sm-10">
        <p class="fd-text-form-horizontal font-110">
          <?=$item['date_validity']?>
        </p>
      </div>
    </div>

    <!-- Submit =========================================================== -->
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
