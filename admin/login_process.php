<?php

// Check if the anti-CSRF token and an action is passed
if ($_POST['token'] == $_SESSION['token'] AND isset($_POST['action'])) {
	
	// Admin is logging in
	if ($_POST['action'] == 'admin_login') {
	    
		// Escape username and password
		$uname = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
		$pword = $_POST['password'];
		
		// Get database connection
		$db = \App\Legacy\Database::getInstance();
		
		// Get admin info, if any
		$admin = $db->get(
		    "SELECT id, user_groups_id, name, password, hash
		    FROM admins
		    WHERE name = :name
		    LIMIT 1",
		    [":name" => $uname],
		    true
        );
		
		// ERROR: Wrong password flag
		if (! password_verify($pword, $admin['password'])) {
			$wrong_password = true;
		} else {

			// Store info on session
			$_SESSION['admin'] = $admin['hash'];
			
			// Notify the user
			notify("You logged in as admin", "success");
		}

	// Log out the user
	} else if ($_POST['action'] == 'admin_logout') {
		unset($_SESSION['admin']);
		notify("You logged out as admin", "danger");
	}
}
