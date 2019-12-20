<?php

// VARIABLES
// $items

?>
<div class="page-header">
	<h1>Comprehensive Rules</h1>
</div>

<!-- Dislaimer -->
<div class="fd-box font-110 p-100 mv-100 --grey">
  Always check the official website for reference:
  <a href="http://www.fowtcg.com/docs/rules" target="_blank">
    http://www.fowtcg.com/docs/rules
  </a>
</div>

<div class="list-group fd-list-group">

  <div class="list-group-item">
    <p class="list-group-item-text font-110">
      2019-11-15
      <a href="<?=url('documents/cr_9.0.pdf')?>" target="_self">
        Comprehensive Rules ver. 9.0
      </a>
    </p>
  </div>

	<?php foreach ($items as $item): ?>
  	<div class="list-group-item">
			<p class="list-group-item-text font-110">
				<?=$item['date_validity']?>
				<a
					href="<?=url('cr/' . $item['version'])?>"
					target="_self"
				>
					Comprehensive Rules ver. <?=$item['version']?>
				</a>
			</p>
		</div>
  <?php endforeach; ?>
</div>
