<?php

$items = database()
  ->select(statement('select')
		->select([
			'c.name `name`',
			'c.code `code`',
			'r.date `date`',
			'r.text `text`',
		])
		->from(
			'game_rulings r
			INNER JOIN cards c ON r.cards_id = c.id'
		)
		->where('r.is_errata = 1')
		->orderBy('r.date DESC')
	)
	->get();

// ERROR: Missing errata
if (empty($items)) {
	alert('There are no errata on FoWDB at the moment.', 'warning');
	redirect_old('/');
}

?>
<div class="page-header">
	<h1>
    Errata
    <small>(<?=count($items)?>)</small>
  </h1>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="list-group f-list-group">
			<?php foreach ($items as $item): ?>
			  <div class="list-group-item f-list-group-item">
			    <h4 class="list-group-item-heading">
			    	<em><?=$item['date']?></em>
			    	<a
							href="<?=url_old('card', ['code' => urlencode($item['code'])])?>"
						>
							<?="{$item['name']} ({$item['code']})"?> 
						</a>
			    </h4>
			    <p class="list-group-item-text"><?=render($item['text'])?></p>
			  </div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
