<?php

/**
 * Checks for an admin logged in, then returns admin level
 *
 * @return int 0 If no admin is logged in, 1+ if there's an admin
 */
function admin_level()
{
	// ERROR: No admin stores into session
	if (!isset($_SESSION['admin'])) {
		return 0;
	}

	// Get database connection
	$db = \App\Database::getInstance();

	// Get admin info from database (INT)
	$admin = $db->get(
		"SELECT user_groups_id FROM admins WHERE hash = :hash",
		[":hash" => $_SESSION['admin']],
		true
	);

	// ERROR: No admin found
	if (empty($admin) OR $admin['user_groups_id'] == $_SESSION['admin']) {
		return 0;
	}

	return (int) $admin['user_groups_id'];
}
