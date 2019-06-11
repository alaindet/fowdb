<?php
/*
 * VARIABLES
 * array pagination
 * App\Base\Items\ItemsCollection results
 * string sql
 */
?>
<div class="page-header">
  <h1>Database pagination</h1>
  <?= fd_component("navigation/breadcrumb", (object) [
    "Test" => "test",
    "database" => "#",
    "pagination" => "#"
  ]) ?>
</div>

<h2>Query</h2>
<?= fd_log_html($sql) ?>

<h2>Pagination data</h2>
<div class="well text-monospace">
  <ul>
    <?php foreach ($pagination as $key => $value): ?>
      <li>
        <strong><?= $key ?></strong>:
        <?= $value ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<h2>Results</h2>
<div class="well text-monospace">
  <ul>
    <?php foreach ($results as $result): ?>
      <li>
        <strong><?= $result->code ?></strong>
        <?= $result->name ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
