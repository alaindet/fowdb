<?php

// VARIABLES
// urls

$baseUrl = fd_url("");

$items = [];
foreach ($urls as $url) {
  $relativeUrl = str_replace($baseUrl, "", $url);
  $items[$relativeUrl] = $url;
}

?>
<ul class="fd-list --spaced font-110">
  <?php foreach ($items as $relativeUrl => $url): ?>
    <li>
      <a href="<?=$url?>">
        <?=$relativeUrl?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?=fd_component("navigation/top-anchor")?>
