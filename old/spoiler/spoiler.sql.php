<?php

$cards = [];

foreach (cached('spoiler.sets') as $set) {

	$setCards = database_old()->get(
		"SELECT
			id,
			back_side,
			code,
			setcode,
			num,
			name,
			type,
			image_path,
			thumb_path
		FROM cards
		WHERE setcode = :setcode
		ORDER BY id DESC",
		[":setcode" => $set['code']]
	);

	
	// Count just base faces
	$spoiled = 0;
	if (!empty($setCards)) {
		for ($i = 0, $len = count($setCards); $i < $len; $i++) {
			if ($setCards[$i]['back_side'] === '0') $spoiled++;
		}
	}

	// Add 'spoiled' and 'cards' elements to set
	$set = array_merge($set, [ 'spoiled' => $spoiler, 'cards' => $setCards ]);

	// Add this set to existing sets, on top
	array_unshift($cards, $set);
}
