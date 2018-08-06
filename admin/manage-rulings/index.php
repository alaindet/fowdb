<?php

// Check if admin is logged in
if (admin_level() > 0) {
	
	// Check if a create/edit/delete request was initialized by user
	if (isset($_REQUEST['form_action'])) {

		// Select action to take
		switch($_REQUEST['form_action']) {

			// Create
			case 'create':
				$title = 'Create new ruling'; // Title
				$action = 'create'; // Create flag
				break;

			// Edit
			case 'edit':
				$title = "Edit ruling <small>{$_REQUEST['code']}</small>"; // Title
				$action = 'edit'; // Edit flag
				break;

			// Delete
			case 'delete':
				$title = "Delete this ruling from {$_REQUEST['code']} card?";
				$action = 'delete'; // Delete flag
				break;
		}

		// Include form
		require __DIR__ . '/form.html.php'; // Include form
	}

	// No create/edit/delete request passed
	else {
		// Retrieve rulings from DB
		require __DIR__ . '/rulings.sql.php';
	
		// Show list of all rulings
		require __DIR__ . '/rulings.html.php';
	}
}
else {
	
	// Request login
	echo '<div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>';
}
