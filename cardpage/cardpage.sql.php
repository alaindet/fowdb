<?php

use \App\Models\Ban;
use \App\Models\Card;
use \App\Models\CardNarp;
use \App\Models\Ruling;
use \App\Utils\Arrays;
use \App\Views\Card\Card as CardView;

$code = htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8');
$cardsDb = Card::getByCode($code);

// ERROR: No card with that code!
if (empty($cardsDb)) {
	notify("No card found with code <strong>{$code}</strong>", 'warning');
	redirect('/');
}

$results = true;
$cards = [];

foreach ($cardsDb as &$card) {
	
	// $type ------------------------------------------------------------------
	$reminder = ($card['back_side'] === '2') ? ' (Shift)' : '';
	$link = "/?do=search&cardtype[]={$card['cardtype']}";
	$type = "<a href=\"{$link}\">{$card['cardtype']}</a>{$reminder}";
	
	// $freecost --------------------------------------------------------------
	$freecost = '';
	if (!empty($card['freecost'])) {
		if ($card['freecost'] > 0) {
			$freecost = render('{'.$card['freecost'].'}');
		} else {
			$xCosts = '';
			for ($i = $card['freecost']; $i < 0; $i++) {
				$xCosts .= '{x}';
			}
			$freecost = render($xCosts);
		}
	}

	// $attributecost ---------------------------------------------------------
	$attributecost = '';
	if (!empty($card['attributecost'])) {
		$attributecost = array_reduce(
			str_split($card['attributecost']),
			function ($tot, $attr) { return $tot .= render('{'.$attr.'}'); },
			''
		);
	}
	
	// $cost ------------------------------------------------------------------
	$cost = empty($card['totalcost']) ? '0' : $attributecost . $freecost;
	
	// $attribute -------------------------------------------------------------
	$attribute = '';
	if (!empty($card['attribute'])) {

		// Build attribute html
		// Ex.: [ICON] Fire, [ICON] Dark
		$attributesMap = cached('attributes');
		$attributes = array_map(function ($attribute) use ($attributesMap) {
			return collapse(
				"<a href=\"/?do=search&attributes[]={$attribute}\">",
					"<img ",
						"src=\"images/icons/1x1.gif\" ",
						"class=\"fdb-icon-{$attribute}\"",
					"/>&nbsp;",
					$attributesMap[$attribute],
				"</a>"
			);
		}, explode('/', $card['attribute']));

		// Remove last comma
		$attribute = implode(', ', $attributes);
	}
	
	// $raceLabel -------------------------------------------------------------
	$raceTypes = ['Ruler', 'J-Ruler', 'Resonator'];
	$raceLabel = in_array($card['cardtype'], $raceTypes) ? 'race' : 'trait';

	// $raceValue -------------------------------------------------------------
	$raceValue = '<em>(none)</em>';
	if (!empty($card['subtype_race'])) {
		$raceValue = implode(' / ', array_map(
			function ($race) {
				return "<a href=\"/?do=search&race={$race}\">{$race}</a>";
			},
			explode('/', $card['subtype_race'])
		));
	}
	
	// $set -------------------------------------------------------------------
	$set = collapse(
		"<a href='/?do=search&setcode={$card['setcode']}'>",
			strtoupper($card['setcode']), ' - ',
			cached("clusters.{$card['clusters_id']}.sets.{$card['setcode']}"),
		"</a>"
	);

	// $artist ----------------------------------------------------------------
	$artist = null;
	if (isset($card['artist_name'])) {
		$artist = collapse(
			"<a href='/?do=search&artist={$card['artist_name']}'>",
				$card['artist_name'],
			"</a>"
		);
	}

	// $baseCardId ------------------------------------------------------------
	($card['narp'] === 0)
		? $baseCardId = (int) $card['id']
		: $baseCardId = CardNarp::getBaseIdByName($card['cardname']);
	
	// $format, $banned -------------------------------------------------------
	$spoilers = cached('spoiler.codes');
	if (!empty($spoilers) && in_array($card['setcode'], $spoilers)) {
		$format = '<span class="mark_spoiler">Spoiler</span>';
		$banned = '';
	}

	// Not a spoiler card, check the banned list
	else {
		
		// This card's formats
		// Ex.: (assoc) [ [format_name, format_code], ... ]
		$cardFormats = CardView::formatsListByCluster($card['clusters_id']);

		// Banned in these formats (can be empty, most of the times)
		// Ex.: (assoc) [ [format_name, deck_name, copies_in_deck], ... ]
		$bannedFormats = Ban::formatsList($baseCardId);

		// Is this banned?
		if (!empty($bannedFormats)) {

			// Exlude banned formats
			// Ex.: (assoc) [ [format_name, format_code] ]
			$bannedFormatsNames = array_column($bannedFormats, 'name');
			$cardFormats = array_filter(
				$cardFormats,
				function ($format) use ($bannedFormatsNames) {
					return in_array($format['name'], $bannedFormatsNames);
				}
			);

			// Built HTML list of banned formats
			// Ex.: (no extra) New Frontiers
			// Ex.: (extra) New Frontiers (Rune Deck, 1 copy)
			$bannedHtml = implode(', ', array_map(function ($ban) {
				$extra = Arrays::filterNull( [$ban['deck'], $ban['copies']] );
				return collapse(
					"<span style=\"color:red;\">",
						"<strong>",$ban['format'],"</strong>","&nbsp",
					"</span>",
					"<em>",
						!empty($extra) ? '('.implode(', ', $extra).')' : '',
					"</em>"
				);
			}, $bannedFormats));
		}

		// $format, $banned ---------------------------------------------------
		$format = CardView::displayFormats($cardFormats);
		$banned = $bannedHtml ?? '';
	}
	
	// $rulings ---------------------------------------------------------------
	$rulings = Ruling::getByCardId($baseCardId, $render = true);

	// $narp ------------------------------------------------------------------
	$narp = CardNarp::displayRelatedCards(
		(int) $card['narp'],
		$card['cardname']
	);

	// $rarity ----------------------------------------------------------------
	$rarity = '<em>(none)</em>';
	if (isset($card['rarity'])) {
		$rarity = collapse(
			"<a href='/?do=search&rarity[]={$card['rarity']}'>",
				strtoupper($card['rarity']), ' - ',
				cached("rarities.{$card['rarity']}"),
			"</a>"
		);
	}

	// $flavorText ------------------------------------------------------------
	$flavorText = "<span class='flavortext'>{$card['flavortext']}</span>";

	// $atkDef ----------------------------------------------------------------
	$atkDef = collapse(
		"<span class=\"font-150 text-italic\">",
			"{$card['atk']} / {$card['def']}",
		"</span>"
	);

	// Backup card type before overwriting $cards
	$_type = $card['cardtype'];

	// $card ------------------------------------------------------------------
	$card = [
		// Shown info (side panel)
		'name' => $card['cardname'],
		'cost' => $cost,
		'total_cost' => $card['totalcost'],
		'atk_def' => $atkDef,
		'divinity' => $card['divinity'],
		'type' => $type,
		$raceLabel => $raceValue,
		'attribute' => $attribute,
		'text' => render($card['cardtext']),
		'flavor_text' => $flavorText,
		'code' => $card['cardcode'],
		'rarity' => $rarity,
		'artist_name' => $artist,
		'set' => $set,
		'format' => $format,
		'banned' => $banned,
		// Extra info
		'id' => $card['id'],
		'back_side' => $card['back_side'],
		'narp' => $narp,
		'image_path' => $card['image_path'],
		'thumb_path' => $card['thumb_path'],
		'rulings' => $rulings,
	];

	// Remove optional properties for some card types -------------------------
	if (empty($card['banned'])) unset($card['banned']);
	if (empty($card['divinity'])) unset($card['divinity']);
	if (empty($card['flavor_text'])) unset($card['flavor_text']);

	// Filter out some props based on card type
	$cards[] = CardView::removeIllegalProps($card, $_type);
}

// Add the display property on each card
CardView::addDisplay($cards);
