<?php

use \App\Helpers;

// ERROR: Authorization
if (admin_level() === 0) {
  notify('You are not authorized.', 'danger');
  redirect('/');
}

// Review all cached data
if (isset($_POST['review'])) {
  echo logHtml(Helpers::getAll(), '/app/helpers/helpers.json');
}

// Read all dynamic helpers
$dynamics = array_merge(['all'], Helpers::$dynamicFeatures);

// Check if any feature to be cached is passed
if (isset($_POST['feat']) && in_array($_POST['feat'], $dynamics)) {

  // Alias feature
  $feat =& $_POST['feat'];

  // Re-generate all features
  if ($feat === 'all') {

    // Re-generate all dynamic helpers and assemble all helpers
    $saved = Helpers::generateAll() && Helpers::assembleAll();

    notify(
      $saved
        ? 'All helpers JSON data regenerated.'
        : 'Could not generate and assemble all helpers.'
    );
    
    // Log generated file
    echo logHtml(Helpers::getAll(), $feat);
  }
    
  // Re-generate specific feature
  elseif (in_array($feat, $dynamics)) {

    // Re-generate current and assemble all
    $saved = Helpers::generate($feat) && Helpers::assembleAll();

    notify(
      $saved
        ? "{$feat}.json regenerated."
        : "Could not generate and assemble helper file for {$feat}."
    );

    // Log generated file
    echo logHtml(\App\Helpers::get($feat), $feat);
  }
}
?>
<div class="page-header">
  <h1>Helpers</h1>
</div>

<div class="well">
  <p>
    Helpers are used throughout FoWDB for presentation of game-specific data. They include data about what features cards have, then attributes, clusters, costs, formats, keywords, rarities, sorting fields and types! This page lets you re-generate individual JSON files for semi-static data that rarely changes (Clusters, Formats, Keywords, Types) and saves them into <code>/app/helpers/data/</code> directory, then rebuilds final <code>/app/helpers/helpers.json</code>. You can also re-genearate all at once.
  </p>
</div>

<form class="form form-inline" action="" method="post">

  <p>
    <button
      name="review"
      value="all"
      type="submit"
      class="btn btn-info btn-lg"
    >
      Review <code>helpers.json</code>
    </button>
  </p>

  <?php foreach ($dynamics as $feat): ?>
    <p>
      <button
        name="feat"
        value="<?=$feat?>"
        type="submit"
        class="btn btn-default btn-lg"
      >
        Regenerate <?=ucfirst($feat)?>
      </button>
    </p>
  <?php endforeach; ?>

</form>
