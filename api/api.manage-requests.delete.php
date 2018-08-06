<?php

// Require config
require '../_config/config.php';

if (
	isset($_POST['token']) AND $_POST['token'] == $_SESSION['token'] AND
	isset($_POST['id']) AND
	isset($_POST['action']) AND $_POST['action'] == 'delete'
){

	// Declare array that will hold ID values
	$ids = [];

	// Check if id is an array
	if(is_array($_POST['id'])) {
		foreach($_POST['id'] as $id) {
			$ids[] = (int)$id;
		}
	}
	else {
		$ids[] = (int)$_POST['id'];
	}

	// Generate SQL filter
	$filter = 'id IN(' . implode(', ', $ids) . ')';

	// Perform query
	try {
		$stmt = $pdo->exec("DELETE FROM ruling_requests WHERE {$filter}; ALTER TABLE ruling_requests AUTO_INCREMENT = 1;");
	}
	catch (PDOException $e) {
		// Return error
		$rsp = ['response' => $e->getMessage()];
		echo json_encode($rsp);
		exit();
	}

	// Return success
	$rsp = ['response' => 'Requests '.implode(', ',$ids) .' successfully removed from database.'];
}
else {
	// Return error
	$rsp = ['response' => 'Token and/or request id and/or action was not passed.'];
}

// Return response
echo json_encode($rsp);
