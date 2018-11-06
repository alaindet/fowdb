<?php

$cards = [];

$spoilers = lookup('spoilers.sets');
$map = lookup('sets.code2id');
foreach ($spoilers as &$spoiler) $spoiler['id'] = $map[$spoiler['code']];

foreach ($spoilers as $spoilerSet) {

	$spoilerSetCards = database()
		->select(statement('select')
			->select([
				'id',
				'back_side',
				'code',
				'num',
				'name',
				'type',
				'image_path',
				'thumb_path'
			])
			->from('cards')
			->where('sets_id = :setid')
			->orderBy('id DESC')
		)
		->bind([':setid' => $spoilerSet['id']])
		->get();

	// Count just base faces
	$spoiledCounter = 0;
	if (!empty($spoilerSetCards)) {
		foreach ($spoilerSetCards as $card) {
			if ($card['back_side'] === '0') $spoiledCounter++;
		}
	}

	// Add 'spoiled' and 'cards' elements to set
	$spoilerSet = array_merge($spoilerSet, [
		'spoiled' => $spoiledCounter,
		'cards' => $spoilerSetCards
	]);

	// Add this set to existing sets, on top
	array_unshift($cards, $spoilerSet);
}
