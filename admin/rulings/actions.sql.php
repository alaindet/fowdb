<?php

require dirname(dirname(__DIR__)) . '/src/config/config.php';

// Check if an action was passed, then execute it
if (isset($_POST['action'])) {

	// Database connection
	$db = \App\Database::getInstance();

	// DELETE RULING ----------------------------------------------------------
	if ($_POST['action'] == 'delete' AND isset($_POST['id'])) {

		// Generate card link with card name
		$card_link = cardLink($_POST['name_suggest'], $_POST['code']);

		// Try deleting the ruling
		if (! $db->delete("rulings", "id = :id", [":id" => $_POST['id']])) {
			// ERROR
			notify("Could not delete ruling for {$card_link}.", "warning");
		} else {
			// SUCCESS
			notify("Ruling for {$card_link} successfully deleted.", "info");
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
			notify(
				"Ruling for {$card_link} successfully edited.",
				"info"
			);
		} else {
			notify(
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
			notify("Could not create a new ruling for {$card_link}.", "warning");
		} else {
			// SUCCESS
			notify("New ruling for {$card_link} successfully added.", "info");
		}

		// FROM RULING REQUEST
		if (isset($_POST['req_form'])) {

			// Check if admin wants to delete ruling request
			if (isset($_POST['req_del'], $_POST['req_del'])) {

				// Try to delete ruling request
				if (! $db->delete("ruling_requests", "id = :id", [":id" => $_POST['req_id']])) {
					// ERROR
					notify("Ruling created, ruling request COULD NOT BE deleted", "danger");
				} else {
					// SUCCESS
					notify("Ruling created, ruling request deleted");
				}
			}

			// SUCCESS
			notify("Ruling created, ruling request NOT deleted");

			// Redirect user to requests manager
			header("Location: /?p=admin/requests");
			exit();
		}
	}
}

// REDIRECT TO RULINGS MAIN PAGE
header("Location: /?p=admin/rulings");

/**
 * Generates the link to the card page
 * @param  string $name The card's name
 * @param  string $code The card's code
 * @return string The anchor to the card page
 */
function cardLink($name, $code)
{
	return "<strong><a href='/?p=card&code="
			. urlencode($code)
			. "' target=_blank>{$name} ({$code})</a></strong>";
}
