<?php

// Check if ruling ID was passed
if (isset($_REQUEST['id'])) {

	$card = database_old()->get(
		"SELECT * FROM cards WHERE id = :id LIMIT 1",
		[":id" => (int) $_REQUEST['id']],
		$first = true
	);

	// Adjust returned card
	if (!empty($card)) {
		$card['attr'] = $card['attribute'];
		$card['attrcost'] = $card['attribute_cost'];
		$card['imagepath'] = $card['image_path'];
		$card['thumbpath'] = $card['thumb_path'];

		unset($card['attribute']);
		unset($card['attribute_cost']);
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
		'race'         => '',
		'text'         => '',
		'flavor_text'  => '',
		'artist_name'  => '',
		'imagepath'    => '',
		'thumbpath'    => ''
	];
}
