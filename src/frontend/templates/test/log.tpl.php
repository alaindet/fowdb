<?php
/*
 * INPUT
 * ?any data
 * ?string title
 */

$title = $title ?? "Data log";
?>
<div class="page-header">
  <h1><?= $title ?></h1>
  <?php
    echo fd_component("navigation/breadcrumb", (object) [
      "Test" => "test",
      $title => "#",
    ]);
  ?>
</div>

<?php if (isset($data)): ?>
  <?= fd_log_html($data) ?>
<?php else: ?>
  No data to show
<?php endif; ?>
