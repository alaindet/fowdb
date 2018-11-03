<?php

require dirname(dirname(__DIR__)) . '/src/bootstrap.php';

// Check if an action was passed, then execute it
if (isset($_POST['action'])) {

	// Database connection
	$db = \App\Legacy\Database::getInstance();

	// DELETE RULING ----------------------------------------------------------
	if ($_POST['action'] == 'delete' AND isset($_POST['id'])) {

		// Generate card link with card name
		$card_link = cardLink($_POST['name_suggest'], $_POST['code']);

		// Try deleting the ruling
		if (! $db->delete("rulings", "id = :id", [":id" => $_POST['id']])) {
			// ERROR
			alert("Could not delete ruling for {$card_link}.", "warning");
		} else {
			// SUCCESS
			alert("Ruling for {$card_link} successfully deleted.", "info");
		}
	}
	
	// EDIT RULING ------------------------------------------------------------
	else if ($_POST['action'] == 'edit' AND isset($_POST['id'])) {
		
		// Generate card link with card name
		$card_link = cardLink($_POST['name_suggest'], $_POST['code']);

		// Try to update the ruling
		// public function update($table = null, $values = null, $condition = null, $conditionValues = null)
		if ($db->update(
			"rulings",
			[
				"created" => $_POST['date'] ?? date('Y-m-d'),
				"is_edited" => true,
				"is_errata" => isset($_POST['is_errata']) ? 1 : 0,
				"ruling" => $_POST['ruling']
			],
			"id = :id", [":id" => $_POST['id']]
		)) {
			alert(
				"Ruling for {$card_link} successfully edited.",
				"info"
			);
		} else {
			alert(
				"Could not edit the ruling for {$card_link}.",
				"warning"
			);
		}
	}
	
	
	// CREATE RULING ----------------------------------------------------------
	else if ($_POST['action'] == 'create') {
		
		// Generate card link with card name
		$card_link = cardLink($_POST['name_suggest'], $_POST['code']);

		// Try to create the new ruling
		if (! $db->insert("rulings",
			[
				"cards_id" => $_POST['card_id'],
				"created" => date("Y-m-d",time()),
				"is_errata" => isset($_POST['is_errata']) ? 1 : 0,
				"ruling" => $_POST['ruling']
			],
			true // Update on duplicate
		)) {
			// ERROR
			alert("Could not create a new ruling for {$card_link}.", "warning");
		} else {
			// SUCCESS
			alert("New ruling for {$card_link} successfully added.", "info");
		}
	}
}

// REDIRECT TO RULINGS MAIN PAGE
redirect_old('admin/rulings');

/**
 * Generates the link to the card page
 * @param  string $name The card's name
 * @param  string $code The card's code
 * @return string The anchor to the card page
 */
function cardLink($name, $code)
{
	$card_url = url_old('card', ['code' => urlencode($code)]);
	return "<strong><a href='{$card_url}' target=_blank>{$name} ({$code})</a></strong>";
}
