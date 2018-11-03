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
$name = htmlspecialchars($_GET['term'], ENT_QUOTES, 'UTF-8');
$name = str_replace(['&#039;', '&quot;'], ['\'', "\\\""], $name);

$artists = database_old()->get(
	"SELECT DISTINCT artist_name
	FROM cards
	WHERE artist_name LIKE \"%{$name}%\"
	ORDER BY artist_name ASC
	LIMIT 20"
);

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
