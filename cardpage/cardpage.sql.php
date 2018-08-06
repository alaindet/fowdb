<?php

$attributes = \App\Helpers::get("attributes");
$spoilers = \App\Helpers::get("spoiler.codes");

// ERROR: No card code passed
if (!isset($_GET['code'])) {
	throw new \Exception(
		"No card code provided. Please provide a card code and try again."
	);
	exit();
}

// Database connection
$db = \App\Database::getInstance();

// Get card(s) from db
$result = $db->get(
	"SELECT
		cards.id,
		cards.backside,
		cards.narp,
		cards.block,
		cards.setnum,
		cards.setcode,
		cards.cardnum,
		cards.cardcode,
		cards.attribute,
		cards.cardtype,
		cards.rarity,
		cards.attributecost,
		cards.freecost,
		cards.totalcost,
		cards.atk,
		cards.def,
		cards.cardname,
		cards.subtype_race,
		cards.cardtext,
		cards.flavortext,
		cards.image_path,
		cards.thumb_path,
		sets.name as setname
	FROM
		cards INNER JOIN sets ON cards.setnum = sets.id
	WHERE cardcode = :code
	LIMIT 3",
	[":code" => htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8')]
);

// Check if there were results
if (!empty($result)) {
	
	// Set results flag to true and initialize cards container array
	$results = true;
	$cards = array();
	
	// Populate $cards while processing of every card retrieved
	// Only one loop for most of cards, two loops for Ruler/J-Ruler couples
	foreach ($result as &$row) {
		
		// REMINDER -----------------------------------------------------------
		
		switch ($row['backside']) {
			case 2:
				$reminder = ' (Shift)';
				break;
			default:
				$reminder = '';
				break;
		}

		// COST ---------------------------------------------------------------

		if (!empty($row['freecost'])) {
			if ($row['freecost'] < 0) {
				$temp = '';
				for ($i = $row['freecost']; $i < 0; $i++) {
					$temp .= '{x}';
				}
				$freecost = \App\FoWDB::renderText($temp);
			} else {
				$freecost = \App\FoWDB::renderText('{'.$row['freecost'].'}');
			}
		} else {
			$freecost = '';
		}

		// Check if there's an attribute cost,
		// Then convert it into will symbol icon,
		// Else leave it blank
		$attributecost = ''; // Initialize empty attribute cost
		if (!empty($row['attributecost'])) {
			
			// Return an array of attributes (1 character = 1 array element)
			// From db string of attributecost field
			$attrcost = str_split($row['attributecost']);

			// Insert a will symbol <img> tag for each will occurrence in attribute cost
			foreach($attrcost as &$attr) {
				$attributecost .= \App\FoWDB::renderText('{'.$attr.'}');
			}
		}
		
		// If total cost is 0, displayed cost is 0,
		// Else assemble display cost
		$cost = empty($row['totalcost']) ? '0' : $attributecost . $freecost;
		
		// ATTRIBUTE ----------------------------------------------------------
		
		// Check if attribute is set
		if (!empty($row['attribute'])) {

			// Init attribute string
			$temp = [];

			// Build attribute string
			foreach (explode("/", $row['attribute']) as &$attr) {
				$temp[] = "<a href='/?do=search&attributes[]={$attr}'><img src='_images/icons/1x1.gif' class='fdb-icon-{$attr}'>&nbsp;{$attributes[$attr]}</a>";
			}

			// Remove last comma
			$attribute = implode(", ", $temp);
		}

		// If attribute is not set, like Magic Stones, display attr is "no"
		else {
			$attribute = '';
		}
		
		// RACE AND TRAIT -----------------------------------------------------
		 
		// If Ruler, J-Ruler or Resonator, label is "Race"
		in_array($row['cardtype'], ['Ruler', 'J-Ruler', 'Resonator'])
			? $race_trait_label = 'race'
			: $race_trait_label = 'trait';
		
		// If there's a subtype or race, display its value, else display (none)
		if (!empty($row['subtype_race'])) {
			$races = [];
			foreach (explode("/", $row['subtype_race']) as $race) {
				$races[] = "<a href='/?do=search&race={$race}'>{$race}</a>";
			}
			$race_trait_value = implode(" / ", $races);
		} else {
			$race_trait_value = "<em>(none)</em>";
		}
		
		// SET ----------------------------------------------------------------
		$set = "<a href='/?do=search&setcode={$row['setcode']}'>".strtoupper($row['setcode'])." - ".$row['setname']."</a>";
		
		// FORMAT AND BANNED --------------------------------------------------
		
		// Check if it's a spoiler card, then display Spoiler on format
		if (!empty($spoilers) AND in_array($row['setcode'], $spoilers)) {
			$format = '<span class="mark_spoiler">Spoiler</span>';
			$banned = '';
		}

		// If it's not a spoiler card, build card formats
		else {
			// Will hold all card's formats
			$cardFormats = [];

			// Get array of banned (empty array if not banned)
			$bannedFormats = FoWDB_banned($db, $row['id']);

			// Loop through all formats
			foreach (\App\Helpers::get('formats.list') as $fcode => &$fdata) {
				if (in_array($row['block'], $fdata['clusters'])) {
					$cardFormats[] = "<a href='/?do=search&format={$fcode}''>".$fdata['name']."</a>";
				}
			}

			if (!empty($bannedFormats)) {
				// Build banned formats display string
				$banned = "<strong style='color:red;'>".implode(", ", $bannedFormats)."</strong>";
				// Build format display string (show only formats in which not banned)
				$format = implode(", ", array_diff($cardFormats, $bannedFormats));
			}
			else {
				// Build format display string
				$format = implode(", ", $cardFormats);
				$banned = '';
			}
		}
		
		
		// RULINGS ------------------------------------------------------------
		
		// Check if this card is a NARP
		if ($row['narp'] > 0) {

			// Get "normal" card ID (NARP=0) from database
			$normalCardId = $db->get(
				"SELECT id
				FROM cards
				WHERE narp = 0 AND cardname = :name
				LIMIT 1",
				[":name" => $row['cardname']],
				true // Return just first row
			)['id'];

		// Card is already a "normal" card
		} else { $normalCardId = $row['id']; }

		// Get rulings form normal card ID
		$_rulings = $db->get(
			"SELECT id, created, is_edited, is_errata, ruling
			FROM rulings
			WHERE cards_id = :id
			ORDER BY is_errata DESC, created DESC",
			[":id" => $normalCardId]
		);
		
		// Initialize rulings array
		$rulings = [];
		
		// Check for results
		if (!empty($_rulings)) {
		
			// Populate rulings array
			foreach ($_rulings as &$ruling) {
				// Render ruling text
				$ruling['ruling'] = \App\FoWDB::renderText($ruling['ruling']);
				// Store every ruling info as element of $rulings[] array
				$rulings[] = $ruling;
			}
		}

		// Store every card info in $card[] array
		$card = array (
			'id' => $row['id'],
			'backside' => $row['backside'],
			'narp' => FoWDB_narp($db, $row['narp'], $row['cardname']),
			'cardname' => $row['cardname'],
			'cost' => $cost,
			'totalcost' => $row['totalcost'],
			'attribute' => $attribute,
			'cardtype' => $row['cardtype'] . $reminder,
			$race_trait_label => $race_trait_value,
			'cardtext' => \App\FoWDB::renderText($row['cardtext']),
			'atk_def' => $row['atk'] . ' / ' . $row['def'],
			'set' => $set,
			'cardcode' => $row['cardcode'],
			'rarity' => isset($row['rarity']) ? strtoupper($row['rarity']) : '<em>(none)</em>',
			'format' => $format,
			'banned' => $banned,
			'flavortext' => "<span class='flavortext'>" . $row['flavortext'] . "</span>",
			'image_path' => $row['image_path'],
			'thumb_path' => $row['thumb_path'],
			'rulings' => $rulings,
		);
	
		// Remove unwanted properties for some card types ---------------------

		// Remove banned from cards that are not banned
		if (empty($card['banned'])) { unset($card['banned']); }

		// Remove flavor text from cards that don't have it
		if ($row['flavortext'] == "") { unset($card['flavortext']); }

		// Remove cost and total cost
		if (in_array($row['cardtype'],[
			'Ruler',
			'J-Ruler',
			'Magic Stone',
			'Special Magic Stone',
			'Special Magic Stone/True Magic Stone'
		])) {
			unset($card['cost']);
			unset($card['totalcost']);
		}

		// Remove ATK and DEF
		if (in_array($row['cardtype'],[
			'Ruler',
			'Addition',
			'Addition:Resonator',
			'Addition:J/Resonator',
			'Addition:Ruler/J-Ruler',
			'Addition:Field',
			'Chant',
			'Spell:Chant',
			'Spell:Chant-Instant',
			'Spell:Chant-Standby',
			'Regalia',
			'Magic Stone',
			'Special Magic Stone',
			'Special Magic Stone/True Magic Stone'
		])) {
			unset($card['atk_def']);
		}

		// Remove attribute
		if (in_array($row['cardtype'],[
			'Magic Stone',
			'Special Magic Stone',
			'Special Magic Stone/True Magic Stone'
		])) {
			unset($card['attribute']);
		}

		// Add generated card to cards array
		$cards[] = $card;
	}
}

// If no card corresponds to the passed code
else { throw new \Exception("No card found with code <strong>{$_GET['code']}</strong>"); }


/**
 * This function gets the NARP value of a card
 *
 * NARP stands for Normal-Alternate-Reprint-Promo and
 * that's a reminder of integer values of the narp table field
 * (0 = Normal, 1 = Alternate, 2 = Reprint, 3 = Promo)
 *
 * @param obj $_db The Database object
 * @param int $_narp The NARP integer value (see above)
 * @param int $_name The card name
 * @return array
 */
function FoWDB_narp($_db, $_narp, $_name)
{
	// Initialize response (basic card, no ARPs)
	$response = ['flag' => 0, 'cards' => []];
	
	// Helper array for HTML view
	$helper = ['Basic', 'Alternate art', 'Reprint', 'Promo'];
	
	if ($_narp == 0) { // Card is BASE PRINT (normal)

		// Get ARPs for this Normal
		$arps = $_db->get(
			"SELECT narp, cardcode
			FROM cards
			WHERE narp > 0 AND cardname = :name
			ORDER BY setnum DESC, cardnum ASC",
			[":name" => $_name]
		);

		// This card is basic (N), with *NO* ARPs
		if (empty($arps)) {
			$response['flag'] = 0;
		}

		// This card is basic (N), with ARPs
		else {
			$response['flag'] = 1;

			foreach($arps as &$arp) {
				$name = $helper[$arp['narp']].'s';
				$response['cards'][$name][] = $arp['cardcode'];
			}
		}

	// Input card was an ARP
	} else {

		$response['flag'] = 2;

		// Get Normal of this ARP
		$normal = $_db->get(
			"SELECT narp, cardcode
			FROM cards
			WHERE narp = 0 AND cardname = :name",
			[":name" => $_name],
			true
		);

		// Check if there was the Normal card
		if (!empty($normal)) {

			// Store Normal card
			$name = $helper[$normal['narp']];
			$response['cards'][$name][] = $normal['cardcode'];

		// Strange: an ARP without its Normal
		} else {
			throw Exception("This card is a {$helper[$narp]} with no base card.");
		}
	}
	
	// 0 = normal card, no ARPs
	// 1 = normal card, has ARPs
	// 2 = ARP card
	return $response;
}


/**
 * Accepts card ID, returns list of formats in which card is banned
 *
 * @param obj $_db The Database object
 * @param int $_id The card ID to be tested
 * @return array of strings
 */
function FoWDB_banned($_db, $_id)
{

	// Will hold al banned formats for this card
	$bannedFormats = [];

	// Get list of formats
	$results = $_db->get(
		"SELECT formats.name as formats_name
		FROM bans INNER JOIN formats ON bans.formats_id = formats.id
        WHERE cards_id = :id
        ORDER BY formats.id",
        [":id" => $_id]
	);

	// Check if card is actually banned in some format
	if (!empty($results)) {
		foreach($results as &$r) {
			$bannedFormats[] = $r['formats_name'];
		}
	}

	return $bannedFormats;
}
