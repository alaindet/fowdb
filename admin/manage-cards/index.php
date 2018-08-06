<?php

// Check if admin is logged in
if (admin_level() > 0) {
	
	// Check if a create/edit/delete request was initialized by user
	if (isset($_REQUEST['form_action'])) {

		// Get selected action
		$action = $_REQUEST['form_action'];

		// Include form
		include 'admin/manage-cards/form.html.php'; // Include form
	}

	// Check if menu action or change page or sort cards was passed
	else if (
		(isset($_REQUEST['menu_action']) AND $_REQUEST['menu_action'] == 'list') OR
		isset($_POST['sort_cards']) OR isset($_POST['page'])
	) {
		// Retrieve all cards from DB
		include 'admin/manage-cards/cards.sql.php';
	
		// Show list of all cards
		include 'admin/manage-cards/cards.html.php';
	}

	// No create/edit/delete request passed
	else {

		// Show Create/Edit/Delete menu
		include 'admin/manage-cards/menu.html.php';
	}
}
else {
	// Request login
	echo '<div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>';
}