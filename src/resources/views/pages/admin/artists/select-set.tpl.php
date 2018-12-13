<?php

$clusters = lookup('clusters.list');
$setsCode2Id = lookup('sets.code2id');

?>
<div class="page-header">
  <h1>Artists</h1>
  <?=component('breadcrumb', [
    'Admin' => url('admin'),
    'Artists' => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12">
    <form
      class="form form-inline"
      method="GET"
    >
      <select
        class="form-control input-lg"
        name="set"
        id="js-select-set"
      >
        <!-- Default -->
        <option value=0>Select a set...</option>

        <?php foreach ($clusters as $cluster_code => $cluster): ?>
          <optgroup label="<?=$cluster['name']?>">
            <?php foreach($cluster['sets'] as $setcode => $setname):
              $setid = $setsCode2Id[$setcode];
            ?>
              <option value="<?=$setid?>">
                <?=$setname?> - (<?=strtoupper($setcode)?>)
              </option>
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>    
      </select>
    </form>
  </div>
</div>
