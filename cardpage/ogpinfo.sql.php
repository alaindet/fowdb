<?php

// ERROR: No card code provided
if (!isset($_GET['code'])) {
	notify('No card code provided.', 'warning');
	header('Location: /');
	return;
}

// Datanase connection
$db = \App\Database::getInstance();

// Get card info
$card = $db->get(
	"SELECT cardname, thumb_path
	FROM cards
	WHERE cardcode = :code
	LIMIT 1",
	[":code" => htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8')],
	true // Get first result
);

if (!empty($card)) {
	$page_title = $card['cardname'];
	$ogp_image[] = $card['thumb_path'];
} else {
	$page_title = 'Card Page';
}
