<?php

/**
 * Checks for an admin logged in, then returns admin level
 *
 * @return int 0 If no admin is logged in, 1+ if there's an admin
 */
function admin_level(): int
{
	// ERROR: No admin stores into session
	if (!isset($_SESSION['admin'])) {
		return 0;
	}

	// Get admin info from database (INT)
	$admin = database()->get(
		"SELECT user_groups_id FROM admins WHERE hash = :hash",
		[":hash" => $_SESSION['admin']],
		true
	);

	// ERROR: No admin found
	if (empty($admin) || $admin['user_groups_id'] == $_SESSION['admin']) {
		return 0;
	}

	return (int) $admin['user_groups_id'];
}