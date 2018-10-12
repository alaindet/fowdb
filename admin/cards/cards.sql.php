<?php

// Declare cards array
$cards = [];


// DEFAULTS -------------------------------------------------------------------
$orderby = 'setnum, cardnum'; // Show SETNUM > CARDNUM order as default
$sql_filter = "NOT(block=1)";


// SORTING --------------------------------------------------------------------

// Check if user selected a sorting
if (isset($_POST['sort_cards'])) {

	// Alias sorting
	$sort = $_POST['sort_cards'];

	// Check if descending order is checked
	(isset($_POST['sort_desc']) AND $_POST['sort_desc'] == 'desc')
		? $dir = "DESC"
		: $dir = "ASC";

	// Custom ORDER BY clauses for attribute, rarity and cardtype
	switch($sort) {

		// Code (default)
		case '0':
			$orderby = "setnum {$dir}, cardnum";
			break;

		// Attribute
		case 'attribute':
			$orderby = "FIELD(attribute, 'w', 'r', 'u', 'g', 'b', 'v') {$dir}, id";
			break;

		// Card type
		case 'cardtype':
			
			// Generate a list of all card types
			$types = \App\Helpers::get('types');
			$types_list = "";
			for($i = 0, $len = count($types); $i < $len; $i++) {
				// Add type to list (avoid ", " if last element)
				$i == $len - 1
					? $types_list .= "'{$types[$i]}'"
					: $types_list .= "'{$types[$i]}', ";
			}
			
			// Set ORDER BY clause
			$orderby = "FIELD(cardtype, {$types_list}) {$dir}, id";
			break;

		// For set, num, race, totcost, ATK and DEF a custom ORDER BY is not needed
		default:
			$orderby = "{$sort} {$dir}, setnum, cardnum";
			break;
	}
}


// PAGINATION -----------------------------------------------------------------

// Set cards per page to view
$items_per_page = 40;

// Get requested page
$page = (isset($_POST['page']) AND $_POST['page'] > 0) ? (int)$_POST['page'] : 1;

// Instantiate Pagination class
$pagination = new \App\Pagination(
	'cards'
	,$page
	,$items_per_page
	,['sort_cards', 'sort_desc', 'include_valhalla', 'no_mobile']
);

// Get offsets
$offsets = $pagination->offsets();
$limit_down = $offsets['down'];


// QUERY THE DATABASE ---------------------------------------------------------

// Build SQL statement
$sql = "SELECT
			id,
			narp,
			block,
			setnum,
			setcode,
			cardcode,
			attribute,
			cardtype,
			divinity,
			totalcost,
			cardname,
			SUBSTRING(cardtext, 1, 75) as cardtext,
			atk,
			def
		FROM
			cards
		WHERE
			{$sql_filter}
		ORDER BY
			{$orderby}
		LIMIT
			{$limit_down}, {$items_per_page}";

// Execute query on DB
try {
	$query = $pdo->query($sql);
	$result = $query->fetchAll(\PDO::FETCH_ASSOC);
}
catch (\PDOException $e) {
	die($e->getMessage());
}


// GET RESULTS
// ---------------------------------------------------------------------

// Check if no empty results were returned
if (!empty($result)) {
	
	// Convert booleans to 'Yes' or 'No' for presentation
	$bool = [
		0 => 'No',
		1 => '<strong style="color:red;">Yes</strong>'
	];
	
	// Abbreviations for card type
	$type_abbr = array (
		"Ruler" => 'RULR',
		"J-Ruler" => 'JRUL',
		"Resonator" => 'RESR',
		"Resonator (Shift)" => 'RESR',
		"Chant" => "CHNT",
		"Spell:Chant" => 'S:CHNT',
		"Spell:Chant-Instant" => 'S:INST',
		"Spell:Chant-Standby" => 'S:STDBY',
		"Addition" => "ADD",
		"Addition:Resonator" => 'ADD:RES',
		"Addition:J/Resonator" => 'ADD:JRES',
		"Addition:Ruler/J-Ruler" => 'ADD:JRL',
		"Addition:Field" => 'ADD:FLD',
		"Regalia" => 'REGL',
		"Regalia (Shift)" => 'REGL',
		"Magic Stone" => 'MAG',
		"Special Magic Stone" => 'MAGSP',
		"Special Magic Stone/True Magic Stone" => 'MAGTR'
	);

	// Loop on each card result
	foreach($result as &$row) {

		// If there's a slash / into attribute, process multiple attributes
		strpos($row['attribute'], "/") != FALSE
			? $attribute = explode('/', $row['attribute'])
			: $attribute = array($row['attribute']);

		// Store results in $cards[] array
		$cards[] = array (
			'id' => $row['id'],
			'narp' => $row['narp'],
			'block' => $row['block'],
			'setnum' => $row['setnum'],
			'setcode' => $row['setcode'],
			'cardcode' => $row['cardcode'],
			'attribute' => $attribute,
			'cardtype' => $type_abbr[$row['cardtype']],
			'totalcost' => $row['totalcost'],
			'cardname' => $row['cardname'],
			'cardtext' => "{$row['cardtext']}[...]",
			'atkdef' => "{$row['atk']}/{$row['def']}"
		);
	}
}