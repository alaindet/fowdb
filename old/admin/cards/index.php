<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow([1, 2]);

// Check if a create/edit/delete request was initialized by user
if (isset($_REQUEST['form_action'])) {

	$action = $_REQUEST['form_action'];
	require __DIR__ . '/form.html.php';

}

// Check if menu action or change page or sort cards was passed
elseif (
	(isset($_REQUEST['menu_action']) && $_REQUEST['menu_action'] == 'list') ||
	isset($_POST['sort_cards']) ||
	isset($_POST['page'])
) {
	require __DIR__ . '/cards.sql.php';
	require __DIR__ . '/cards.html.php';
}

// No create/edit/delete request passed
else {

	require __DIR__ . '/menu.html.php';
}
