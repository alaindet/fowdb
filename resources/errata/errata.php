<?php

$db = \App\Legacy\Database::getInstance();

$errata = $db->get(
	"SELECT cardname as name, cardcode as code, created as last_edit, ruling
	FROM rulings INNER JOIN cards ON rulings.cards_id = cards.id
	WHERE is_errata = TRUE
	ORDER BY last_edit DESC"
);

// ERROR: Missing errata
if (empty($errata)) {
	notify("There are currently no errata");
	redirect("/");
}
?>
<!-- Header -->
<div class="page-header"><h1>Errata (<?php echo count($errata); ?>)</h1></div>
<div class="row">
	<div class="col-xs-12">
		<div class="list-group f-list-group">
			<?php foreach ($errata as &$item): ?>
			  <div class="list-group-item f-list-group-item">
			    <h4 class="list-group-item-heading">
			    	<em><?=$item['last_edit']?></em>
			    	<a href="/?p=card&code=<?=$item['code']?>"><?=$item['name']?> (<?=$item['code']?>)</a>
			    </h4>
			    <p class="list-group-item-text"><?=render($item['ruling'])?></p>
			  </div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
