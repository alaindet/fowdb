<?php

// ERROR: Unauthorized
if (admin_level() === 0) {
	notify('You are noth authorized.', 'danger');
	header('Location: /');
	return;
}

// Set selected!
if (isset($_GET['set'])) {
    redirect(
        'temp/admin/artists/select-card',
        ['set' => $_GET['set']]
    );
}

// Select a set
$clusters = cached('clusters');
?>
<div class="row">
  <div class="col-xs-12">
    <form class="form form-inline" method="GET">
      <!-- Page -->
      <input type="hidden" name="p" value="temp/admin/artists/select-set">

      <select
        class="form-control input-sm"
        name="set"
        onchange="this.form.submit()"
      >
        <!-- Default -->
        <option value=0>Last set (default)</option>

        <?php foreach ($clusters as $cid => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
            <?php foreach($cluster['sets'] as $setcode => &$setname): ?>
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
