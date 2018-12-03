<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';
require __DIR__ . '/helpers.php';

// ERROR: Missing ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
	echo outputJson([
		'response' => false,
		'message' => 'Missing card ID'
	]);
	return;
}

$card = (new \App\Models\Card)->byId($_GET['id']);

// ERROR: Card not found
if (empty($card)) {
	echo outputJson([
		'response' => false,
		'message' => "No card with ID {$_GET['id']} exists in the database."
	]);
	return;
}

echo outputJson([
	'response' => true,
	'message' => 'Card found',
]);
