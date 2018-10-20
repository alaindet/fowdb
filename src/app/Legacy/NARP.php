<?php

namespace App\Legacy;

class NARP {

	/**
	 * Accepts a card's ID, returns ID of its basic version (first print)
	 * @param  integer $id Card's ID
	 * @return integer Basic form's ID
	 */
	public static function getBasic($id) {

		$id = (int) $id;

		// Database connection
		$db = \App\Legacy\Database::getInstance();

		$narp = $db->get("SELECT narp, name FROM cards WHERE id = :id LIMIT 1", [":id" => $id], true);

		// Card is already in basic form
		if ($narp['narp'] == 0) {
			return $id;
		}

		// Card is not basic
		else {

			$basic = $db->get(
				"SELECT id FROM cards WHERE narp = 0 AND name = :name LIMIT 1",
				[":name" => $narp['name']],
				true
			);

			return (int) $basic['id'];
		}
	}
}
