<?php

use \App\Legacy\AdminView as View;
use \App\Services\Lookup\Lookup;
use \App\Utils\Logger;
use \App\Legacy\Authorization;

// Bounce back unauthorized users
Authorization::allow([1]);

$lookup = Lookup::getInstance();
$breadcrumb = [
  'Admin' => url('admin'),
  'Lookup' => '#'
];
$data = [];
$features = $lookup->features();

// Regenerate the lookup cached data
if (input()->has('regenerate')) {

  $feature = input()->get('regenerate');

  $lookup->generateAll()->cache();
  $data = $lookup->getAll();

  alert('Lookup data cache regenerated.', 'success');
  $breadcrumb = [
    'Admin' => url('admin'),
    'Lookup' => url_old('admin/lookup'),
    'Regenerate' => '#'
  ];

}

// Show lookup cached data
if (input()->has('read')) {

  $feature = input()->get('read');

  ($feature === 'all')
    ? $data = $lookup->getAll()
    : $data = $lookup->get($feature);

  $breadcrumb = [
    'Admin' => url('admin'),
    'Lookup' => url_old('admin/lookup'),
    'Read' => '#'
  ];

}
?>

<div class="page-header">
  <h1>Lookup data</h1>
  <?=component('breadcrumb', $breadcrumb)?>
</div>

<p>
  This data is used throughout FoWDB to avoid JOIN clauses for reading presentational data when querying the database, like card types, attributes and rarities, since those are stored as integers into the <strong>cards</strong> table. Most integer fields on <strong>cards</strong> map to some "auxiliary" data on a normalized <strong>card_*</strong> corresponding table. This data should be regenerated every time it's needed, like a new set, a new rarity (?!) or a new type comes out (happens more often then you think). FoWDB tries to automatically update lookup data whenever it's needed, like when a new set comes out, but you can manually regenerate and read lookup data here. Read data is then cached into a single serialized file of about 12 Kb.
</p>
<hr>

<div class="col-xs-12 col-sm-3">

  <!-- Regeneraate -->
  <a
    href="<?=url_old('admin/lookup', ['regenerate' => 'all'])?>"
    class="btn btn-lg btn-primary"
  >
    <i class="fa fa-cog"></i>
    Regenerate all
  </a>
      
  <hr>

  <!-- Read -->
  <ul class="fd-list --spaced">
    <li>
      <a href="<?=url_old('admin/lookup', ['read' => 'all'])?>">
        Read all
      </a>
    </li>
    <hr>
    <?php foreach ($lookup->features() as $_feature): ?>
      <li>
        <a href="<?=url_old('admin/lookup', ['read' => $_feature])?>">
          Read <?=$_feature?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php if (!empty($data)): ?>
  <div class="col-xs-12 col-sm-9">
    <?=Logger::html($data, 'Lookup data: ' . $feature)?>
  </div>
  <?=component('top-anchor')?>
<?php endif; ?>
