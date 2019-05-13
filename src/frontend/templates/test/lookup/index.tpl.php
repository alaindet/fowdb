<?php

// VARIABLES
// $features

?>
<div class="page-header">
  <h1>Test lookup data</h1>
  <?=fd_component('breadcrumb', [
    'Test' => fd_url('test'),
    'Lookup' => '#'
  ])?>
</div>
<hr>

<div class="col-xs-12">

  <!-- Regenerate all -->
  <a href="<?=fd_url('test/lookup/build')?>" class="btn btn-lg fd-btn-primary">
    <i class="fa fa-database"></i>
    Regenerate all
  </a>
      
  <hr>

  <!-- Read -->
  <ul class="fd-list --spaced font-110">

    <li>
      <a href="<?=fd_url('test/lookup/read')?>">
        Read all
      </a>
    </li>
    <hr>

    <?php foreach ($features as $_feat):
      $displayFeat = str_pad(ucfirst($_feat), 10, '_', STR_PAD_LEFT);
    ?>
      <li>
        <span class="text-monospace"><?=$displayFeat?></span>
        <a href="<?=fd_url('test/lookup/read/'.$_feat)?>">read</a>
        <a href="<?=fd_url('test/lookup/build/'.$_feat)?>">build</a>
      </li>
    <?php endforeach; ?>

  </ul>

</div>

<!-- Data log -->
<?php if (isset($log)): ?>
  <div class="col-xs-12 col-sm-9">
    <?=$log?>
    <?=fd_component('top-anchor')?>
  </div>
<?php endif; ?>
