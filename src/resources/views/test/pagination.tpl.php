<?php

// VARIABLES
// $pagination

/**
 * Updated a pagination info array with given new page
 *
 * @param array $info Pagination info array
 * @param int $page New page
 * @return array Updated pagination info array
 */
function changePage(array $info, int $page): array
{
  $info['current-page'] = $page;
  $info['more'] = ($page < $info['last-page']) ? 1 : 0;
  $info['lower-bound'] = (($page - 1) * $info['per-page']) + 1;
  $info['upper-bound'] = min($page * $info['per-page'], $info['total']);
  return $info;
}

$pagination1 = changePage($pagination, 1);
$pagination2 = changePage($pagination, 2);
$pagination100 = changePage($pagination, 100);
$pagination125 = changePage($pagination, 125);
$pagination126 = changePage($pagination, 126);

?>
<div class="page-header">

  <!-- Title -->
  <h1>Pagination</h1>

  <!-- Breadcrumbs -->
  <?=component('breadcrumb', [
    'Test' => url('test'),
    'pagination' => '#'
  ])?>

</div>

<!-- Pagination -->
<?=component('pagination', [ 'pagination' => $pagination1 ])?>
<?=component('pagination', [ 'pagination' => $pagination2 ])?>
<?=component('pagination', [ 'pagination' => $pagination100 ])?>
<?=component('pagination', [ 'pagination' => $pagination125 ])?>
<?=component('pagination', [ 'pagination' => $pagination126 ])?>
