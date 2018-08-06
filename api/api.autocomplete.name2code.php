<?php

// Init the app
require $_SERVER['DOCUMENT_ROOT'] . '/_config/config.php';

// Load helpers
require __DIR__ . "/helpers.php";

// ERROR: No term passed!
if (!isset($_GET['term']) OR empty($_GET['term'])) {
	echo outputJson([
		"label" => "No term",
		"value" => "No term"
	]);
	return;
}

// Get database connection
$db = \App\Database::getInstance();

// Sanitize name (preserve quotes)
$name = htmlspecialchars($_GET['term'], ENT_QUOTES, 'UTF-8');
$name = str_replace(['&#039;', '&quot;'], ['\'', "\\\""], $name);

$cards = $db->get(
	"SELECT id, cardcode, cardname, thumb_path
	FROM cards
	WHERE cardname LIKE \"%{$name}%\" AND block > 1
	ORDER BY setnum DESC, cardnum ASC
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
		'label' => $card['cardname'],
		'value' => $card['cardcode'],
		'path'  => $card['thumb_path']
	];
}

echo outputJson($response);
