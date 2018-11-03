<?php

use \App\Legacy\Authorization;
use \App\Http\Request\Input;
use \App\Models\Card;

// Bounce back unauthorized users
Authorization::allow([1, 2]);

$input = Input::getInstance();

// List rulings
if (!$input->hash('form-action')) {
	require __DIR__ . '/rulings.sql.php';
	require __DIR__ . '/rulings.html.php';
	return;
}

// ACTION ---------------------------------------------------------------------

$cardId = $input->request('card-id', $escape = true);

// Read this card's data from the database
if (isset($cardId)) {
	$card = Card::getById($cardId, ['name', 'code']);
}

// Read the action and the code
$action = $input->request('form-action');

// ERROR: Missing action
if (!isset($action)) {
	alert('No action provided');
	redirect_old('/');
}

// Available actions
$actions = [ 'create', 'edit', 'delete' ];

// ERROR: Invalid action provided
if (!in_array($action, [ 'create', 'edit', 'delete' ])) {
	alert('Invalid action provided');
	redirect_old('/');
}

// Choose form title
if ($action === 'create') {

	$title = 'Create new ruling';

} elseif ($action === 'edit') {

	$cardLabel = "<strong>{$card['name']} {$card['code']}</strong>";
	$title = "Edit ruling for {$cardLabel}";

} elseif ($action === 'delete') {

	$cardLabel = "<strong>{$card['name']} {$card['code']}</strong>";
	$title = "Delete ruling for {$cardLabel}?";

}

if ($action === 'create') {
	
}

// Include form
require __DIR__ . '/ruling.sql.php';
require __DIR__ . '/form.html.php';
