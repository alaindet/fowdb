<?php

// Races ----------------------------------------------------------------------
$racesDb = database()->get(
	"SELECT DISTINCT race
	FROM cards
	WHERE type IN('Ruler', 'J-Ruler', 'Resonator')"
);
$races = [];
foreach ($racesDb as &$item) {
	foreach (explode('/', $item['race']) as $race) {
		if (!isset($races[$race])) $races[$race] = 1;
	}
}
$races = array_keys($races);
sort($races);

// Traits ---------------------------------------------------------------------
$traitsDb = database()->get(
	"SELECT DISTINCT race
	FROM cards
	WHERE
		NOT (type IN('Ruler','J-Ruler','Resonator')) AND
		NOT (race = '')"
);
$traits = [];
foreach ($traitsDb as &$item) {
	foreach (explode('/', $item['race']) as $trait) {
		if (!isset($traits[$trait])) $traits[$trait] = 1;
	}
}
$traits = array_keys($traits);
sort($traits);
