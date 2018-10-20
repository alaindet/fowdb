<?php

// Races ----------------------------------------------------------------------
$racesDb = database()->get(
	"SELECT DISTINCT subtype_race
	FROM cards
	WHERE cardtype IN('Ruler', 'J-Ruler', 'Resonator')"
);
$races = [];
foreach ($racesDb as &$item) {
	foreach (explode('/', $item['subtype_race']) as $race) {
		if (!isset($races[$race])) $races[$race] = 1;
	}
}
$races = array_keys($races);
sort($races);

// Traits ---------------------------------------------------------------------
$traitsDb = database()->get(
	"SELECT DISTINCT subtype_race
	FROM cards
	WHERE
		NOT (cardtype IN('Ruler','J-Ruler','Resonator')) AND
		NOT (subtype_race = '')"
);
$traits = [];
foreach ($traitsDb as &$item) {
	foreach (explode('/', $item['subtype_race']) as $trait) {
		if (!isset($traits[$trait])) $traits[$trait] = 1;
	}
}
$traits = array_keys($traits);
sort($traits);
