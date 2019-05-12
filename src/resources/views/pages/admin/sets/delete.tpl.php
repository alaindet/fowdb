<?php

// VARIABLES
// $item

// INPUT
// (none)

?>
<div class="page-header">
  <h1>
    Delete set
    <small><?=$item['name']?> (<?=$item['code']?>)</small>
  </h1>
  <?=fd_component('breadcrumb', [
    'Admin' => url('profile'),
    'Sets' => url('sets/manage'),
    'Delete' => '#'
  ])?>
</div>

<div class="fd-boxes">

  <!-- Set info -->
  <div class="fd-box --more-margin">
    <div class="fd-box__content">
      
      <form
        action="<?=url("sets/delete/{$item['id']}")?>"
        method="post"
        class="form-horizontal"
      >
        <?=fd_csrf_token()?>

        <!-- Cluster ====================================================== -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Cluster</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['cluster_id']?>#
              <?=$item['cluster_name']?>
              (<?=strtoupper($item['cluster_code'])?>)
            </p>
          </div>
        </div>

        <!-- ID =========================================================== -->
        <div class="form-group">
          <label class="col-sm-2 control-label">ID</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['id']?>
            </p>
          </div>
        </div>

        <!-- Name ========================================================= -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['name']?>
            </p>
          </div>
        </div>

        <!-- Code ========================================================= -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Code</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['code']?>
            </p>
          </div>
        </div>

        <!-- Count ======================================================== -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Count</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['count']?>
            </p>
          </div>
        </div>

        <!-- Release date ================================================= -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Release date</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?=$item['release_date'] ?? '<em>(None)</em>'?>
            </p>
          </div>
        </div>

        <!-- Spoiler? ===================================================== -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Errata</label>
          <div class="col-sm-10">
            <p class="fd-text-form-horizontal font-110">
              <?="It's ".($item['is_spoiler'] ? '' : 'NOT ')."a spoiler set"?>
            </p>
          </div>
        </div>

        <!-- Submit ======================================================= -->
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
  </div>

  <!-- Rules -->
  <div class="fd-box --darker-headings --more-margin">
    <div class="fd-box__title">
      <h2>Rules</h2>
      <a name="rules"></a>
    </div>
    <div class="fd-box__content">
      <ul class="fd-list --spaced">
        <li>
          Removing a set will remove all card images from the filesystem and all cards from this set, including associated rulings and banned lists
        </li>
      </ul>
    </div>
  </div>

</div>
