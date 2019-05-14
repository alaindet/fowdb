<?php

// VARIABLES
// $pagination (object)
//   - totalCount
//   - count
//   - page
//   - perPage
//   - lastPage
//   - lowerBound
//   - upperBound
//   - link
//   - hasMorePages
//   - hasAnyPagination

/**
 * Updated a pagination info array with given new page
 *
 * @param array $info Pagination info object
 * @param int $page New page
 * @return array Updated pagination info array
 */
function changePage(object $info, int $page): object
{
  $new = clone $info;
  $new->page = $page;
  $new->hasMorePages = ($page < $info->lastPage) ? 1 : 0;
  $new->lowerBound = (($page - 1) * $info->perPage) + 1;
  $new->upperBound = min($page * $info->perPage, $info->totalCount);
  return $new;
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
  <?= fd_component("breadcrumb", [
    "Test" => fd_url("test"),
    "pagination" => "#"
  ]) ?>

</div>

<!-- Pagination with labels -->
<h2>With labels</h2>
<?= fd_component("pagination", ["pagination" => $pagination1]) ?>
<?= fd_component("pagination", ["pagination" => $pagination2]) ?>
<?= fd_component("pagination", ["pagination" => $pagination100]) ?>
<?= fd_component("pagination", ["pagination" => $pagination125]) ?>
<?= fd_component("pagination", ["pagination" => $pagination126]) ?>

<!-- Pagination with labels -->
<h2>Without labels</h2>
<?= fd_component("pagination", ["no-label" => true, "pagination" => $pagination1]) ?>
<?= fd_component("pagination", ["no-label" => true, "pagination" => $pagination2]) ?>
<?= fd_component("pagination", ["no-label" => true, "pagination" => $pagination100]) ?>
<?= fd_component("pagination", ["no-label" => true, "pagination" => $pagination125]) ?>
<?= fd_component("pagination", ["no-label" => true, "pagination" => $pagination126]) ?>
