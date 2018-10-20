<?php

$db = \App\Legacy\Database::getInstance();

// EDIT OR DELETE -------------------------------------------------------------

// Check if ruling ID was passed
if (isset($_REQUEST['id'])) {

	if ($ruling = $db->get(
		"SELECT
			rulings.id as id,
			rulings.cards_id,
			cards.cardname,
			cards.code,
			cards.image_path,
			cards.thumb_path,
			rulings.created,
			rulings.is_edited,
			rulings.is_errata,
			rulings.ruling
		FROM rulings INNER JOIN cards ON cards_id = cards.id
		WHERE rulings.id = :id",
		[":id" => (int) $_REQUEST['id']],
		true // Return first result
	)) {
		//$ruling['is_errata'] = $ruling['is_errata'] ? ' checked=true' : '';
	} else {
		$ruling = [];
	}
}

// CREATE ---------------------------------------------------------------------

else if ($action == 'create') {

	if (isset($_GET['card_id'])) {

		$card_id = \App\Legacy\NARP::getBasic((int) $_GET['card_id']);

		if ($card = $db->get(
			"SELECT cardname, code, thumb_path
			FROM cards
			WHERE id = :id",
			[":id" => $card_id],
			true
		)) {
			$ruling = [
				'id' => '',
				'cards_id' => $card_id,
				'cardname' => $card['cardname'],
				'code' => $card['code'],
				'thumb_path' => $card['thumb_path'],
				'created' => '',
				'is_edited' => '',
				'is_errata' => '',
				'ruling' => ''
			];
		} else { $ruling = []; }

	} else {
		$ruling = [
			'id' => '',
			'cards_id' => '',
			'cardname' => '',
			'code' => '',
			'thumb_path' => '',
			'created' => '',
			'is_edited' => '',
			'is_errata' => '',
			'ruling' => ''
		];
	}
}
