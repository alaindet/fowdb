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

$card = database_old()->get(
	'SELECT id FROM cards WHERE id = :id LIMIT 1',
	[':id' => $_GET['id']],
	$first = true
);

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
