<?php

if (admin_level() === 0) {
	notify('You are not authorized.', 'danger');
	redirect('/');
}

// Check if a create/edit/delete request was initialized by user
if (isset($_REQUEST['form_action'])) {

	// Get selected action
	$action = $_REQUEST['form_action'];

	// Include form
	include 'admin/cards/form.html.php'; // Include form
}

// Check if menu action or change page or sort cards was passed
else if (
	(isset($_REQUEST['menu_action']) AND $_REQUEST['menu_action'] == 'list') OR
	isset($_POST['sort_cards']) OR isset($_POST['page'])
) {
	// Retrieve all cards from DB
	include 'admin/cards/cards.sql.php';

	// Show list of all cards
	include 'admin/cards/cards.html.php';
}

// No create/edit/delete request passed
else {

	// Show Create/Edit/Delete menu
	include 'admin/cards/menu.html.php';
}
