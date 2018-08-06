<?php

$db = \App\Database::getInstance();

$races = [];
if ($results = $db->get(
	"SELECT DISTINCT subtype_race
	FROM cards
	WHERE cardtype IN('Ruler', 'J-Ruler', 'Resonator')"
)) {
	foreach ($results as &$raceString) {
		foreach (explode("/", $raceString['subtype_race']) as $race) {
			if (! in_array($race, $races)) {
				$races[] = $race;
			}
		}
	}
}
sort($races);

$traits = [];
if ($results = $db->get(
	"SELECT DISTINCT subtype_race
	FROM cards
	WHERE NOT (cardtype IN('Ruler','J-Ruler','Resonator')) AND NOT (subtype_race='')"
)) {
	foreach ($results as &$traitString) {
		foreach (explode("/", $traitString['subtype_race']) as $trait) {
			if (! in_array($trait, $traits)) {
				$traits[] = $trait;
			}
		}
	}
}
sort($traits);
