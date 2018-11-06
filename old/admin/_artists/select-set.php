<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow();

// Set selected!
if (isset($_GET['set'])) {
    redirect_old(
        'admin/_artists/select-card',
        ['set' => $_GET['set']]
    );
}

// Select a set
$clusters = lookup('clusters');

?>
<div class="page-header">
  <h1>Artists</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Artists' => '#'
  ])?>
</div>

<h2>Select a set</h2>

<div class="row">
  <div class="col-xs-12">
    <form class="form form-inline" method="GET">

      <!-- Page -->
      <input type="hidden" name="p" value="admin/_artists/select-set">

      <select
        class="form-control"
        name="set"
        onchange="this.form.submit()"
      >
        <!-- Default -->
        <option value=0>Select a set...</option>

        <?php foreach ($clusters as $cluster_code => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
            <?php foreach($cluster['sets'] as $setcode => $setname): ?>
              <option value="<?=$setcode?>">
                <?=$setname?> - (<?=strtoupper($setcode)?>)
              </option>;
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>    
      </select>
    </form>
  </div>
</div>
