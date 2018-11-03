<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';
require __DIR__ . '/helpers.php'; // API helpers

// ERROR: No term passed!
if (!isset($_GET['term']) OR empty($_GET['term'])) {
	echo outputJson([
		"label" => "No term",
		"value" => "No term"
	]);
	return;
}

// Sanitize name (preserve quotes)
$name = htmlspecialchars($_GET['term'], ENT_QUOTES, 'UTF-8');
$name = str_replace(['&#039;', '&quot;'], ['\'', "\\\""], $name);

$cards = database_old()->get(
	"SELECT id, code, name, thumb_path
	FROM cards
	WHERE name LIKE \"%{$name}%\" AND clusters_id > 1
	ORDER BY sets_id DESC, num ASC
	LIMIT 10"
);

// ERROR: No results found!
if (empty($cards)) {
	echo outputJson([
		"label" => "No results",
		"value" => "No results"
	]);
	return;
}

$response = [];

foreach ($cards as &$card) {
	$response[] = [
		'id'    => $card['id'],
		'label' => $card['name'],
		'value' => $card['code'],
		'path'  => $card['thumb_path']
	];
}

echo outputJson($response);
