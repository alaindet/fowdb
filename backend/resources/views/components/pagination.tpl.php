<?php
// VARIABLES
// $items
$first = array_shift($items);
$last = array_pop($items);
?>
<nav aria-label="Page navigation">
  <ul class="pagination fd-pagination">

    <li>
      <a href="<?=$first['link']?>" aria-label="First page">
        <span aria-hidden="true">First</span>
      </a>
    </li>

    <?php foreach ($items as $item): ?>
      <?php $active = $item['active'] ? ' class="active"' : ''; ?>
      <li<?=$active?>>
        <a href="<?=$item['link']?>">
          <?=$item['page']?>
        </a>
      </li>
    <?php endforeach; ?>

    <li>
      <a href="<?=$last['link']?>" aria-label="Last Page">
        <span aria-hidden="true">Last (<?=$last['page']?>)</span>
      </a>
    </li>

  </ul>
</nav>
