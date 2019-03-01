<?php

// VARIABLES
// $features

?>
<div class="page-header">
  <h1>Lookup data</h1>
  <?=component('breadcrumb', [
    'Test' => url('test'),
    'Lookup' => '#'
  ])?>
</div>
<hr>

<div class="col-xs-12 col-sm-3">

  <!-- Regenerate all -->
  <a href="<?=url('lookup/build')?>" class="btn btn-lg fd-btn-primary">
    <i class="fa fa-database"></i>
    Regenerate all
  </a>
      
  <hr>

  <!-- Read -->
  <ul class="fd-list --spaced">

    <li>
      <a href="<?=url('lookup/read')?>">
        Read all
      </a>
    </li>
    <hr>

    <?php foreach ($features as $_feat): ?>
      <li>
        <a href="<?=url('lookup/read/'.$_feat)?>">
          Read <?=$_feat?>
        </a>
      </li>
    <?php endforeach; ?>

  </ul>

</div>

<!-- Data log -->
<?php if (isset($log)): ?>
  <div class="col-xs-12 col-sm-9">
    <?=$log?>
    <?=component('top-anchor')?>
  </div>
<?php endif; ?>
