<?php

$cards = [];
$db = \App\Database::getInstance();
$spoilers = \App\Helpers::get('spoiler');

foreach ($spoilers['sets'] as &$set) {

	$setCards = $db->get(
		"SELECT
			id,
			back_side,
			cardcode,
			setcode,
			cardnum,
			cardname,
			cardtype,
			image_path,
			thumb_path
		FROM cards
		WHERE setcode = :setcode
		ORDER BY id DESC",
		[":setcode" => $set['code']]
	);

	// Count spoiled cards avoiding double count for cards with a back side
	if (! empty($setCards)) {
		$spoiled = array_reduce($setCards, function ($total, $card) {
		    return ($card['back_side'] == "0") ? ++$total : $total;
		});
	} else {
		$spoiled = 0;
	}

	// Prepend new values (last is on top!)
	array_unshift($cards, array_merge($set, ['spoiled' => $spoiled, 'cards' => $setCards]));
}

if (empty($cards)) {
	notify('No spoiler cards found.', 'danger');
}
