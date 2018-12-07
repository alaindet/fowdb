<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';
require __DIR__ . '/helpers.php'; // API helpers

// ERROR: No term passed!
if (!isset($_GET['term']) OR empty($_GET['term'])) {
	echo outputJson([
		'label' => 'No term',
		'value' => 'No term'
	]);
	return;
}

// Sanitize name (preserve quotes)
$name = str_replace(['&#039;', '&quot;'], ['\'', "\\\""], escape($_GET['term']));

$cards = database()
	->select(
		statement('select')
			->fields([
				'id',
				'name',
				'code',
				'image_path'
			])
			->from('cards')
			->where("name LIKE \"%{$name}%\"")
			->where('clusters_id > 1')
			->orderBy([
				'sets_id DESC',
				'num ASC',
			])
			->limit(10)
	)
	->get();

// ERROR: No results found!
if (empty($cards)) {
	echo outputJson([
		'label' => 'No results',
		'value' => 'No results'
	]);
	return;
}

$response = [];

foreach ($cards as &$card) {

	$label = "{$card['name']} ({$card['code']})";
	$link = url('card/'.urlencode($card['code']));

	$response[] = [

		// jQueryUI-specific
		'label' => $label, // Autocomplete item label,
		'value' => $label, // Autocomplete item value,

		// Extra information
		'id' => $card['id'],
		'image' => asset($card['image_path'], 'jpg'),
		'link' => $link

	];
}

echo outputJson($response);
