<?php

if (
	isset($_POST['id']) AND
	isset($_POST['token'])
){

	// Request config
	require '../_config/config.php';

	// Sanitize id
	$id = (int)$_POST['id'];

	// Check if user aleady voted for some requests
	if (isset($_SESSION['request-votes'])) {

		// Check if user already voted for THIS request, then prevent it
		if (in_array($id, $_SESSION['request-votes'])) {

			// Return error and exit
			$rsp = ['response' => 'You already voted for this request. Thank you.'];
			echo json_encode($rsp);
			exit();
		}
		else {
			// Add current request to those already voted
			$_SESSION['request-votes'][] = $id;
		}
	}
	else {
		$_SESSION['request-votes'] = [$id];
	}

	// Try updating vote
	try {
		$stmt = $pdo->prepare("
			UPDATE ruling_requests
			SET votes = votes + 1
			WHERE id = {$id}
		");
		$stmt->execute();
		$vote_updated = ($stmt->rowCount() > 0) ? true : false;
	}
	catch (Exception $e) {
		$rsp = ['response' => "Couldn't update the vote."];
	}

	// Check if vote update worked
	if ($vote_updated) {

		// Fetch current vote for this request from database
		try {
			$stmt = $pdo->query("
				SELECT votes
				FROM ruling_requests
				WHERE id = {$id}
				LIMIT 1
			");
			$rs = $stmt->fetchAll();
		}
		catch (Exception $e) {
			$rsp = ['response' => "Couldn't get current vote."];
		}

		// Get current vote
		$vote = (!empty($rs)) ? $rs[0]['votes'] : 0;
	}

	// Return success
	$rsp = [
		'response' => 'You voted.'
		,'currentVote' => $vote
	];
}
else {

	// Return error
	$rsp = ['response' => 'No id and token provided.'];
}

// Return response
echo json_encode($rsp);