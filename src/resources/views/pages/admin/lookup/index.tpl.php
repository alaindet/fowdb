<?php

// VARIABLES
// $breadcrumb
// $feature
// $features
// $log (optional)

?>
<div class="page-header">
  <h1>Lookup data</h1>
  <?=fd_component('breadcrumb', $breadcrumb)?>
</div>

<p>
  This data is used throughout FoWDB to avoid JOIN clauses for reading presentational data when querying the database, like card types, attributes and rarities, since those are stored as integers into the <strong>cards</strong> table. Most integer fields on <strong>cards</strong> map to some "auxiliary" data on a normalized <strong>card_*</strong> corresponding table. This data should be regenerated every time it's needed, like a new set, a new rarity (?!) or a new type comes out (happens more often then you think). FoWDB tries to automatically update lookup data whenever it's needed, like when a new set comes out, but you can manually regenerate and read lookup data here. Read data is then cached into a single serialized file of about 12 Kb.
</p>
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
    <?=fd_component('top-anchor')?>
  </div>
<?php endif; ?>
