<?php

// Check if ruling ID was passed
if (isset($_REQUEST['id'])) {

	$card = database()->get(
		"SELECT * FROM cards WHERE id = :id LIMIT 1",
		[":id" => (int) $_REQUEST['id']],
		$first = true
	);

	// Adjust returned card
	if (!empty($card)) {
		$card['num'] = $card['num'];
		$card['attr'] = $card['attribute'];
		$card['type'] = $card['type'];
		$card['attrcost'] = $card['attribute_cost'];
		$card['name'] = $card['cardname'];
		$card['code'] = $card['code'];
		$card['text'] = $card['cardtext'];
		$card['imagepath'] = $card['image_path'];
		$card['thumbpath'] = $card['thumb_path'];

		unset($card['attribute']);
		unset($card['attribute_cost']);
		unset($card['cardname']);
		unset($card['cardtext']);
		unset($card['image_path']);
		unset($card['thumb_path']);
	}
}

// Else return empty array
else {
	$card = [
		'id'           => '',
		'back_side'    => '',
		'narp'         => '',
		'clusters_id'  => '',
		'sets_id'      => '',
		'setcode'      => '',
		'num'          => '',
		'attr'         => '',
		'type'         => '',
		'divinity'     => '',
		'rarity'       => '',
		'attrcost'     => '',
		'free_cost'    => '',
		'total_cost'   => '',
		'atk'          => '',
		'def'          => '',
		'name'         => '',
		'code' 		   => '',
		'subtype_race' => '',
		'text'         => '',
		'flavortext'   => '',
		'artist_name'  => '',
		'imagepath'    => '',
		'thumbpath'    => ''
	];
}
