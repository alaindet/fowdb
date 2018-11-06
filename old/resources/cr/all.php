<div class="page-header">
	<h1>Comprehensive Rules</h1>
</div>

<div class="list-group f-list-group">
	<?php foreach ($items as $item): ?>
  	<div class="list-group-item f-list-group-item">
			<p class="list-group-item-text font-110">
				<?=$item['date_validity']?>
				<a
					href="<?=url_old('resources/cr', ['v' => $item['version']])?>"
					target="_self"
				>
					Comprehensive Rules ver. <?=$item['version']?>
				</a>
			</p>
		</div>
  <?php endforeach; ?>
</div>
