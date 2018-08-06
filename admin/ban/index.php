<?php

// Check if admin is logged in
if (admin_level() > 0) {
	
	/*// Check if a create/edit/delete request was initialized by user
	if (isset($_REQUEST['action'])) {

		// Select action to take
		switch($_REQUEST['action']) {

			// Create
			case 'create':
				$title = 'Create new ban'; // Title
				$action = 'create'; // Create flag
				break;

			// Edit
			case 'update':
				$title = "Update ban <small>{$_REQUEST['code']}</small>"; // Title
				$action = 'update'; // Edit flag
				break;

			// Delete
			case 'delete':
				$title = "Delete this ban from {$_REQUEST['code']} card?";
				$action = 'delete'; // Delete flag
				break;

			// Default
			default:
				$title = "No valid action passed";
				$action = "default";
				break;
		}

		// Include form
		include 'admin/ban/form.html.php'; // Include form
	}

	// TEST - SwiftMailer
	else */if (isset($_GET['swiftmailer'])) {

		// Load autoload
		require '_components/swiftmailer/swiftmailer/lib/swift_required.php';

		// Instantiate transport (?)
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
	      ->setUsername('alain.det@gmail.com')
	      ->setPassword('barmamerna31');

	   	// Instantiate mailer
	    $mailer = Swift_Mailer::newInstance($transport);

	    // Set message
	    $message = Swift_Message::newInstance('Wonderful Subject')
	      ->setFrom(array('alain.det@gmail.com' => 'FoWDB Mittente'))
	      ->setTo(array('astonish7@gmail.com'=> 'FoWDB Destinatario'))
	      ->setBody('Testo completamente a <strong>caso</strong>');

	    // Send message
	    $result = $mailer->send($message);
	    
	    // Dump result
	    var_dump($result);
	    echo "SwiftMailer response: " . $result;
	}

	// No action required, so 'read' is assumed
	else {

		/*// Retrieve bans from DB
		include 'admin/ban/bans.sql.php';
	
		// TEST
		echo "<pre>POST: " . print_r($_POST, true) . "</pre>";

		// Show list of all bans
		include 'admin/ban/bans.html.php';*/
		echo "Page: admin/ban";
	}
}
else {
	
	// Request login
	echo '<div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>';
}
