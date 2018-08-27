<?php

// Check if ruling ID was passed
if (isset($_REQUEST['id'])) {

	$db = \App\Database::getInstance();

	$card = $db->get(
		"SELECT * FROM cards WHERE id = :id LIMIT 1",
		[":id" => (int) $_REQUEST['id']],
		true
	);

	// Adjust returned card
	if (!empty($card)) {
		$card['num'] = $card['cardnum'];
		$card['attr'] = $card['attribute'];
		$card['type'] = $card['cardtype'];
		$card['attrcost'] = $card['attributecost'];
		$card['name'] = $card['cardname'];
		$card['text'] = $card['cardtext'];
		$card['imagepath'] = $card['image_path'];
		$card['thumbpath'] = $card['thumb_path'];

		unset($card['cardnum']);
		unset($card['attribute']);
		unset($card['cardtype']);
		unset($card['attributecost']);
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
		'backside'     => '',
		'narp'         => '',
		'block'        => '',
		'setnum'       => '',
		'setcode'      => '',
		'num'          => '',
		'attr'         => '',
		'type'         => '',
		'divinity'     => '',
		'rarity'       => '',
		'attrcost'     => '',
		'freecost'     => '',
		'totalcost'    => '',
		'atk'          => '',
		'def'          => '',
		'name'         => '',
		'code' 		   => '',
		'subtype_race' => '',
		'text'         => '',
		'flavortext'   => '',
		'imagepath'    => '',
		'thumbpath'    => ''
	];
}
