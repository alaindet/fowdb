<?php

use \App\Legacy\AdminView as View;
use \App\Services\Lookup\Lookup;
use \App\Utils\Logger;

// Bounce back unauthorized users
\App\Legacy\Authorization::allow([1]);

$lookup = Lookup::getInstance();
$breadcrumbs = ['Lookup' => '#'];
$data = [];
$features = $lookup->features();

// Regenerate the lookup cached data
if (input()->exists('regenerate', 'GET')) {

  $lookup->generateAll()->cache();
  $data = $lookup->getAll();
  $breadcrumbs = [
    'Lookup' => 'admin/lookup',
    'Regenerate' => '#'
  ];
  alert('Lookup data cache regenerated.', 'success');

}

// Show lookup cached data
if (input()->exists('read', 'GET')) {

  $feature = input()->get('read');

  ($feature === 'all')
    ? $data = $lookup->getAll()
    : $data = $lookup->get($feature);

  $breadcrumbs = [
    'Lookup' => url_old('admin/lookup'),
    'Read' => '#'
  ];

}
?>

<div class="page-header">
  <h1>FoWDB Lookup data</h1>
  <?=View::breadcrumbs($breadcrumbs)?>
</div>

<div class="col-xs-12 col-sm-3">

  <!-- Regeneraate -->
  <a
    href="<?=url_old('admin/lookup', ['regenerate' => 1])?>"
    class="btn btn-lg btn-primary"
  >
    <i class="fa fa-cog"></i>
    Regenerate
  </a>
      
  <hr>

  <!-- Read -->
  <ul>
    <li>
      <a
        href="<?=url_old('admin/lookup', ['read' => 'all'])?>"
        class="btn btn-default separate"
      >
        Read all
      </a>
    </li>
    <?php foreach ($lookup->features() as $feature): ?>
      <li>
        <a
          href="<?=url_old('admin/lookup', ['read' => $feature])?>"
          class="btn btn-default separate"
        >
          Read <?=$feature?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php if (!empty($data)): ?>
  <div class="col-xs-12 col-sm-9">
    <?=Logger::html($data, 'Lookup data: ' . $feature)?>
  </div>
<?php endif; ?>
