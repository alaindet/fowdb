<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';
require __DIR__ . '/helpers.php'; // API helpers

// ERROR: No term passed!
if (!isset($_GET['term']) || empty($_GET['term'])) {
	echo outputJson([
		"label" => "No term",
		"value" => "No term"
	]);
	return;
}

// Sanitize name (preserve quotes)
$name = str_replace(['&#039;', '&quot;'], ['\'', "\\\""], escape($_GET['term']));

$artists = database()
	->select(
		statement('select')
			->fields('DISTINCT artist_name')
			->from('cards')
			->where("artist_name LIKE \"%{$name}%\"")
			->orderBy('artist_name ASC')
			->limit(20)
	)
	->get();

// ERROR: No results found!
if (empty($artists)) {
	echo outputJson([
		"label" => "No results",
		"value" => "No results"
	]);
	return;
}

echo outputJson(array_reduce($artists, function ($result, $row) {
	$result[] = [
		'label' => $row['artist_name'],
		'value' => $row['artist_name']
	];
	return $result;
}, []));
