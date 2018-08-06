<?php

// Init the app
require $_SERVER['DOCUMENT_ROOT'] . '/_config/config.php';

// Load helpers
require __DIR__ . "/helpers.php";

// ERROR: 
if (!isset($_GET['name'], $_GET['code']) OR empty($_GET['name']) OR empty($_GET['code'])) {
	echo outputJson([
		"response" => "Missing name and/or code in the request"
	]);
	return;
}

// Sanitize name and code
$name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
$name = str_replace(array('&#039;', '&quot;'), array('\'', "\\\""), $name);
$code = htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8');
$code = str_replace(array('&#039;', '&quot;'), array('\'', "\\\""), $code);

$db = \App\Database::getInstance();

$card = $db->get(
	"SELECT id FROM cards
	WHERE cardname = :name AND cardcode = :code
	LIMIT 1",
	[":name" => $name, ":code" => $code],
	true // Return first card
);

if (empty($card)) {
	echo outputJson([
		'response' => false,
		'message' => 'No card with that name and code exists in the database.'
	]);
	return;
}

echo outputJson([
	'response' => true,
	'message' => 'Card found',
	'data' => ['card_id' => (int) $card['id']]
]);
