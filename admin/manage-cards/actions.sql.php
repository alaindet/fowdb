<?php

if (isset($_POST['action'])) {

	require dirname(dirname(__DIR__)) . '/_config/config.php';

	$db = \App\Database::getInstance();

	// DELETE CARD ------------------------------------------------------------
	if ($_POST['action'] == 'delete' AND isset($_POST['id'])) {

		// Delete card from database
		$db->delete("cards", "id = :id", [":id" => (int) $_POST['id']]);
		$db->resetAutoIncrement("cards");

		// Delete card image and card thumb!
		unlink($_SERVER['DOCUMENT_ROOT']."/".$_POST['imagepath']);
		unlink($_SERVER['DOCUMENT_ROOT']."/".$_POST['thumbpath']);
		
		// Notify the user
		\App\FoWDB::notify("Card \"{$_REQUEST['name']}\" successfully deleted.", 'danger');

		// REDIRECT TO CARD LIST
		header("Location: /?p=admin/cards&menu_action=list");
	}
	

	// EDIT CARD --------------------------------------------------------------
	else if ($_POST['action'] == 'edit' && isset($_POST['id'])) {

		// Check if a valid set was passed
		if ($_POST['set'] != '0') {

			// Get set code (selected)
			$setcode = $_POST['set'];

			// SUFFIX AND BACKSIDE --------------------------------------------
			$backside = 0; // Initialize backside
			
			// J-ruler (backside = 1)
			if (!isset($_POST['backside']) && $_POST['type'] == 'J-Ruler') {
				$suffix = 'j';
				$backside = 1;
			}

			// Backsides
			elseif (isset($_POST['backside'])) {
				switch ($_POST['backside']) {
					case 'no': list($backside, $suffix) = [0, ''];   break;
					case 'j' : list($backside, $suffix) = [1, 'j'];  break;
					case 'sh': list($backside, $suffix) = [2, 'sh']; break;
					case 'jj': list($backside, $suffix) = [3, 'jj']; break;
					case 'in': list($backside, $suffix) = [4, 'in']; break;
				}
			}
			
			// Alternate
			elseif ($_POST['narp'] == 1) { $suffix = 'sr'; }
			else { $suffix = ''; }

			// Get display card number
			($_POST['cardnum'] != '')
				? $cardnum =  str_pad($_POST['cardnum'],3,'0',STR_PAD_LEFT)
				: $cardnum = '000';

			// Search set code into database
			$dbRow = $db->get(
				"SELECT id as setnum, clusters_id as block FROM sets WHERE code = :code",
			    [":code" => $setcode],
			    true
			);

			// Define set number and block
			$setnum = (int) $dbRow['setnum'];
			$block  = (int) $dbRow['block'];

			// Assemble paths
			$imagepath = "_images/cards/{$block}/{$_POST['set']}/{$cardnum}{$suffix}.jpg";
			$thumbpath = "_images/thumbs/{$block}/{$_POST['set']}/{$cardnum}{$suffix}.jpg";
		}

		// Set was not passed, get default values
		else {
			$block = '0';
			$setnum = null;
			$setcode = '';
			$imagepath = "_images/cards/missing.jpg";
			$thumbpath = "_images/thumb/missing.jpg";
		}

		// Rarity
		$rarity = $_POST['rarity'] !== 'no' ? strtolower($_POST['rarity']) : null;
		$codeRarity = isset($rarity) ? strtoupper($rarity) : '';

		// Code (If setcode and cardnum and rarity not set the code is 0-000 C)
		$code = strtoupper($_POST['set'])
				. '-' . str_pad($_POST['cardnum'], 3, '0', STR_PAD_LEFT)
				. ' ' . $codeRarity;

		// Attribute ----------------------------------------------------------
		if (
			isset($_POST['attribute']) AND
			!empty($_POST['attribute']) AND
			is_array($_POST['attribute'])
		) {
			$attribute = implode("/", $_POST['attribute']);
		} else {
			$attribute = '';
		}

		// COSTS --------------------------------------------------------------

		// Attribute cost
		(isset($_POST['attrcost']) AND $_POST['attrcost'] != '')
			? $attrcost = strtolower($_POST['attrcost'])
			: $attrcost = null;

		// Free cost (allow x costs as value = 100)
		(isset($_POST['freecost']) AND $_POST['freecost'] != '')
			? $freecost = (int)$_POST['freecost']
			: $freecost = null;

		// Total cost
		(isset($_POST['totalcost']) AND $_POST['totalcost'] != '')
			? $totalcost = (int)$_POST['totalcost']
			: $totalcost = null;

		// If total cost is not passed, calculate it
		if ($totalcost == null) {

			// If both attribute and free cost are null
			if ($attrcost == null AND !is_int($freecost)) {
				$totalcost = null;
			}
			// If only attribute cost is null
			elseif ($attrcost == null AND is_int($freecost)) {
				// If freecost / 100 < 1, totalcost = freecost as normal,
				// But if freecost / 100 = 1, then freecost = 'x' and totalcost = 0
				$totalcost = ($freecost < 0) ? 0 : $freecost;
			}
			// If only free cost is null
			elseif (
				$attrcost != null AND
				(!is_int($freecost) OR (is_int($freecost) AND $freecost == 0))
			) {
				$totalcost = strlen($attrcost);
			}
			// If both attribute cost and free cost are NOT null
			else {
				// Add attribute costs first
				$totalcost = strlen($attrcost);
				// Add free cost if it's not X (which is 100 by convention)
				if ($freecost > 0) { $totalcost += $freecost; }
			}
		}

		// Divinity
		if (isset($_POST['divinity'])) {
			$divinity = (int) $_POST['divinity'];
			$divinity = $divinity === -1 ? null : $divinity;
		} else {
			$divinity = null;
		}

		// Update the database
		$db->update("cards", [
			'id' => $_POST['id'],
			'backside' => $backside,
			'narp' => $_POST['narp'],
			'block' => $block,
			'setnum' => $setnum,
			'setcode' => $setcode,
			'cardnum' => (int) $_POST['cardnum'],
			'cardcode' => $code,
			'attribute' => $attribute,
			'cardtype' => $_POST['type'],
			'divinity' => $divinity,
			'rarity' => $rarity,
			'attributecost' => ($attrcost == null) ? null : $attrcost,
			'freecost' => $freecost,
			'totalcost' => $totalcost,
			'atk' => isset($_POST['atk']) ? (int) $_POST['atk'] : 0,
			'def' => isset($_POST['def']) ? (int) $_POST['def'] : 0,
			'cardname' => $_POST['name'],
			'subtype_race' => $_POST['race'],
			'cardtext' => $_POST['text'],
			'flavortext' => $_POST['flavortext'],
			'image_path' => $imagepath,
			'thumb_path' => $thumbpath
		], "id = :id", [":id" => $_POST['id']]);

		
		// Generate card link with card name
		$card_link = "<strong><a href='/?p=card&code="
				   . str_replace(' ', '+', $code)
				   . "' target=_blank>".$_POST['name']."</a></strong>";

		// Notify the user
		\App\FoWDB::notify("Card {$card_link} successfully edited.", 'info');

		// REDIRECT TO CARD LIST
		header("Location: /?p=admin/cards&menu_action=list");
	}
	
	
	// CREATE CARD ------------------------------------------------------------
	else if ($_POST['action'] == 'create') {

		// Check if a valid set was passed
		if ($_POST['set'] != '0') {

			// Get set code (selected)
			$setcode = $_POST['set'];

			// SUFFIX AND BACKSIDE --------------------------------------------
			$backside = 0; // Initialize backside
			
			// J-ruler (backside = 1)
			if (!isset($_POST['backside']) && $_POST['type'] == 'J-Ruler') {
				$suffix = 'j';
				$backside = 1;
			}

			// Backsides
			elseif (isset($_POST['backside'])) {
				switch ($_POST['backside']) {
					case 'no': list($backside, $suffix) = [0, ''];   break;
					case 'j' : list($backside, $suffix) = [1, 'j'];  break;
					case 'sh': list($backside, $suffix) = [2, 'sh']; break;
					case 'jj': list($backside, $suffix) = [3, 'jj']; break;
					case 'in': list($backside, $suffix) = [4, 'in']; break;
				}
			}
			
			// Alternate
			elseif ($_POST['narp'] == 1) {$suffix = 'sr';}
			else {$suffix = '';}

			// Get display card number
			($_POST['cardnum'] != '')
				? $cardnum =  str_pad($_POST['cardnum'], 3, '0', STR_PAD_LEFT)
				: $cardnum = '000';

			// Search set code into database
			$db_set = $db->get(
			    "SELECT id as setnum, clusters_id as block
			    FROM sets
			    WHERE code = :code",
			    [":code" => $setcode],
			    true
			);

			// Define set number and block
			$setnum = (int) $db_set['setnum'];
			$block  = (int) $db_set['block'];

			// Assemble paths
			$imagepath = "_images/cards/{$block}/{$_POST['set']}/{$cardnum}{$suffix}.jpg";
			$thumbpath = "_images/thumbs/{$block}/{$_POST['set']}/{$cardnum}{$suffix}.jpg";
		}

		// Set was not passed, get default values
		else {
			$block = 0;
			$setnum = null;
			$setcode = '';
			$imagepath = "_images/cards/missing.jpg";
			$thumbpath = "_images/thumb/missing.jpg";
		}

		// Rarity
		$rarity = $_POST['rarity'] !== 'no' ? strtolower($_POST['rarity']) : null;
		$codeRarity = isset($rarity) ? strtoupper($rarity) : '';

		// Code (If setcode and cardnum and rarity not set the code is 0-000)
		$code = strtoupper($_POST['set'])
				. '-' . str_pad($_POST['cardnum'], 3, '0', STR_PAD_LEFT)
				. ' ' . $codeRarity;

		// ATTRIBUTE ----------------------------------------------------------
		if (
			isset($_POST['attribute']) AND
			!empty($_POST['attribute']) AND
			is_array($_POST['attribute'])
		) {
			$attribute = implode("/", $_POST['attribute']);
		} else {
			$attribute = '';
		}

		// COSTS --------------------------------------------------------------

		// Attribute cost
		(isset($_POST['attrcost']) AND $_POST['attrcost'] != '')
			? $attrcost = strtolower($_POST['attrcost'])
			: $attrcost = null;

		// Free cost (allow x costs as value = 100)
		(isset($_POST['freecost']) AND $_POST['freecost'] != '')
			? $freecost = (int)$_POST['freecost']
			: $freecost = null;

		// Total cost
		(isset($_POST['totalcost']) AND $_POST['totalcost'] != '')
			? $totalcost = (int)$_POST['totalcost']
			: $totalcost = null;

		// If total cost is not passed, calculate it
		if ($totalcost == null) {

			// If both attribute and free cost are null
			if ($attrcost == null AND !is_int($freecost)) {
				$totalcost = null;
			}
			// If only attribute cost is null
			elseif ($attrcost == null AND is_int($freecost)) {
				// If freecost / 100 < 1, totalcost = freecost as normal,
				// But if freecost / 100 = 1, then freecost = 'x' and totalcost = 0
				$totalcost = ($freecost < 0) ? 0 : $freecost;
			}
			// If only free cost is null
			elseif ($attrcost != null AND (!is_int($freecost) OR (is_int($freecost) AND $freecost == 0))) {
				$totalcost = strlen($attrcost);
			}
			// If both attribute cost and free cost are NOT null
			else {
				// Add attribute costs first
				$totalcost = strlen($attrcost);
				// Add free cost if it's not X (which is 100 by convention)
				if ($freecost > 0) { $totalcost += $freecost; }
			}
		}

		// Divinity
		if (isset($_POST['divinity'])) {
			$divinity = (int) $_POST['divinity'];
			$divinity = $divinity === -1 ? null : $divinity;
		} else {
			$divinity = null;
		}

		// Insert into the database
		$db->insert("cards", [
			'narp' => $_POST['narp'],
			'backside' => $backside,
			'block' => $block,
			'setnum' => $setnum,
			'setcode' => $setcode,
			'cardnum' => (int) $_POST['cardnum'],
			'cardcode' => $code,
			'attribute' => $attribute,
			'cardtype' => $_POST['type'],
			'divinity' => $divinity,
			'rarity' => $rarity,
			'attributecost' => ($attrcost == null) ? null : $attrcost,
			'freecost' => $freecost,
			'totalcost' => $totalcost,
			'atk' => isset($_POST['atk']) ? (int) $_POST['atk'] : 0,
			'def' => isset($_POST['def']) ? (int) $_POST['def'] : 0,
			'cardname' => $_POST['name'],
			'subtype_race' => $_POST['race'],
			'cardtext' => $_POST['text'],
			'flavortext' => $_POST['flavortext'],
			'image_path' => $imagepath,
			'thumb_path' => $thumbpath
		]);

		// // Store card images
		// $_imagepath = \App\FoWDB::processImage(
		// 	$_FILES['cardimage'], APP_ROOT."/".$imagepath, 'hq',
		// 	true // Watermark
		// );

		// $_thumbpath = \App\FoWDB::processImage(
		// 	$_FILES['cardimage'], APP_ROOT."/".$thumbpath, 'lq',
		// 	true // Watermark
		// );
		
		
		// Create image
		(new \Intervention\Image\ImageManager())
	        ->make($_FILES['cardimage']['tmp_name'])
	        ->resize(480, 670)
	        ->insert(APP_ROOT."/_images/watermark/watermark480.png")
	        ->save(APP_ROOT."/".$imagepath, 80);

		// Create thumbnail image
		(new \Intervention\Image\ImageManager())
	        ->make($_FILES['cardimage']['tmp_name'])
	        ->resize(280, 391)
	        ->insert(APP_ROOT."/_images/watermark/watermark280.png")
	        ->save(APP_ROOT."/".$thumbpath, 80);

		// Generate card link with card name
		$card_link = "<strong><a href='/?p=card&code="
				   . str_replace(' ', '+', $code)
				   . "' target=_blank>".$_POST['name']."</a></strong>";

		// Notify the user
		\App\FoWDB::notify("New card {$card_link} successfully added.", 'success');

		// Redirect user to Create new card
		header("Location: /?p=admin/cards&form_action=create");
	}
}
