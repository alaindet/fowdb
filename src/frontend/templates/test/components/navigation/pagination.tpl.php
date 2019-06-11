<?php

/**
 * Updates a pagination info array with given new page
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

function toComponent(object $pagination, bool $hasLabel = true): object
{
  return (object) [
    "pagination" => $pagination,
    "hasLabel" => $hasLabel
  ];
}

if (isset($_GET["page"])) {
  $pagination = changePage($pagination, intval($_GET["page"]));
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
  <?= fd_component("navigation/breadcrumb", (object) [
    "Test" => "test",
    "pagination" => "#"
  ]) ?>

</div>

<!-- Pagination with labels -->
<h2>With labels</h2>
<?= fd_component("navigation/pagination", toComponent($pagination, true)) ?>
<?php /*
<?= fd_component("navigation/pagination", toComponent($pagination1)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination2)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination100)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination125)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination126)) ?>
*/ ?>

<!-- Pagination with labels -->
<h2>Without labels</h2>
<?= fd_component("navigation/pagination", toComponent($pagination, false)) ?>
<?php /*
<?= fd_component("navigation/pagination", toComponent($pagination1, false)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination2, false)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination100, false)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination125, false)) ?>
<?= fd_component("navigation/pagination", toComponent($pagination126, false)) ?>
*/ ?>
